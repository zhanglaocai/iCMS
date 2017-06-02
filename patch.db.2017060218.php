<?php
@set_time_limit(0);
if(!defined('iPHP')){
    require (dirname(__file__).'/iCMS.php');
    echo patch_db_2017060218();
}
function patch_db_2017060218(){
    $fields  = apps_db::fields('#iCMS@__spider_url');
    if(empty($fields['appid'])){
        iDB::query("
            ALTER TABLE `#iCMS@__spider_url`
            ADD COLUMN `appid` INT(10) NOT NULL AFTER `id`;
        ");
    }
    $msg.='升级[spider_url]表<iCMS>';
    return $msg;
}

