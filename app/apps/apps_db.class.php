<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author c00lt3a <idreamsoft@qq.com>
 */

class apps_db {
    public static function check_table($table) {
        $variable = apps_db::tables_list();
        foreach ($variable as $key => $value) {
            $tables_list[$value['TABLE_NAME']] = true;
        }
        if($tables_list[$table]){
            return true;
        }
        return false;
    }
    /** Get tables list
    * @return array array($name => $type)
    */
    public static function tables_list() {
        return iDB::all(iDB::version() >= 5
            ? "SELECT TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME"
            : "SHOW TABLES"
        );
    }
    /** Count tables in all databases
    * @param array
    * @return array array($db => $tables)
    */
    public static function count_tables($databases) {
        $return = array();
        foreach ($databases as $db) {
            $return[$db] = count(iDB::all("SHOW TABLES IN " . self::idf_escape($db)));
        }
        return $return;
    }
    /** Get table status
    * @param string
    * @param bool return only "Name", "Engine" and "Comment" fields
    * @return array array($name => array("Name" => , "Engine" => , "Comment" => , "Oid" => , "Rows" => , "Collation" => , "Auto_increment" => , "Data_length" => , "Index_length" => , "Data_free" => )) or only inner array with $name
    */
    public static function table_status($name = "", $fast = false) {
        $return = array();
        foreach (iDB::all($fast && iDB::version() >= 5
            ? "SELECT TABLE_NAME AS Name, Engine, TABLE_COMMENT AS Comment FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() " . ($name != "" ? "AND TABLE_NAME = " . iDB::quote($name) : "ORDER BY Name")
            : "SHOW TABLE STATUS" . ($name != "" ? " LIKE " . iDB::quote(addcslashes($name, "%_\\")) : "")
        ) as $row) {
            if ($row["Engine"] == "InnoDB") {
                // ignore internal comment, unnecessary since MySQL 5.1.21
                $row["Comment"] = preg_replace('~(?:(.+); )?InnoDB free: .*~', '\\1', $row["Comment"]);
            }
            if (!isset($row["Engine"])) {
                $row["Comment"] = "";
            }
            if ($name != "") {
                return $row;
            }
            $return[$row["Name"]] = $row;
        }
        return $return;
    }
    public static function fields($table) {
        $return = array();
        $rs = iDB::all("SHOW FULL COLUMNS FROM " . self::table($table));
        foreach ( $rs as $row) {
            preg_match('~^([^( ]+)(?:\\((.+)\\))?( unsigned)?( zerofill)?$~', $row["Type"], $match);
            $return[$row["Field"]] = array(
                "field"          => $row["Field"],
                "full_type"      => $row["Type"],
                "type"           => $match[1],
                "length"         => $match[2],
                "unsigned"       => ltrim($match[3] . $match[4]),
                "default"        => ($row["Default"] != "" || preg_match("~char|set~", $match[1]) ? $row["Default"] : null),
                "null"           => ($row["Null"] == "YES"),
                "auto_increment" => ($row["Extra"] == "auto_increment"),
                "on_update"      => (preg_match('~^on update (.+)~i', $row["Extra"], $match) ? $match[1] : ""), //! available since MySQL 5.1.23
                "collation"      => $row["Collation"],
                "privileges"     => array_flip(preg_split('~, *~', $row["Privileges"])),
                "comment"        => $row["Comment"],
                "primary"        => ($row["Key"] == "PRI"),
            );
        }
        return $return;
    }
    /** Get table indexes
    * @param string
    * @param string Min_DB to use
    * @return array array($key_name => array("type" => , "columns" => array(), "lengths" => array(), "descs" => array()))
    */
    public static function indexes($table, $connection2 = null) {
        $return = array();
        foreach (iDB::all("SHOW INDEX FROM " . self::table($table)) as $row) {
            $return[$row["Key_name"]]["type"] = ($row["Key_name"] == "PRIMARY" ? "PRIMARY" : ($row["Index_type"] == "FULLTEXT" ? "FULLTEXT" : ($row["Non_unique"] ? "INDEX" : "UNIQUE")));
            $return[$row["Key_name"]]["columns"][] = $row["Column_name"];
            $return[$row["Key_name"]]["lengths"][] = $row["Sub_part"];
            $return[$row["Key_name"]]["descs"][] = null;
        }
        return $return;
    }
    /** Get sorted grouped list of collations
    * @return array
    */
    public static function collations() {
        $return = array();
        foreach (iDB::all("SHOW COLLATION") as $row) {
            if ($row["Default"]) {
                $return[$row["Charset"]][-1] = $row["Collation"];
            } else {
                $return[$row["Charset"]][] = $row["Collation"];
            }
        }
        ksort($return);
        foreach ($return as $key => $val) {
            asort($return[$key]);
        }
        return $return;
    }
    /** Find out if database is information_schema
    * @param string
    * @return bool
    */
    public static function information_schema($db) {
        $version = iDB::version();
        return ($version >= 5 && $db == "information_schema")
            || ($version >= 5.5 && $db == "performance_schema");
    }
    public static function partitioning($value=''){
        $partitioning = "";
        if ($partition_by[$row["partition_by"]]) {
            $partitions = array();
            if ($row["partition_by"] == 'RANGE' || $row["partition_by"] == 'LIST') {
                foreach (array_filter($row["partition_names"]) as $key => $val) {
                    $value = $row["partition_values"][$key];
                    $partitions[] = "\n  PARTITION " . idf_escape($val) . " VALUES " . ($row["partition_by"] == 'RANGE' ? "LESS THAN" : "IN") . ($value != "" ? " ($value)" : " MAXVALUE"); //! SQL injection
                }
            }
            $partitioning .= "\nPARTITION BY $row[partition_by]($row[partition])" . ($partitions // $row["partition"] can be expression, not only column
                ? " (" . implode(",", $partitions) . "\n)"
                : ($row["partitions"] ? " PARTITIONS " . (+$row["partitions"]) : "")
            );
        } elseif (support("partitioning") && preg_match("~partitioned~", $table_status["Create_options"])) {
            $partitioning .= "\nREMOVE PARTITIONING";
        }
    }
    /** Run commands to create or alter table
    * @param string "" to create
    * @param string new name
    * @param array of array($orig, $process_field, $after)
    * @param array of strings
    * @param string
    * @param string
    * @param string
    * @param string number
    * @param string
    * @return bool
    */
    public static function alter_table($table, $name, $fields, /*$foreign,*/ $comment, $auto_increment, $engine='MyISAM', $collation='utf8_general_ci',$partitioning='') {
        $alter = array();
        foreach ($fields as $field) {
            $alter[] = ($field[1]
                ? ($table != "" ? ($field[0] != "" ? "CHANGE " . self::idf_escape($field[0]) : "ADD") : " ") . " " . implode($field[1]) . ($table != "" ? $field[2] : "")
                : "DROP " . self::idf_escape($field[0])
            );
        }
        // $alter = array_merge($alter, $foreign);
        $status = ($comment !== null ? " COMMENT=" . iDB::quote($comment) : "")
            . ($engine ? " ENGINE=" . iDB::quote($engine) : "")
            . ($collation ? " COLLATE " . iDB::quote($collation) : "")
            . ($auto_increment != "" ? " AUTO_INCREMENT=$auto_increment" : "")
        ;
        if ($table == "") {
            return iDB::query("CREATE TABLE " . self::table($name) . " (\n" . implode(",\n", $alter) . "\n)$status$partitioning");
        }
        if ($table != $name) {
            $alter[] = "RENAME TO " . self::table($name);
        }
        if ($status) {
            $alter[] = ltrim($status);
        }
        return ($alter || $partitioning ? iDB::query("ALTER TABLE " . self::table($table) . "\n" . implode(",\n", $alter) . $partitioning) : true);
    }
    /** Run commands to alter indexes
    * @param string escaped table name
    * @param array of array("index type", "name", array("column definition", ...)) or array("index type", "name", "DROP")
    * @return bool
    */
    public static function alter_indexes($table, $alter) {
        foreach ($alter as $key => $val) {
            $alter[$key] = ($val[2] == "DROP"
                ? "\nDROP INDEX " . self::idf_escape($val[1])
                : "\nADD $val[0] " . ($val[0] == "PRIMARY" ? "KEY " : "") . ($val[1] != "" ? idf_escape($val[1]) . " " : "") . "(" . implode(", ", $val[2]) . ")"
            );
        }
        return iDB::query("ALTER TABLE " . self::table($table) . implode(",", $alter));
    }
    /** Run commands to truncate tables
    * @param array
    * @return bool
    */
    public static function truncate_tables($tables) {
        return iDB::query("TRUNCATE TABLE ". $tables);
    }
    /** Drop tables
    * @param array
    * @return bool
    */
    public static function drop_tables($tables) {
        return iDB::query("DROP TABLE " . implode(", ", array_map('table', $tables)));
    }
    /** Move tables to other schema
    * @param array
    * @param array
    * @param string
    * @return bool
    */
    public static function move_tables($tables, $target) {
        $rename = array();
        foreach ($tables as $table) { // views will report SQL error
            $rename[] = self::table($table) . " TO " . self::idf_escape($target) . "." . self::table($table);
        }
        return iDB::query("RENAME TABLE " . implode(", ", $rename));
        //! move triggers
    }
    /** Copy tables to other schema
    * @param array
    * @param array
    * @param string
    * @return bool
    */
    public static function copy_tables($tables) {
        iDB::query("SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");
        foreach ($tables as $table) {
            $name = self::table("copy_$table");
            if (!iDB::query("\nDROP TABLE IF EXISTS $name")
                || !iDB::query("CREATE TABLE $name LIKE " . self::table($table))
                || !iDB::query("INSERT INTO $name SELECT * FROM " . self::table($table))
            ) {
                return false;
            }
        }
        return true;
    }
    /** Get SQL command to create table
    * @param string
    * @param bool
    * @return string
    */
    public static function create_sql($table, $auto_increment) {
        $return = iDB::all("SHOW CREATE TABLE " . self::table($table), 1);
        if (!$auto_increment) {
            $return = preg_replace('~ AUTO_INCREMENT=\\d+~', '', $return); //! skip comments
        }
        return $return;
    }
    /** Get server variables
    * @return array ($name => $value)
    */
    public static function show_variables() {
        return iDB::all("SHOW VARIABLES");
    }
    /** Get process list
    * @return array ($row)
    */
    public static function process_list() {
        return iDB::all("SHOW FULL PROCESSLIST");
    }
    public static function kill_process($val) {
        return iDB::query("KILL " . int($val));
    }
    /** Get status variables
    * @return array ($name => $value)
    */
    public static function show_status() {
        return iDB::all("SHOW STATUS");
    }
    /** Escape database identifier
    * @param string
    * @return string
    */
    public static function idf_escape($idf) {
        return "`" . str_replace("`", "``", $idf) . "`";
    }
    public static function idf_unescape($idf) {
        $last = substr($idf, -1);
        return str_replace($last . $last, $last, substr($idf, 1, -1));
    }
    /** Get escaped table name
    * @param string
    * @return string
    */
    public static function table($idf) {
        return self::idf_escape($idf);
    }
    public static function init() {
        $types = array(); ///< @var array ($type => $maximum_unsigned_length, ...)
        $structured_types = array(); ///< @var array ($description => array($type, ...), ...)
        foreach (array(
            'Numbers' => array("tinyint" => 3, "smallint" => 5, "mediumint" => 8, "int" => 10, "bigint" => 20, "decimal" => 66, "float" => 12, "double" => 21),
            'Date and time' => array("date" => 10, "datetime" => 19, "timestamp" => 19, "time" => 10, "year" => 4),
            'Strings' => array("char" => 255, "varchar" => 65535, "tinytext" => 255, "text" => 65535, "mediumtext" => 16777215, "longtext" => 4294967295),
            'Lists' => array("enum" => 65535, "set" => 64),
            'Binary' => array("bit" => 20, "binary" => 255, "varbinary" => 65535, "tinyblob" => 255, "blob" => 65535, "mediumblob" => 16777215, "longblob" => 4294967295),
            'Geometry' => array("geometry" => 0, "point" => 0, "linestring" => 0, "polygon" => 0, "multipoint" => 0, "multilinestring" => 0, "multipolygon" => 0, "geometrycollection" => 0),
        ) as $key => $val) {
            $types += $val;
            $structured_types[$key] = array_keys($val);
        }
        $unsigned = array("unsigned", "zerofill", "unsigned zerofill"); ///< @var array number variants
        $operators = array("=", "<", ">", "<=", ">=", "!=", "LIKE", "LIKE %%", "REGEXP", "IN", "IS NULL", "NOT LIKE", "NOT REGEXP", "NOT IN", "IS NOT NULL", "SQL"); ///< @var array operators used in select
        $functions = array("char_length", "date", "from_unixtime", "lower", "round", "sec_to_time", "time_to_sec", "upper"); ///< @var array functions used in select
        $grouping = array("avg", "count", "count distinct", "group_concat", "max", "min", "sum"); ///< @var array grouping functions used in select
        $edit_functions = array( ///< @var array of array("$type|$type2" => "$function/$function2") functions used in editing, [0] - edit and insert, [1] - edit only
            array(
                "char" => "md5/sha1/password/encrypt/uuid", //! JavaScript for disabling maxlength
                "binary" => "md5/sha1",
                "date|time" => "now",
            ), array(
                "(^|[^o])int|float|double|decimal" => "+/-", // not point
                "date" => "+ interval/- interval",
                "time" => "addtime/subtime",
                "char|text" => "concat",
            )
        );
        var_dump($structured_types,$edit_functions);
    }
}
