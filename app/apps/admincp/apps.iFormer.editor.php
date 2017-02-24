<!-- 字段编辑器 -->
<div id="iFormer-field-editor" class="hide" style="width:500px;text-align: left;">
  <form id="iFormer-field-form">
    <input type="hidden" name="id" id="iFormer-id"/>
    <input type="hidden" name="type" id="iFormer-type"/>

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
      <span class="add-on">数据类型</span>
      <select name="field" id="iFormer-field" class="chosen-select" style="width:230px;" data-placeholder="请选择...">
        <optgroup label="字符类型">
          <option value='VARCHAR'>字符串(VARCHAR)</option>
          <option value='TEXT'>文本(TEXT)</option>
          <option value='MEDIUMTEXT'>大文本(MEDIUMTEXT)</option>
          <option value='LONGTEXT'>超大文本(LONGTEXT)</option>
        </optgroup>
        <optgroup label="整数类型">
          <option value='TINYINT'>小整数(TINYINT)</option>
          <option value='SMALLINT'>大整数(SMALLINT)</option>
          <option value='MEDIUMINT'>大整数(MEDIUMINT)</option>
          <option value='INT'>大整数(INT)</option>
          <option value='BIGINT'>极大整数(BIGINT)</option>
        </optgroup>
        <optgroup label="浮点数类型">
          <option value='DECIMAL'>小数值(DECIMAL)</option>
          <option value='FLOAT'>单精度(FLOAT)</option>
          <option value='DOUBLE'>双精度(DOUBLE)</option>
        </optgroup>
      </select>
    </div>
    <span class="help-inline">* 必填</span>
    <div class="clearfix"></div>
    <div class="unsigned-wrap hide">
      <div class="input-prepend input-append">
        <span class="add-on">整数类型</span>
        <span class="add-on">无符号
          <input type="radio" name="unsigned" class="uniform" id="iFormer-unsigned-1" value="1"/>
        </span>
        <span class="add-on">有符号
          <input type="radio" name="unsigned" class="uniform" id="iFormer-unsigned-0" value="0"/>
        </span>
      </div>
      <span class="help-inline">* 必填</span>
      <div class="clearfix"></div>
    </div>
    <div class="input-prepend">
      <span class="add-on">数据长度</span>
      <input type="text" name="len" class="span3" id="iFormer-len" value=""/>
    </div>
    <span class="help-inline">* 必填</span>
    <div class="clearfix"></div>
    <div class="input-prepend">
      <span class="add-on">默&nbsp;&nbsp;认&nbsp;值</span>
      <input type="text" name="default" class="span3" id="iFormer-default" value=""/>
    </div>
    <span class="help-inline">选填</span>
    <div class="input-prepend">
      <span class="add-on">字段注释</span>
      <input type="text" name="comment" class="span3" id="iFormer-comment" value=""/>
    </div>
    <span class="help-inline">选填,数据表的comment,不清楚的可不填</span>
    <hr />
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
      <hr />
      <div class="clearfix"></div>
    </div>
    <div class="field-tab-box">
      <ul class="nav nav-tabs" id="field-tab">
        <li class="active"><a href="#field-tab-0" data-toggle="tab"><i class="fa fa-dashboard"></i> UI</a></li>
        <li><a href="#field-tab-1" data-toggle="tab"><i class="fa fa-check-square-o"></i> 验证</a></li>
        <li><a href="#field-tab-2" data-toggle="tab"><i class="fa fa-cog"></i> 数据处理</a></li>
        <li><a href="#field-tab-3" data-toggle="tab"><i class="fa fa-info-circle"></i> 提示</a></li>
        <li><a href="#field-tab-5" data-toggle="tab"><i class="fa fa-cog"></i> 优化</a></li>
        <li><a href="#field-tab-6" data-toggle="tab"><i class="fa fa-code"></i> 脚本</a></li>
      </ul>
      <div class="tab-content">
        <div id="field-tab-0" class="tab-pane active">
          <div class="input-prepend">
            <span class="add-on">字段说明</span>
            <input type="text" name="help" class="span3" id="iFormer-help" value=""/>
          </div>
          <span class="help-inline">选填 </span>
          <div class="input-prepend">
            <span class="add-on">字段样式</span>
            <input type="text" name="class" class="span3" id="iFormer-class" value=""/>
          </div>
          <span class="help-inline">选填</span>
          <div class="clearfix"></div>
          <div id="iFormer-label-after-wrap" class="hide">
            <div class="input-prepend">
              <span class="add-on">扩展信息</span>
              <input type="text" name="label-after" class="span3" id="iFormer-label-after" value=""/>
            </div>
            <span class="help-inline">选填</span>
            <div class="clearfix"></div>
          </div>
          <div class="clearfix"></div>
          <div class="input-prepend">
            <span class="add-on">UI选项</span>
            <select id="iFormer-ui" class="chosen-select" style="width:360px;" data-placeholder="请选择..." multiple="multiple">
              <optgroup label="管理员">
                <option value='admincp-list'>列表显示</option>
              </optgroup>
              <optgroup label="用户">
                <option value='usercp-list'>列表显示</option>
                <option value='usercp-input'>可填写</option>
              </optgroup>
            </select>
            <select multiple="multiple" class="hide" name="ui[]" id="sort-ui"></select>
          </div>
          <span class="help-inline">选填</span>
        </div>
        <div id="field-tab-1" class="tab-pane">
          <span class="help-inline">可多选按顺序验证</span>
          <div class="clearfix mt5"></div>
          <div class="input-prepend">
            <span class="add-on">数据验证</span>
            <select id="iFormer-validate" class="chosen-select" style="width:360px;" data-placeholder="请选择数据验证方式..." multiple="multiple">
              <option value='empty'>不能为空</option>
              <option value='number'>只能输入数字</option>
              <option value='hanzi'>只能输入汉字</option>
              <option value='character'>只能输入字母</option>
              <option value='minmax'>验证范围</option>
              <option value='count'>字数检测</option>
              <option value='email'>E-Mail地址</option>
              <option value='url'>网址</option>
              <option value='phone'>手机号码</option>
              <option value='telphone'>联系电话</option>
              <option value='idcard'>身份证</option>
              <option value='zipcode'>邮政编码</option>
              <option value='defined'>自定义</option>
            </select>
            <select multiple="multiple" class="hide" name="validate[]" id="sort-validate"></select>
          </div>
          <span class="help-inline">选填</span>
          <div class="clearfix"></div>
          <div id="iFormer-validate-minmax" class="hide">
            <div class="input-prepend input-append">
              <span class="add-on">验证范围</span>
              <span class="add-on">最小值</span>
              <input type="text" name="minmax[0]" class="span1" id="iFormer-minmax_0" value=""/>
              <span class="add-on">-</span>
              <input type="text" name="minmax[1]" class="span1" id="iFormer-minmax_1" value=""/>
              <span class="add-on">最大值</span>
            </div>
            <div class="clearfix mt5"></div>
          </div>
          <div id="iFormer-validate-count" class="hide">
            <div class="input-prepend input-append">
              <span class="add-on">字数检测</span>
              <span class="add-on">最小字数</span>
              <input type="text" name="count[0]" class="span1" id="iFormer-count_0" value=""/>
              <span class="add-on">-</span>
              <input type="text" name="count[1]" class="span1" id="iFormer-count_1" value=""/>
              <span class="add-on">最大字数</span>
            </div>
            <div class="clearfix mt5"></div>
          </div>
          <div id="iFormer-validate-defined" class="hide">
            <div class="input-prepend">
              <span class="add-on">代码</span>
              <textarea name="defined" id="iFormer-defined" class="span6" style="height:60px;"></textarea>
            </div>
            <span class="help-inline">可以自己填写提交时数据验证代码(javascript)</span>
          </div>
        </div>
        <div id="field-tab-2" class="tab-pane">
          <span class="help-inline">保存数据时或者展示时执行,可多选按顺序执行</span>
          <div class="clearfix mt5"></div>
          <div class="input-prepend">
            <span class="add-on">数据处理</span>
            <select id="iFormer-func" class="chosen-select" style="width:360px;" data-placeholder="请选择数据处理方式..." multiple="multiple">
              <optgroup label="保存数据时">
                <option value='repeat'>检查重复</option>
                <option value='pinyin'>转成拼音</option>
                <option value='cleanhtml'>清除HTML</option>
                <option value='formathtml'>格式化HTML</option>
                <option value='strtolower'>小写字母</option>
                <option value='strtoupper'>大写字母</option>
                <option value='firstword'>获取头字母大写</option>
                <option value='implode-n'>数组转字符串(换行符)</option>
                <option value='implode-c'>数组转字符串(,)</option>
              </optgroup>
              <optgroup label="通用">
                <option value='explode-n'>分割(换行符)成数组</option>
                <option value='explode-c'>分割(,)成数组</option>
                <option value='rand'>生成随机数</option>
                <option value='json_encode'>数组转json</option>
                <option value='json_decode'>json转数组</option>
                <option value='serialize'>数组序转列化</option>
                <option value='unserialize'>序列化转数组</option>
                <option value='base64_encode'>base64编码</option>
                <option value='base64_decode'>base64解码</option>
                <option value='md5'>md5</option>
              </optgroup>
              <optgroup label="展示时">
                <option value='redirect'>网址跳转</option>
              </optgroup>
            </select>
            <select multiple="multiple" class="hide" name="func[]" id="sort-func"></select>
          </div>
          <span class="help-inline">选填</span>
          <div class="input-prepend hide">
            <span class="add-on">关联应用</span>
            <input type="text" name="app" class="span3" id="iFormer-app" value=""/>
          </div>
        </div>
        <div id="field-tab-3" class="tab-pane">
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
        <div id="field-tab-5" class="tab-pane">
          <div class="input-prepend">
            <span class="add-on">数据优化</span>
            <select id="iFormer-db" class="chosen-select" style="width:360px;" data-placeholder="请选择..." multiple="multiple">
              <option value='index'>索引项</option>
            </select>
            <select multiple="multiple" class="hide" name="db[]" id="sort-db"></select>
          </div>
          <span class="help-inline">选填</span>
        </div>
        <div id="field-tab-6" class="tab-pane">
          <div class="input-prepend">
            <span class="add-on">代码</span>
            <textarea name="javascript" id="iFormer-javascript" class="span6" style="height:60px;"></textarea>
          </div>
          <span class="help-inline">可填写javascript代码</span>
        </div>
      </div>
    </div>
  </form>
</div>
