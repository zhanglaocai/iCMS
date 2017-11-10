<?php
@set_time_limit(0);
if(!defined('iPHP')){
    require (dirname(__file__).'/iCMS.php');
    echo patch_db_2017111000();
}

function patch_db_2017111000(){
    $fields  = apps_db::fields('#iCMS@__user');
    if(empty($fields['favorite'])){
        iDB::query("
            ALTER TABLE `#iCMS@__user`
            CHANGE `share` `favorite` INT(10) UNSIGNED DEFAULT 0 NOT NULL COMMENT '收藏数';
        ");
    }
    $msg.='升级[user]表结构<iCMS>';

    return $msg;
}

