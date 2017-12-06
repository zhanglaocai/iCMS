<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/
class propFunc{
    public static function prop_list($vars){
        $appsFunc = new appsFunc($vars,'prop','pid');
        $appsFunc->set_default_orderby('ASC','sortnum');
        $appsFunc->process_sql_status(false);

        isset($vars['rootid'])&& $appsFunc->add_sql_and('rootid');
        isset($vars['field']) && $appsFunc->add_sql_and('field');
        isset($vars['appid']) && $appsFunc->add_sql_and('appid');
        isset($vars['sapp'])  && $appsFunc->add_sql_and('app',$vars['sapp']);

        $appsFunc->process_sql_id();
        $appsFunc->process_sql_cid();
        $appsFunc->process_sql_pid();

        $vars['orderby'] == 'new' && $appsFunc->set_sql_order('pid');
        $vars['orderby'] == 'rand' && $appsFunc->add_sql_by_rand();

        $resource = $appsFunc->process_get_cache();
        if(empty($resource)){
            $resource = $appsFunc->get_resource();
            if($resource){
                $resource = self::prop_value($vars,$resource);
                $this->process_set_cache($resource);
            }
        }
        return $resource;
    }
	public static function prop_array($vars){
        $field    = $vars['field'];
        $sapp     = $vars['sapp'];
        $variable = propApp::value($field,$sapp);
        $offset = $vars['start']?$vars['start']:0;
		$vars['row'] && $variable = array_slice($variable,$offset, $vars['row']);
        $variable = self::prop_value($vars,$variable);
		return $variable;
	}
    public static function prop_value($vars,$variable){
        foreach ($variable as $key => $value) {
            if($vars['field']){
                $value['url'] = propApp::url($value,$vars['url']);
                $value['link'] = '<a href="'.$value['url'].'" />'.$value['name'].'</a>';
            }else{
                foreach ($value as $k => $v) {
                    $v['url'] = propApp::url($v,$vars['url']);
                    $v['link'] = '<a href="'.$v['url'].'" />'.$v['name'].'</a>';
                    $value[$k] = $v;
                }
            }
            $variable[$key] = $value;
        }
        return $variable;
    }
}
