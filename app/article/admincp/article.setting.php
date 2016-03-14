<?php /**
* @package iCMS
* @copyright 2007-2015, iDreamSoft
* @license http://www.idreamsoft.com iDreamSoft
* @author coolmoo <idreamsoft@qq.com>
* @$Id: setting.php 2412 2014-05-04 09:52:07Z coolmoo $
*/
defined('iPHP') OR exit('What are you doing?');
?>
<script type="text/javascript">
</script>
<div id="setting-article" class="tab-pane">
  <h3 class="title">文章设置</h3>
  <div class="clearfloat"></div>
  <div class="input-prepend">
    <span class="add-on">文章图片居中</span>
    <div class="switch" data-on-label="启用" data-off-label="关闭">
      <input type="checkbox" data-type="switch" name="config[article][pic_center]" id="article_pic_center" <?php echo $config['article']['pic_center']?'checked':''; ?>/>
    </div>
  </div>
  <span class="help-inline">启用后文章内的图片会自动居中</span>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend">
    <span class="add-on">文章图片链接</span>
    <div class="switch" data-on-label="启用" data-off-label="关闭">
      <input type="checkbox" data-type="switch" name="config[article][pic_next]" id="article_pic_next" <?php echo $config['article']['pic_next']?'checked':''; ?>/>
    </div>
  </div>
  <span class="help-inline">启用后文章内的图片都会带上下一页的链接和点击图片进入下一页的链接</span>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend">
    <span class="add-on">文章分页+N</span>
    <input type="text" name="config[article][pageno_incr]" class="span3" id="article_pageno_incr" value="<?php echo $config['article']['pageno_incr'] ; ?>"/>
  </div>
  <span class="help-inline">设置此项后,内容分页数比实际页数+N页,不增加请设置为0</span>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend">
    <span class="add-on">编辑器</span>
    <div class="switch" data-on-label="Editor.md" data-off-label="UEditor">
      <input type="checkbox" data-type="switch" name="config[article][editor]" id="article_editor" <?php echo $config['article']['editor']?'checked':''; ?>/>
    </div>
  </div>
  <span class="help-inline">Editor.md为markdown编辑器,默认使用UEditor</span>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend">
    <span class="add-on">自动排版</span>
    <div class="switch">
      <input type="checkbox" data-type="switch" name="config[publish][autoformat]" id="publish_autoformat" <?php echo $config['publish']['autoformat']?'checked':''; ?>/>
    </div>
  </div>
  <span class="help-inline">开启后发布文章时,程序会自动对内容进行清理无用代码.采集时推荐开启.如果内容格式丢失 请关闭此项</span>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend">
    <span class="add-on">编辑器图片</span>
    <div class="switch">
      <input type="checkbox" data-type="switch" name="config[publish][catch_remote]" id="publish_catch_remote" <?php echo $config['publish']['catch_remote']?'checked':''; ?>/>
    </div>
  </div>
  <span class="help-inline">开启后发表文章时只要有图片 就会自动下载</span>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend">
    <span class="add-on">下载远程图片</span>
    <div class="switch">
      <input type="checkbox" data-type="switch" name="config[publish][remote]" id="publish_remote" <?php echo $config['publish']['remote']?'checked':''; ?>/>
    </div>
  </div>
  <span class="help-inline">开启后发表文章时该选项默认为选中状态</span>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend">
    <span class="add-on">提取缩略图</span>
    <div class="switch">
      <input type="checkbox" data-type="switch" name="config[publish][autopic]" id="publish_autopic" <?php echo $config['publish']['autopic']?'checked':''; ?>/>
    </div>
  </div>
  <span class="help-inline">开启后发表文章时该选项默认为选中状态</span>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend">
    <span class="add-on">提取摘要</span>
    <div class="switch">
      <input type="checkbox" data-type="switch" name="config[publish][autodesc]" id="publish_autodesc" <?php echo $config['publish']['autodesc']?'checked':''; ?>/>
    </div>
  </div>
  <span class="help-inline">开启后发表文章时程序会自动提取文章部分内容为文章摘要</span>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend">
    <span class="add-on">提取摘要字数</span>
    <input type="text" name="config[publish][descLen]" class="span3" id="publish_descLen" value="<?php echo $config['publish']['descLen'] ; ?>"/>
  </div>
  <span class="help-inline">设置自动提取内容摘要字数</span>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend">
    <span class="add-on">内容自动分页</span>
    <div class="switch">
      <input type="checkbox" data-type="switch" name="config[publish][autoPage]" id="publish_autoPage" <?php echo $config['publish']['autoPage']?'checked':''; ?>/>
    </div>
  </div>
  <span class="help-inline">开启后发表文章时程序会分页</span>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend">
    <span class="add-on">内容分页字数</span>
    <input type="text" name="config[publish][AutoPageLen]" class="span3" id="publish_AutoPageLen" value="<?php echo $config['publish']['AutoPageLen'] ; ?>"/>
  </div>
  <span class="help-inline">设置自动内容分页字数</span>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend">
    <span class="add-on">检查标题重复</span>
    <div class="switch">
      <input type="checkbox" data-type="switch" name="config[publish][repeatitle]" id="publish_repeatitle" <?php echo $config['publish']['repeatitle']?'checked':''; ?>/>
    </div>
  </div>
  <span class="help-inline">开启后不能发表相同标题的文章</span>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend">
    <span class="add-on">列表显示图片</span>
    <div class="switch">
      <input type="checkbox" data-type="switch" name="config[publish][showpic]" id="publish_showpic" <?php echo $config['publish']['showpic']?'checked':''; ?>/>
    </div>
  </div>
  <span class="help-inline">开启后文章列表将会显示缩略图</span>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend">
    <span class="add-on">后台文章过滤</span>
    <div class="switch">
      <input type="checkbox" data-type="switch" name="config[article][filter]" id="article_filter" <?php echo $config['article']['filter']?'checked':''; ?>/>
    </div>
  </div>
  <span class="help-inline">开启台 后台输入的文章都将经过关键字过滤</span>
</div>
