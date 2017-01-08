<?php
/**
 * 大部份方法移植自adminer
 */

class apps_db {
    public static function make_sql($vars=null){
      $field    = $vars['field'];  //字段类型
      $label    = $vars['label']; //字段名称
      $name     = $vars['name'];  //字 段 名
      $default  = $vars['default']; //默 认 值
      $len      = $vars['len']; //数据长度

      empty($name) && $name = iPinyin::get($label);
      $field = strtolower($field);
      $DEFAULT = " DEFAULT '$default'";
      switch ($field) {
        case 'varchar':
        case 'multivarchar':
          $data_type = 'varchar';
          $data_len  = '('.$len.')';
        break;
        case 'tinyint':
          $data_type = 'tinyint';
          $data_len  = '(1)';
          $default   = (int)$default;
        break;
        case 'int':
        case 'time':
          $data_type = 'int';
          $data_len  = '(10)';
          $default   = (int)$default;
        break;
        case 'bigint':
          $data_type = 'bigint';
          $data_len  = '(20)';
          $default   = (int)$default;
        break;
        case 'radio':
        case 'select':
          $data_type = 'smallint';
          $data_len  = '(6)';
        break;
        case 'checkbox':
        case 'multiselect':
          $data_type = 'varchar';
          $data_len  = '(255)';
        break;
        case 'image':
        case 'file':
          $data_type = 'varchar';
          $data_len  = '(255)';
        break;
        case 'multiimage':
        case 'multifile':
          $data_type = 'varchar';
          $data_len  = '(10240)';
        break;
        case 'text':
          $data_type = 'text';
          $DEFAULT   = '';
        break;
        case 'mediumtext':
        case 'editor':
          $data_type = 'mediumtext';
          $DEFAULT   = '';
        break;
        default:
         $data_type = 'varchar';
         $data_len  = '(255)';
        break;
      }

      return "`$name` $data_type$data_len NOT NULL $DEFAULT COMMENT '$label'";
      // return "ADD COLUMN `$name` $data_type$data_len DEFAULT '$default' NOT NULL  COMMENT '$label'";
    }
    public static function base_fields(){
      $sql = self::CREATE_TABLE('test',null,true);
      preg_match_all("@`(.+)`\s(.+)\sDEFAULT\s'(.*?)'\sCOMMENT\s'(.+)',@", $sql, $matches);
      return $matches;
    }
    public static function create_table($name,$fields=null,$sql=false){
      $create_sql = "CREATE TABLE `#iCMS@__{$name}` (";
      $create_sql.= "
        `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键 自增ID',
        `cid` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '栏目id',
        `ucid` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户分类',
        `pid` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '属性',
        `sortnum` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
        `title` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '标题',
        `editor` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '编辑 用户名',
        `userid` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户ID',
        `pubdate` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '发布时间',
        `postime` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '提交时间',
        `tpl` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '模板',
        `hits` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '总点击数',
        `hits_today` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '当天点击数',
        `hits_yday` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '昨天点击数',
        `hits_week` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '周点击',
        `hits_month` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '月点击',
        `favorite` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '收藏数',
        `comments` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '评论数',
        `good` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '顶',
        `bad` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '踩',
        `creative` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '内容类型 1:原创 0:转载',
        `weight` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '权重',
        `mobile` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1:手机发布 0:pc',
        `postype` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '类型 0用户 1管理员',
        `status` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态 0:草稿,1:正常,2:回收,3:审核,4:不合格',
      ";
      if($fields){
        $fsql_array = array();

        foreach ($fields as $key => $_field) {
          if(stripos($_field, 'UI:')===false){
            $output = array();
            parse_str($_field,$output);
            $output && $fsql_array[] = self::make_sql($output);
          }
        }
        $fsql_array && $create_sql.= implode(",\n", $fsql_array).',';
      }
      $create_sql.="
        PRIMARY KEY (`id`),
        KEY `id` (`status`,`id`),
        KEY `hits` (`status`,`hits`),
        KEY `pubdate` (`status`,`pubdate`),
        KEY `hits_week` (`status`,`hits_week`),
        KEY `hits_month` (`status`,`hits_month`),
        KEY `cid_hits` (`status`,`cid`,`hits`)
      ) ENGINE=MYISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

// print_r($create_sql);
// exit;
     if($sql){
        return $create_sql;
     }
     return iDB::query($create_sql);
    }
    /** Filter length value including enums
    * @param string
    * @return string
    */
    public static function process_length($length) {
        $enum_length = "'(?:''|[^'\\\\]|\\\\.)*'";
        return (preg_match("~^\\s*\\(?\\s*$enum_length(?:\\s*,\\s*$enum_length)*+\\s*\\)?\\s*\$~", $length) && preg_match_all("~$enum_length~", $length, $matches)
            ? "(" . implode(",", $matches[0]) . ")"
            : preg_replace('~^[0-9].*~', '(\0)', preg_replace('~[^-0-9,+()[\]]~', '', $length))
        );
    }

    /** Create SQL string from field type
    * @param array
    * @param string
    * @return string
    */
    public static function process_type($field, $collate = "COLLATE") {
        $unsigned = array("unsigned", "zerofill", "unsigned zerofill");
        return " $field[type]"
            . self::process_length($field["length"])
            . (preg_match('~(^|[^o])int|float|double|decimal~', $field["type"]) && in_array($field["unsigned"], $unsigned) ? " $field[unsigned]" : "")
            . (preg_match('~char|text|enum|set~', $field["type"]) && $field["collation"] ? " $collate " . q($field["collation"]) : "")
        ;
    }

    /** Create SQL string from field
    * @param array basic field information
    * @param array information about field type
    * @return array array("field", "type", "NULL", "DEFAULT", "ON UPDATE", "COMMENT", "AUTO_INCREMENT")
    */
    public static function process_field($field, $type_field) {
        $default = $field["default"];
        return array(
            self::idf_escape(trim($field["field"])),
            self::process_type($type_field),
            ($field["null"] ? " NULL" : " NOT NULL"), // NULL for timestamp
            (isset($default) ? " DEFAULT " . (
                (preg_match('~time~', $field["type"]) && preg_match('~^CURRENT_TIMESTAMP$~i', $default))
                || (iPHP_DB_TYPE == "sqlite" && preg_match('~^CURRENT_(TIME|TIMESTAMP|DATE)$~i', $default))
                || ($field["type"] == "bit" && preg_match("~^([0-9]+|b'[0-1]+')\$~", $default))
                || (iPHP_DB_TYPE == "pgsql" && preg_match("~^[a-z]+\\(('[^']*')+\\)\$~", $default))
                ? $default : q($default)) : ""),
            (preg_match('~timestamp|datetime~', $field["type"]) && $field["on_update"] ? " ON UPDATE $field[on_update]" : ""),
            (self::support("comment") && $field["comment"] != "" ? " COMMENT " . iDB::quo($field["comment"]) : ""),
            ($field["auto_increment"] ? auto_increment() : null),
        );
    }
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
        } elseif (self::support("partitioning") && preg_match("~partitioned~", $table_status["Create_options"])) {
            $partitioning .= "\nREMOVE PARTITIONING";
        }
    }
    /** Generate modifier for auto increment column
    * @return string
    */
    function auto_increment() {
        $auto_increment_index = " PRIMARY KEY";
        // don't overwrite primary key by auto_increment
        if ($_GET["create"] != "" && $_POST["auto_increment_col"]) {
            foreach (self::indexes($_GET["create"]) as $index) {
                if (in_array($_POST["fields"][$_POST["auto_increment_col"]]["orig"], $index["columns"], true)) {
                    $auto_increment_index = "";
                    break;
                }
                if ($index["type"] == "PRIMARY") {
                    $auto_increment_index = " UNIQUE";
                }
            }
        }
        return " AUTO_INCREMENT$auto_increment_index";
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
    /** Check whether a feature is supported
    * @param string "comment", "copy", "database", "drop_col", "dump", "event", "kill", "materializedview", "partitioning", "privileges", "procedure", "processlist", "routine", "scheme", "sequence", "status", "table", "trigger", "type", "variables", "view", "view_trigger"
    * @return bool
    */
    function support($feature) {
        $version = iDB::version();
        return !preg_match("~scheme|sequence|type|view_trigger" . ($version < 5.1 ? "|event|partitioning" . ($version < 5 ? "|routine|trigger|view" : "") : "") . "~", $feature);
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
