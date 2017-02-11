<?php /**
* @package iCMS
* @copyright 2007-2017, iDreamSoft
* @license http://www.idreamsoft.com iDreamSoft
* @author coolmoo <idreamsoft@qq.com>
*/
defined('iPHP') OR exit('What are you doing?');
admincp::head();
?>
<style>
.app_list_desc{font-size: 12px;color: #666;}
.nopadding .tab-content{padding: 0px;}
</style>
<script type="text/javascript">
$(function(){
$("#<?php echo APP_FORMID;?>").batch();
});
</script>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-search"></i> </span>
    <h5>搜索</h5>
  </div>
  <div class="pull-right">
    <a style="margin: 10px;" class="btn btn-mini" href="<?php echo APP_FURI; ?>&do=cache" target="iPHP_FRAME"><i class="fa fa-refresh"></i> 更新缓存</a>
  </div>
  <div class="widget-content">
    <form action="<?php echo iPHP_SELF ; ?>" method="get" class="form-inline">
      <input type="hidden" name="app" value="<?php echo admincp::$APP_NAME;?>" />
      <div class="input-prepend input-append">
        <span class="add-on">每页</span>
        <input type="text" name="perpage" id="perpage" value="<?php echo $maxperpage ; ?>" style="width:36px;"/>
        <span class="add-on">条记录</span> </div>
        <div class="input-prepend input-append">
          <span class="add-on">关键字</span>
          <input type="text" name="keywords" class="span2" id="keywords" value="<?php echo $_GET['keywords'] ; ?>" />
          <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> 搜 索</button>
        </div>
      </form>
    </div>
  </div>
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon">
      <input type="checkbox" class="checkAll" data-target="#<?php echo APP_BOXID;?>" />
    </span>
    <ul class="nav nav-tabs" id="category-tab">
      <li class="active"><a href="#apps-system" data-toggle="tab"><i class="fa fa-cubes"></i> 系统应用</a></li>
      <li><a href="#apps-user" data-toggle="tab"><i class="fa fa-cubes"></i> 其它应用</a></li>
    </ul>
  </div>
  <div class="widget-content nopadding">
    <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
      <div class="tab-content">
        <div id="apps-system" class="tab-pane active">
          <table class="table table-bordered table-condensed table-hover">
            <thead>
              <tr>
                <th style="width:40px;">APPID</th>
                <th>标识</th>
                <th>名称</th>
                <th>数据表</th>
                <th class="span3">模板标签</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($rs as $key => $data) {
              $table = apps::table_item($data['table']);
              $config = json_decode($data['config'],true);
              $installed = apps::installed($data['app'],$data['type']);
              // $admincp = __ADMINCP__.'='.$data['app'];
              // if($data['admincp']){
              //   $admincp = __ADMINCP__.'='.$data['admincp'];
              //   if($data['admincp']=='iPHP_SELF'){
              //     $admincp = iPHP_SELF;
              //   }
              //   if($data['admincp']=='null'){
              //     $admincp = null;
              //   }
              // }
              ?>
              <tr id="tr<?php echo $data['id'] ; ?>">
                <td><b><?php echo $data['id'] ; ?></b></td>
                <td>
                  <span class="label label-success"><?php echo apps::$type_array[$data['type']] ; ?></span>
                  <b><?php echo $data['app'] ; ?></b>
                </td>
                <td>
                  <?php echo $data['name'] ; ?>
                  <p class="app_list_desc"><?php echo $config['info'] ; ?></p>
                </td>
                <td>
                  <?php if(is_array($table)){ ?>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <td>表名</td>
                        <td>主键</td>
                        <td>名称</td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      foreach ((array)$table as $tkey => $tval) {
                      ?>
                      <tr>
                        <td><?php echo $tval['name'] ; ?></td>
                        <td><?php echo $tval['primary'] ; ?></td>
                        <td><?php echo $tval['label'] ; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                  <?php }else{
                    echo '<span class="label">无相关表</span>';
                  }
                  ?>
                </td>
                <td>
                  <?php
                  if($config['template']){
                    foreach ((array)$config['template'] as $key => $tpltags) {
                      echo '<a href="http://www.idreamsoft.com/iCMS/doc/'.str_replace(array(':','$'), array('_',''), $tpltags).'" target="_blank" title="点击查看模板标签说明">&lt;!--{'.$tpltags.'}--&gt;</a><br />';
                    }
                  }else{
                    echo '<span class="label">无相关标签</span>';
                  }
                  ?>
                  <td>
                      <a href="<?php echo APP_URI; ?>&do=app_add&appid=<?php echo $data['id'] ; ?>" class="btn btn-small"><i class="fa fa-edit"></i> 添加内容</a>
                      <a href="<?php echo APP_URI; ?>&do=add&id=<?php echo $data['id'] ; ?>" class="btn btn-small"><i class="fa fa-edit"></i> 编辑</a>
                    <?php if ($installed) {?>
                      <?php if($data['type']){?>
                        <?php if($data['status']){?>
                          <a href="<?php echo APP_URI; ?>&do=update&_args=status:0&id=<?php echo $data['id'] ; ?>" target="iPHP_FRAME" class="btn btn-small btn-warning" onclick="return confirm('关闭应用不会删除数据，但应用将不可用\n确定要关闭应用?');"><i class="fa fa-close"></i> 关闭</a>
                        <?php }else{?>
                          <a href="<?php echo APP_URI; ?>&do=update&_args=status:1&id=<?php echo $data['id'] ; ?>" target="iPHP_FRAME" class="btn btn-small btn-success"><i class="fa fa-check"></i> 启用</a>
                        <?php }?>
                      <?php }?>
                      <?php if($admincp){ ?>
                        <a href="<?php echo $admincp; ?>" class="btn btn-small" target="_blank"><i class="fa fa-list-alt"></i> <?php echo $data['title'] ; ?></a>
                      <?php }?>
                      <?php if($data['type']){?>
                        <a href="<?php echo APP_FURI; ?>&do=uninstall&id=<?php echo $data['id'] ; ?>" target="iPHP_FRAME" class="del btn btn-small btn-danger" title='永久删除'  onclick="return confirm('卸载应用会清除应用所有数据！\n卸载应用会清除应用所有数据！\n卸载应用会清除应用所有数据！\n确定要卸载?\n确定要卸载?\n确定要卸载?');"/><i class="fa fa-trash-o"></i> 卸载</a>
                      <?php }?>
                    <?php } else {?>
                      <?php if($data['type']){?>
                        <a href="<?php echo APP_FURI; ?>&do=install&id=<?php echo $data['id'] ; ?>&appname=<?php echo $data['app'] ; ?>" target="iPHP_FRAME" class="del btn btn-small btn-primary" title='安装' /><i class="fa fa-plug"></i> 安装应用</a>
                        <a href="<?php echo APP_FURI; ?>&do=del&id=<?php echo $data['id'] ; ?>&appname=<?php echo $data['app'] ; ?>" target="iPHP_FRAME" class="del btn btn-small btn-danger" title='删除' /><i class="fa fa-add"></i> 删除应用</a>
                      <?php }?>
                    <?php }?>
                  </td>
                </tr>
                <?php }  ?>
              </tbody>
              <tr>
                <td colspan="7">
                  <div class="pagination pagination-right" style="float:right;"><?php echo iUI::$pagenav ; ?></div>
                  <div class="input-prepend input-append mt20">
                    <span class="add-on">全选
                      <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo APP_BOXID;?>" />
                    </span>
                    <div class="btn-group dropup" id="iCMS-batch">
                      <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-wrench"></i> 批 量 操 作 </a>
                      <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a data-toggle="batch" data-action="dels"><i class="fa fa-trash-o"></i> 删除</a></li>
                      </ul>
                    </div>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div id="apps-user" class="tab-pane">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php admincp::foot();?>
