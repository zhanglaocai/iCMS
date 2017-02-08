<!-- 字段编辑器 -->
<div id="field_edit" class="hide" style="width:500px;text-align: left;">
  <form id="field_form">
    <input type="hidden" name="id" id="iFormer-id"/>
    <input type="hidden" name="type" id="iFormer-type"/>
    <input type="hidden" name="tag" id="iFormer-tag"/>
    <input type="hidden" name="field" id="iFormer-field"/>
    <div class="input-prepend">
      <span class="add-on">字段名称</span>
      <input type="text" name="label" class="span3" id="iFormer-label" value=""/>
    </div>
    <span class="help-inline">* 必填</span>
    <div class="input-prepend">
      <span class="add-on">字&nbsp;&nbsp;段&nbsp;名</span>
      <input type="text" name="name" class="span3" id="iFormer-name" value=""/>
    </div>
    <span class="help-inline">* 必填</span>
    <div class="clearfix"></div>
    <div class="input-prepend">
      <span class="add-on">默&nbsp;&nbsp;认&nbsp;值</span>
      <input type="text" name="default" class="span3" id="iFormer-default" value=""/>
    </div>
    <span class="help-inline">选填</span>
    <div id="iFormer-option-wrap" class="hide">
      <div class="input-prepend">
        <span class="add-on">选项列表</span>
        <textarea type="text" name="option" class="span3" id="iFormer-option" disabled/></textarea>
      </div>
      <span class="help-inline">* 必填.<br />
        格式: 选项=值;<br />
      例:电脑=pc;<br />
      手机=phone;
    </span>
    </div>
    <div class="clearfix"></div>
    <div class="input-prepend">
      <span class="add-on">字段说明</span>
      <input type="text" name="help" class="span3" id="iFormer-help" value=""/>
    </div>
    <span class="help-inline">选填</span>
    <div class="input-prepend">
      <span class="add-on">字段样式</span>
      <input type="text" name="class" class="span3" id="iFormer-class" value=""/>
    </div>
    <span class="help-inline">选填</span>
    <div class="input-prepend">
      <span class="add-on">数据长度</span>
      <input type="text" name="len" class="span3" id="iFormer-len" value=""/>
    </div>
    <span class="help-inline">选填</span>
    <div class="clearfix"></div>
    <div class="field-tab-box">
      <ul class="nav nav-tabs" id="field-tab">
        <li class="active"><a href="#field-tab-1" data-toggle="tab"><i class="fa fa-check-square-o"></i> 验证</a></li>
        <li><a href="#field-tab-2" data-toggle="tab"><i class="fa fa-cog"></i> 数据处理</a></li>
        <li><a href="#field-tab-3" data-toggle="tab"><i class="fa fa-info-circle"></i> 提示</a></li>
        <li><a href="#field-tab-4" data-toggle="tab"><i class="fa fa-user"></i> 用户</a></li>
        <li><a href="#field-tab-5" data-toggle="tab"><i class="fa fa-cog"></i> 优化</a></li>
      </ul>
      <div class="tab-content">
        <div id="field-tab-1" class="tab-pane active">
          <div class="input-prepend">
            <span class="add-on">数据验证</span>
            <select name="validate[]" id="iFormer-validate" class="chosen-select" style="width:360px;" data-placeholder="请选择数据验证方式..." multiple="multiple">
              <option value='empty'>不能为空</option>
              <option value='num'>只能为数字</option>
              <option value='minmax'>验证范围</option>
              <option value='len'>字数检测</option>
              <option value='repeat'>检查重复</option>
              <option value='email'>E-Mail地址</option>
              <option value='url'>网址</option>
              <option value='phone'>手机号码</option>
              <option value='telphone'>联系电话</option>
            </select>
          </div>
          <span class="help-inline">选填</span>
          <div class="clearfix"></div>
          <div id="minmax" class="input-prepend input-append hide">
            <span class="add-on">验证范围</span>
            <span class="add-on">最小值</span>
            <input type="text" name="validate_min" class="span1" id="iFormer-validate_min" value=""/>
            <span class="add-on">-</span>
            <input type="text" name="validate_max" class="span1" id="iFormer-validate_max" value=""/>
            <span class="add-on">最大值</span>
          </div>
        </div>
        <div id="field-tab-2" class="tab-pane">
          <div class="input-prepend">
            <span class="add-on">数据处理</span>
            <select name="fun[]" id="iFormer-fun" class="chosen-select" style="width:360px;" data-placeholder="请选择数据处理方式..." multiple="multiple">
              <option value='html'>清除HTML</option>
              <option value='format'>格式化HTML</option>
              <option value='pinyin'>拼音</option>
              <option value='explode-n'>分割成数组(换行符)</option>
              <option value='explode-c'>分割成数组(,)</option>
              <option value='strtolower'>小写字母</option>
              <option value='strtoupper'>大写字母</option>
              <option value='rand'>随机数</option>
              <option value='json_encode'>数组转json</option>
              <option value='base64'>base64</option>
              <option value='serialize'>数组序列化</option>
              <option value='redirect'>网址跳转</option>
            </select>
          </div>
          <span class="help-inline">选填</span>
          <div class="input-prepend">
            <span class="add-on">关联应用</span>
            <input type="text" name="app" class="span3" id="iFormer-app" value=""/>
          </div>
        </div>
        <div id="field-tab-3" class="tab-pane">
          <span class="help-inline">支持bootstrap v2.3.2样式 或者请先定义css在填写样式名</span>
          <div class="clearfix"></div>
          <div class="input-prepend">
            <span class="add-on">默认提示</span>
            <input type="text" name="holder" class="span3" id="iFormer-holder" value=""/>
          </div>
          <div class="clearfloat"></div>
          <div class="input-prepend">
            <span class="add-on">错误提示</span>
            <input type="text" name="error" class="span3" id="iFormer-error" value=""/>
          </div>
        </div>
        <div id="field-tab-4" class="tab-pane">
          <div class="input-prepend">
            <span class="add-on">用户选项</span>
            <select name="user[]" id="iFormer-user" class="chosen-select" style="width:360px;" data-placeholder="请选择数据处理方式..." multiple="multiple">
              <option value='show'>用户后台显示</option>
              <option value='fill'>用户后台可填写</option>
            </select>
          </div>
          <span class="help-inline">选填</span>
        </div>
        <div id="field-tab-5" class="tab-pane">
          <div class="input-prepend">
            <span class="add-on">数据优化</span>
            <select name="db[]" id="iFormer-db" class="chosen-select" style="width:360px;" data-placeholder="请选择数据处理方式..." multiple="multiple">
              <option value='index'>搜索项/索引</option>
            </select>
          </div>
          <span class="help-inline">选填</span>
        </div>
      </div>
    </div>
  </form>
</div>
