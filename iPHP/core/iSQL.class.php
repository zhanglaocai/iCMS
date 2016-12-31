<?php
/**
 * iPHP - i PHP Framework
 * Copyright (c) 2012 iiiphp.com. All rights reserved.
 *
 * @author coolmoo <iiiphp@qq.com>
 * @website http://www.iiiphp.com
 * @license http://www.iiiphp.com/license
 * @version 2.0.0
 */
class iSQL {
    public static function get_rand_ids($table,$where=null,$limit='10',$primary='id'){
        $whereSQL = $where?
            "{$where} AND `{$table}`.`{$primary}` >= rand_id":
            " WHERE `{$table}`.`{$primary}` >= rand_id";
        // $limitNum = rand(2,10);
        // $prelimit = ceil($limit/rand(2,10));
        $randSQL  = "
            SELECT `{$table}`.`{$primary}` FROM `{$table}`
            JOIN (SELECT
                  ROUND(RAND() * (
                      (SELECT MAX(`{$table}`.`{$primary}`) FROM `{$table}`) -
                      (SELECT MIN(`{$table}`.`{$primary}`) FROM `{$table}`)
                    ) + (SELECT MIN(`{$table}`.`{$primary}`) FROM `{$table}`)
                 ) AS rand_id) RAND_DATA
            {$whereSQL}
            LIMIT $limit;
        ";
        $randIdsArray = iDB::all($randSQL);
        // $randIdsArray = null;
        // for ($i=0; $i <=$prelimit; $i++) {
        //     $randIdsArray[$i] = array('id'=>iDB::value($randSQL));
        //     echo iDB::$last_query;
        // }
        return $randIdsArray;
    }
    public static function update_hits($all=true,$hit=1){
        $timeline = iPHP::timeline();
        // var_dump($timeline);
        $pieces = array();
        $all && $pieces[] = '`hits` = hits+'.$hit;
        foreach ($timeline as $key => $bool) {
            $field = "hits_{$key}";
            if($key=='yday'){
                if($bool==1){
                    $pieces[]="`hits_yday` = hits_today";
                }elseif ($bool>1) {
                    $pieces[]="`hits_yday` = 0";
                }
                continue;
            }
            $pieces[]="`{$field}` = ".($bool?"{$field}+{$hit}":$hit);
        }
        return implode(',', $pieces);
    }
    public static function where($vars, $field, $not = false, $noand = false, $table = '') {
        if (is_bool($vars) || empty($vars)) {
            return '';
        }
        if (!is_array($vars) && strpos($vars,',') !== false){
            $vars = explode(',', $vars);
        }

        if (is_array($vars)) {
            foreach ($vars as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $vk => $vv) {
                        $vas[] = "'" . addslashes($vv) . "'";
                    }
                }else{
                    $vas[] = "'" . addslashes($value) . "'";
                }
            }
            $vas  = array_unique($vas);
            $vars = implode(',', $vas);
            $sql  = $not ? " NOT IN ($vars)" : " IN ($vars) ";
        } else {
            $vars = addslashes($vars);
            $sql = $not ? "<>'$vars' " : "='$vars' ";
        }
        $table && $table .= '.';
        $sql = "{$table}`{$field}`" . $sql;
        if ($noand) {
            return $sql;
        }
        $sql = ' AND ' . $sql;
        return $sql;
    }
    public static function multi_ids($ids,$only=false) {
        $is_multi = false;
        if(is_array($ids)){
            $is_multi = true;
        }
        if(!is_array($ids) && strpos($ids, ',') !== false){
            $ids = explode(',', $ids);
            $is_multi = true;
        }
        if($only){
            return $ids;
        }
        return array($ids,$is_multi);
    }
    public static function values($rs, $field = 'id',$ret='string',$quote="'",$key=null) {
        if (empty($rs)) {
            return false;
        }

        $resource = array();
        foreach ((array) $rs AS $rkey =>$_vars) {
            if($key===null){
                $_key = $rkey;
            }else{
                $_key = $_vars[$key];
            }

            if ($field === null) {
                $_vars!=='' && $resource[$_key] = $quote . $_vars . $quote;
            } else {
                if(is_array($field)){
                    foreach ($field as $fk => $fv) {
                        $_vars[$fv]!=='' && $resource[$_key][$fk] = $quote . $_vars[$fv] . $quote;
                    }
                }else{
                    $_vars[$field]!=='' && $resource[$_key] = $quote . $_vars[$field] . $quote;
                }
            }
        }
        unset($rs);
        if ($resource) {
            is_array($field) OR $resource = array_unique($resource);
            if($ret=='array'){
                return $resource;
            }else{
                $resource = implode(',', $resource);
                return $resource;
            }
        }
        return false;
    }
    public static function select_map($where, $type = null, $field = 'iid') {
        if (empty($where)) {
            return false;
        }
        $i = 0;
        foreach ($where as $key => $value) {
            $as = ' map';
            $i && $as .= $i;
            $_FROM[] = $key . $as;
            $_WHERE[] = str_replace($key, $as, $value);
            $_FIELD[] = $as . ".`{$field}`";
            $i++;
        }
        $_field = $_FIELD[0];
        $_count = count($_FIELD);
        if ($_count > 1) {
            foreach ($_FIELD as $fkey => $fd) {
                $fkey && array_push($_WHERE, $_field . ' = ' . $fd);
            }
        }
        if ($type == 'join') {
            return array('from' => implode(',', $_FROM), 'where' => implode(' AND ', $_WHERE));
        }
        return 'SELECT ' . $_field . ' AS ' . $field . ' FROM ' . implode(',', $_FROM) . ' WHERE ' . implode(' AND ', $_WHERE);
    }
}
