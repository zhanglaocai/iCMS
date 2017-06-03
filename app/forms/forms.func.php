<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/
class formsFunc{
    public static function forms_array($vars){
        if(isset($vars['formid'])){
            $formid = $vars['formid'];
            $form   = forms::get($formid);
        }else if(isset($vars['form'])){
            is_array($vars['form']) && $form = $vars['form'];
        }
        return $form;
    }
	public static function forms_create($vars){
        $form = self::forms_array($vars);

        if(empty($form)||empty($form['status'])){
            return false;
        }
        isset($vars['main']) && former::$template['main'] = $vars['main'];
        isset($vars['label']) && former::$template['label'] = $vars['label'];
        foreach ($vars as $key => $value) {
            if(stripos($key, 'class_') !== false){
                $key = str_replace('class_', '', $key);
                former::$template['class'][$key] = $value;
            }
        }
        isset($vars['prefix']) && former::$prefix = $vars['prefix']?:'iDATA';
        former::$config['value']   = array(
            'userid'   => user::$userid,
            'username' => user::$username,
            'nickname' => user::$nickname
        );
        former::$config['gateway'] = 'usercp';
        former::create($form);
        echo former::layout("#former_".$form['id']);
	}
    public static function forms_data($vars){
        $form = self::forms_array($vars);

        if(empty($form)||empty($form['status'])){
            return false;
        }
        $maxperpage = isset($vars['row'])?(int)$vars['row']:"10";
        $cache_time = isset($vars['time'])?(int)$vars['time']:"-1";


        $table_array = apps::get_table($form);
        $table       = $table_array['table'];
        $primary     = $table_array['primary'];

        if($form['fields']){
            $fields = former::fields($form['fields']);
        }

        $where_sql = "WHERE 1=1";

        if($vars['keywords']) {
          $search = array();
          if(empty($vars['sfield'])){
            foreach ((array)$fields as $fi => $field) {
              $field['field']=='VARCHAR' && $search[] = $field['id'];
            }
            $search && $where_sql.=" AND CONCAT(".implode(',', $search).") REGEXP '{$vars['keywords']}'";
          }else{
            $where_sql.=" AND ".$vars['sfield']." REGEXP '{$vars['keywords']}'";
          }
        }
        isset($vars['where']) && $where_sql .= $vars['where'];

        $by = $vars['by']=="ASC"?"ASC":"DESC";
        $order_sql = 'ORDER BY '.($vars['orderby']?$vars['orderby']:$primary).' '.$by;

        $offset = 0;
        $limit  = "LIMIT {$maxperpage}";
        if($vars['page']){
            $total  = iCMS::page_total_cache("SELECT count(*) FROM `{$table}` {$where_sql}",null,iCMS::$config['cache']['page_total']);
            $multi  = iUI::page(array('total'=>$total,'perpage'=>$maxperpage,'unit'=>iUI::lang('iCMS:page:list'),'nowindex'=>$GLOBALS['page']));
            $offset = $multi->offset;
            $limit  = "LIMIT {$offset},{$maxperpage}";
            iView::assign("forms_list_total",$total);
        }
        if($vars['orderby']=='rand'){
            $ids_array = iSQL::get_rand_ids($table,$where_sql,$maxperpage,$primary);
        }

        $hash = md5($where_sql.$order_sql.$limit);

        if($vars['cache']){
            $cache_name = iPHP_DEVICE.'/forms_dlist/'.$hash;
            $vars['page'] && $cache_name.= "/".(int)$GLOBALS['page'];
            $resource = iCache::get($cache_name);
            if($resource){
                return $resource;
            }
        }
        if($offset){
            if(empty($ids_array)){
                $ids_array = iDB::all("SELECT `{$primary}` FROM `{$table}` {$where_sql} {$order_sql} {$limit}");
                // $vars['cache'] && iCache::set($map_cache_name,$ids_array,$cache_time);
            }
        }

        if($ids_array){
            $ids       = iSQL::values($ids_array);
            $ids       = $ids?$ids:'0';
            $where_sql = "WHERE `{$table}`.`{$primary}` IN({$ids})";
            $limit     = '';
        }

        $rs = iDB::all("SELECT * FROM `{$table}` {$where_sql} {$order_sql} {$limit}");
        if($rs){
            $resource = array();
            foreach ((array)$rs as $key => $value) {
                foreach ($fields as $fi => $field) {
                    $resource[$key][$fi] = array(
                        'name'=>$field['label'],
                        'value'=>$value[$field['id']]
                    );
                }
            }
            $vars['cache'] && iCache::set($cache_name,$resource,$cache_time);
        }
        return $resource;
    }
    public static function forms_list($vars){
        $maxperpage = isset($vars['row'])?(int)$vars['row']:"100";
        $cache_time = isset($vars['time'])?(int)$vars['time']:"-1";

        $where_sql  = "WHERE `status`='1'";

        isset($vars['type'])  && $where_sql.= " AND `type`='".$vars['type']."'";
        isset($vars['pic'])  && $where_sql.= " AND `pic`!=''";
        isset($vars['nopic'])&& $where_sql.= " AND `pic`=''";

        isset($vars['startdate'])    && $where_sql.=" AND `addtime`>='".strtotime($vars['startdate'])."'";
        isset($vars['enddate'])     && $where_sql.=" AND `addtime`<='".strtotime($vars['enddate'])."'";

        $by=$vars['by']=="ASC"?"ASC":"DESC";
        switch ($vars['orderby']) {
            case "id":      $order_sql=" ORDER BY `id` $by";            break;
            case "app":      $order_sql=" ORDER BY `app` $by";            break;
            case "addtime": $order_sql=" ORDER BY `addtime` $by";    break;
            default:        $order_sql=" ORDER BY `id` DESC";
        }
        if($vars['cache']){
            $cache_name = iPHP_DEVICE.'/forms_list/'.md5($where_sql);
            $resource   = iCache::get($cache_name);
        }
        if(empty($resource)){
            $resource = iDB::all("SELECT * FROM `#iCMS@__forms` {$where_sql} {$order_sql} LIMIT $maxperpage");
            if($resource)foreach ($resource as $key => $value) {
                $value['pic'] && $value['pic']  = iFS::fp($value['pic'],'+http');
                $value['url'] = iURL::router(array('forms:id',$value['id']));
                $resource[$key] = $value;
            }
            $vars['cache'] && iCache::set($cache_name,$resource,$cache_time);
        }
        return $resource;
    }
}
