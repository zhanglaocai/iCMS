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
        if(isset($vars['formid'])||isset($vars['fid'])){
            $formid = $vars['formid'];
            $vars['fid'] && $formid = $vars['fid'];
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
        if(empty($form['config']['enable'])){
            iUI::warning('该表单设置不允许用户提交.');
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
        former::$config['option'] = $vars['option'];
        former::create($form);

        $token = $form['token'];
        if(empty($token)){
            $token  = authcode($form['id'].'#'.$_SERVER['REQUEST_TIME'],'decode');
            iPHP::set_cookie('token_time', $_SERVER['REQUEST_TIME'], 600);
        }

        $layout = '<input name="action" type="hidden" value="save" />';
        $layout.= '<input name="fid" type="hidden" value="'.$form['id'].'" />';
        $layout.= '<input name="token" type="hidden" value="'.$token.'" />';
        $layout.= former::layout("#former_".$form['id']);

        if($vars['assign']){
            iView::assign($vars['assign'],$layout);
        }else{
            echo $layout;
        }
	}
    public static function forms_data($vars){
        $form = self::forms_array($vars);

        if(empty($form)||empty($form['status'])){
            return false;
        }

        $table_array = apps::get_table($form);
        $table       = $table_array['table'];
        $primary     = $table_array['primary'];

        if($form['fields']){
            $fields = former::fields($form['fields']);
            iView::assign("forms_fields",$fields);
        }
        $appsFunc = new appsFunc($vars,'forms_data',$primary,iCMS_APP_FORMS,$table);
        $appsFunc->process_sql_status(false);
        $appsFunc->process_sql_id();

        if($vars['keywords']) {
          $search = array();
          if(empty($vars['sfield'])){
            foreach ((array)$fields as $fi => $field) {
              $field['field']=='VARCHAR' && $search[] = $field['id'];
            }
            $search && $appsFunc->where_sql.=" AND CONCAT(".implode(',', $search).") REGEXP '{$vars['keywords']}'";
          }else{
            if($vars['pattern']){
                $appsFunc->where_sql.=" AND ".$vars['sfield']." {$vars['pattern']} '{$vars['keywords']}'";
            }else{
                $appsFunc->where_sql.=" AND ".$vars['sfield']." REGEXP '{$vars['keywords']}'";
            }
          }
        }else{
          if($vars['pattern']){
            $appsFunc->where_sql.=" AND ".$vars['sfield']." {$vars['pattern']} '{$vars['keywords']}'";
          }
        }

        isset($vars['where'])  && $appsFunc->add_sql_where();
        isset($vars['page'])   && $appsFunc->process_page();
        isset($vars['orderby'])&& $appsFunc->process_sql_orderby();
        $resource = $appsFunc->process_get_cache();

        if(empty($resource)){
            $rs = $appsFunc->get_resource();
            if($rs){
                if($vars['data']){
                    $data = array();
                    $idArray = iSQL::values($rs,$primary,'array',null,'id');
                    foreach ($form['table'] as $tkey => $tvalue) {
                        if($tvalue['union'] && $idArray){
                          $pkey = $tvalue['union'];
                            $a = iDB::all("SELECT * FROM `{$tvalue['table']}` WHERE `{$pkey}` in (".implode(',', $idArray).")");
                            foreach ((array)$a as $k => $v) {
                              $data[$v[$pkey]] = $v;
                            }
                        }
                    }
                }
                $resource = array();
                foreach ((array)$rs as $key => $value) {
                    foreach ($fields as $fi => $field) {
                        $id = $value[$primary];
                        if($data[$id] && is_array($data[$id])){
                            $value+=$data[$id];
                        }
                        formerApp::vars($field,$fi,$value,$vars);
                        $resource[$key] = $value;
                        // $resource[$key][$fi] = array(
                        //     'name'=>$field['label'],
                        //     'value'=>$value[$field['id']]
                        // );
                    }
                }
                $appsFunc->process_keys($resource);
                $appsFunc->process_set_cache($resource);
            }
        }
        return $resource;
    }
    public static function forms_list($vars){
        $vars['default:rows'] = 100;

        $appsFunc = new appsFunc($vars,'forms');
        $appsFunc->process_sql_status();
        $appsFunc->process_sql_id();

        isset($vars['type']) && $appsFunc->add_sql_and('type');
        isset($vars['pic'])  && $appsFunc->add_sql_and('pic','','!=');
        isset($vars['nopic'])&& $appsFunc->add_sql_and('pic','');

        isset($vars['starttime'])&& $this->add_sql_and('addtime',strtotime($vars['starttime']),'>=');
        isset($vars['endtime'])  && $this->add_sql_and('addtime',strtotime($vars['endtime']),'<=');
        $appsFunc->process_sql_orderby(array('app','addtime'));
        $resource = $appsFunc->process_get_cache();

        if(empty($resource)){
            $resource = $appsFunc->get_resource();
            foreach ($resource as $key => $value) {
                $resource[$key] = formsApp::value($value,true);
            }
            $appsFunc->process_keys($resource);
            $appsFunc->process_set_cache($resource);
        }
        return $resource;
    }
}
