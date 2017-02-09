<!-- 字段选择 -->
<div class="iFormer-design">
  <div class="widget-title">
    <span class="icon"> <i class="fa fa-cog"></i> </span>
    <h5 class="brs">字段</h5>
    <ul class="nav nav-tabs" id="fields-tab">
      <li class="active"><a href="#fields-tab-base" data-toggle="tab"><i class="fa fa-info-circle"></i> 简易字段</a></li>
      <li><a href="#fields-tab-func" data-toggle="tab"><i class="fa fa-cog"></i> 功能字段</a></li>
      <li><a href="#fields-tab-addons" data-toggle="tab"><i class="fa fa-cog"></i> 附加字段</a></li>
    </ul>
  </div>
  <div id="fields-tab-content" class="tab-content">
    <div id="fields-tab-base" class="tab-pane active">
      <ul>
        <li i="layout" tag="br" class="br">
          <span class="fa fa-arrows-h"></span>
          <p>换行符</p>
        </li>
        <li i="field" tag="input" field="VARCHAR" len="255">
          <span class="fb-icon fb-icon-input"></span>
          <p>单行</p>
        </li>
        <li i="field" tag="input" field="VARCHAR" len="5120">
          <span class="fb-icon fb-icon-input"></span>
          <p>单行长文本</p>
        </li>
        <li i="field" tag="textarea" field="TEXT">
          <span class="fb-icon fb-icon-textarea"></span>
          <p>多行</p>
        </li>
        <li i="field" tag="input" field="VARCHAR" len="255" label="邮箱">
          <span class="fb-icon fb-icon-mail"></span>
          <p>邮箱</p>
        </li>
        <li i="field" tag="input" type="date" field="INT" len="10" label="日期">
          <span class="fb-icon fb-icon-date"></span>
          <p>日期</p>
        </li>
        <li i="field" tag="input" type="datetime" field="INT" len="10" label="时间">
          <span class="timeIcon fb-icon fb-icon-datetime"></span>
          <p>日期时间</p>
        </li>
        <li i="field" tag="input" type="radio" field="TINYINT" len="1" label="单选">
          <span class="fb-icon fb-icon-radio"></span>
          <p>单选框</p>
        </li>
        <li i="field" tag="input" type="checkbox" field="TINYINT" len="1" label="复选">
          <span class="fb-icon fb-icon-checkbox"></span>
          <p>复选框</p>
        </li>
        <li i="field" tag="select" type="select" field="TINYINT" len="1" label="列表">
          <span class="fb-icon fb-icon-dropdown"></span>
          <p>下拉列表</p>
        </li>
        <li i="field" tag="select" type="multiple" field="VARCHAR" len="255" label="多选">
          <span class="multiselect fb-icon fb-icon-multiselect"></span>
          <p>多选列表</p>
        </li>
        <li i="field" tag="input" type="number" field="TINYINT" len="1" label="数字">
          <span class="fb-icon fb-icon-number"></span>
          <p>数字</p>
        </li>
        <li i="field" tag="input" type="number" field="INT" len="10" label="数字">
          <span class="fb-icon fb-icon-number"></span>
          <p>大数字</p>
        </li>
        <li i="field" tag="input" type="number" field="BIGINT" len="20" label="数字">
          <span class="fb-icon fb-icon-number"></span>
          <p>超大数字</p>
        </li>
        <li i="field" tag="input" type="decimal" field="DECIMAL" len="6,2" label="小数">
          <span class="fb-icon fb-icon-decimal"></span>
          <p>小数</p>
        </li>
        <li i="field" tag="input" type="percentage" field="DECIMAL" len="3,2" label="百分比" label-after="%">
          <span class="fb-icon fb-icon-percentage"></span>
          <p>百分比</p>
        </li>
        <li i="field" tag="input" type="currency" field="INT" len="10" label="货币" label-after="¥">
          <span class="fb-icon fb-icon-currency"></span>
          <p>货币</p>
        </li>
        <li i="field" tag="input" field="VARCHAR" len="255" label="链接">
          <span class="fb-icon fb-icon-url"></span>
          <p>Url</p>
        </li>
        <!--                         <li i="field" fieldtype="32">
          <span class="lookupIcon fb-icon fb-icon-lookup"></span>
          <p class="lookupConent">查找</p>
        </li>
        <li i="field" fieldtype="14">
          <span class="addnotesIcon fb-icon fb-icon-addnotes"></span>
          <p class="addnotestext">添加备注</p>
        </li>
        <li i="field" fieldtype="99">
          <span class="subformIcon fb-icon fb-icon-subform"></span>
          <p class="subformText">子表单</p>
        </li>
        <li i="field" fieldtype="31">
          <span class="autonumberIcon fb-icon fb-icon-autonumber"></span>
          <p class="lookupConent">自动编号</p>
        </li>
        <li i="field" fieldtype="15">
          <span class="formulaIcon fb-icon fb-icon-formula"></span>
          <p class="formulaText">公式</p>
        </li>
        <li i="field" fieldtype="36">
          <span class="signatureIcon fb-icon fb-icon-signature"></span>
          <p class="lookupConent">签名 </p>
        </li>
        <li i="field" fieldtype="30">
          <span class="usersIcon fb-icon fb-icon-name" style="margin-top:1px;"></span>
          <p class="lookupConent">用户</p>
        </li> -->
      </ul>
    </div>
    <div id="fields-tab-func" class="tab-pane">
      <ul>
        <li i="field" tag="dialog" field="VARCHAR" len="255" label="选择框">
          <span class="fb-icon fb-icon-deciton"></span>
          <p>选择框</p>
        </li>
        <li i="field" tag="image" field="VARCHAR" len="255" label="图片">
          <span class="fb-icon fb-icon-image"></span>
          <p>图片上传</p>
        </li>
        <li i="field" tag="multimage" field="TEXT" label="多图">
          <span class="fb-icon fb-icon-image"></span>
          <p>多图上传</p>
        </li>
        <li i="field" tag="file" field="VARCHAR" len="255" label="上传">
          <span class="fb-icon fb-icon-fileupload"></span>
          <p>上传文件</p>
        </li>
        <li i="field" tag="multifile" field="TEXT" label="批量上传">
          <span class="fb-icon fb-icon-fileupload"></span>
          <p>批量上传</p>
        </li>
        <li i="field" tag="prop" field="VARCHAR" len="255" label="属性">
          <span class="fb-icon fb-icon-prop"></span>
          <p>属性</p>
        </li>
        <li i="field" tag="seccode" label="验证码">
          <span class="fb-icon fb-icon-url"></span>
          <p>验证码</p>
        </li>
      </ul>
    </div>
    <div id="fields-tab-addons" class="tab-pane">
      <ul>
        <li i="field" tag="textarea" field="MEDIUMTEXT" label="超大文本">
          <span class="fb-icon fb-icon-textarea"></span>
          <p>超大文本</p>
        </li>
        <li i="field" tag="editor" field="MEDIUMTEXT" label="编辑器">
          <span class="fb-icon fb-icon-richtext"></span>
          <p>编辑器</p>
        </li>
      </ul>
    </div>
  </div>
</div>

