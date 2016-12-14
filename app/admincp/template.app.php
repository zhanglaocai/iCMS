<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.0.0
*/
class templateApp{
    function __construct() {
    }
    function do_iCMS(){
        $res       = iFS::folder('template',array('htm','css','js','png','jpg','gif'));
        $dirRs     = $res['DirArray'];
        $fileRs    = $res['FileArray'];
        $pwd       = $res['pwd'];
        $parent    = $res['parent'];
        $URI       = $res['URI'];
        $navbar    = true;
        $file_edit = true;

    	include admincp::view("files.explorer");
    }

}
