<script type="text/javascript" src="./app/admincp/ui/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="./app/apps/ui/iFormer/iFormer.js"></script>
<link rel="stylesheet" href="./app/apps/ui/iFormer/iFormer.css" type="text/css" />

<div class="fields-fluid">
  <ul id="custom_field_list">

  </ul>
  <div class="clearfloat mt10"></div>
</div>
<?php include admincp::view("apps.iFormer.fields");?>
<div class="clearfloat mt10"></div>
<div class="alert alert-info alert-block">
  <h5><i class="fa fa-support"></i> 注意事项</h5>
  <p><i class="fa fa-arrows-h"></i> 换行符 双击可删除</p>
  <p><i class="fa fa-arrows-h"></i> 换行符 属于占位符 最终界面上将以10px空白替换.效果请参考文章添加页</p>
  <p>本界面元素只作编辑用,最终界面展现效果请使用预览功能</p>
</div>
<script type="text/javascript">
var  html;
<?php
if($rs['fields'])foreach ($rs['fields'] as $key => $value) {
    if($value=='UI:BR'){
        $output = array('tag'=>'br');
    }else{
        parse_str($value,$output);
    }
    echo "
        html = iFormer.render($('div'),".json_encode($output).",null,'".$output['id']."');
        $('#custom_field_list').append(html);
    ";
}
?>
</script>
