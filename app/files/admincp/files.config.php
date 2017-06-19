<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/
defined('iPHP') OR exit('What are you doing?');
?>
    <div class="input-prepend"> <span class="add-on">附件URL</span>
        <!-- <textarea name="config[FS][url]" id="FS_url" class="span6" style="height: 90px;"><?php echo $config['FS']['url'] ; ?></textarea> -->
        <input type="text" name="config[FS][url]" class="span4" id="FS_url" value="<?php echo $config['FS']['url'] ; ?>" />
    </div>
    <span class="help-inline"><!-- 可填写多个,系统将随机选择.<br />格式一行一条<br /> -->如果访问不到,请自行调整.<br />请填写完整的URL,例:https://www.icmsdev.com/res/</span>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend"> <span class="add-on">文件保存目录</span>
        <input type="text" name="config[FS][dir]" class="span4" id="FS_dir" value="<?php echo $config['FS']['dir'] ; ?>" />
    </div>
    <span class="help-inline">相对于程序根目录</span>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend input-append"> <span class="add-on">目录结构</span>
        <input type="text" name="config[FS][dir_format]" class="span4" id="FS_dir_format" value="<?php echo $config['FS']['dir_format'] ; ?>" />
        <div class="btn-group" to="#FS_dir_format">
            <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-question-circle"></i> 帮助</a>
            <ul class="dropdown-menu">
                <li>
                    <a href="#Y"><span class="label label-inverse">Y</span> 4位数年份</a>
                </li>
                <li>
                    <a href="#y"><span class="label label-inverse">y</span> 2位数年份</a>
                </li>
                <li>
                    <a href="#m"><span class="label label-inverse">m</span> 月份01-12</a>
                </li>
                <li>
                    <a href="#n"><span class="label label-inverse">n</span> 月份1-12</a>
                </li>
                <li>
                    <a href="#d"><span class="label label-inverse">n</span> 日期01-31</a>
                </li>
                <li>
                    <a href="#j"><span class="label label-inverse">j</span> 日期1-31</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#EXT"><span class="label label-inverse">EXT</span> 文件类型</a>
                </li>
            </ul>
        </div>
    </div>
    <span class="help-inline">为空全部存入同一目录</span>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend"> <span class="add-on">允许上传类型</span>
        <input type="text" name="config[FS][allow_ext]" class="span4" id="FS_allow_ext" value="<?php echo $config['FS']['allow_ext'] ; ?>" />
    </div>
