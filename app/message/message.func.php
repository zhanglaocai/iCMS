<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/
class messageFunc{
    public static function message_list($vars=null){
        $vars['default:rows'] = 30;
        $appsFunc = new appsFunc($vars,'message');
        $appsFunc->process_sql_status();

        $type   = $vars['type'];
        $friend = (int)$vars['friend'];

        if($type=='sys'){
            $sql = " AND `userid`='".message::SYS_UID."' AND `friend` ='".user::$userid."'";
        }
        if($friend){
            $sql = " AND `userid`='".user::$userid."' AND `friend`='".$friend."'";
        }
        if($sql){
            $appsFunc->where_sql.= $sql;
            $p_fields  = 'COUNT(*)';
            $s_fields  = '*';
        }else{
            //包含系统信息
            // $where_sql.= " AND (`userid`='".user::$userid."' OR (`userid`='".message::SYS_UID."' AND `friend`='".user::$userid."'))";

            $appsFunc->where_sql.= " AND `userid`='".user::$userid."'";
            $appsFunc->where_sql.= ' GROUP BY `friend` DESC';
            $p_fields  = 'COUNT(DISTINCT id)';
            $s_fields  = 'id,COUNT(id) AS msg_count,`userid`, `friend`, `send_uid`, `send_name`, `receiv_uid`, `receiv_name`, `content`, `type`, `sendtime`, `readtime`';
        }
        isset($vars['where'])&& $appsFunc->add_sql_where();
        isset($vars['page']) && $appsFunc->process_page(null,$p_fields);

        $resource = $appsFunc->get_resource($s_fields);
        foreach ($resource as $key => $value) {
            $value['sender']   = user::info($value['send_uid'],$value['send_name']);
            $value['receiver'] = user::info($value['receiv_uid'],$value['receiv_name']);
            $value['label']    = message::$type_map[$value['type']];

            if($value['userid']==$value['send_uid']){
                $value['is_sender'] = true;
                $value['user']      = $value['receiver'];
            }
            if($value['userid']==$value['receiv_uid']){
                $value['is_sender'] = false;
                $value['user']      = $value['sender'];
            }
            if($value['type']=='1'){
                $value['type_text'] = 'msg';
            }
            if($value['type']=='2'||$value['type']=='0'){
                $value['type_text'] = 'sys';
            }
            $value['url']   = iURL::router(array('user:inbox:uid',$value['user']['uid']));
            $resource[$key] = $value;
        }
        return $resource;
    }
}
