<?php
/**
 * iPHP - i PHP Framework
 * Copyright (c) 2012 iiiphp.com. All rights reserved.
 *
 * @author coolmoo <iiiphp@qq.com>
 * @website http://www.iiiphp.com
 * @license http://www.iiiphp.com/license
 * @version 2.0.0
 * iFormer 表单生成器
 */

class iFormer {
    public static $line = true;

    public static $html     = null;
    public static $validate = null;
    public static $script   = null;

    public static $prefix   = 'iDATA';
    public static $config   = array();

    public static $callback   = array();
    public static $variable   = array();


    public static function multi_value($rs,$fieldArray) {
        foreach ($fieldArray as $key => $field) {
            if(in_array($field['type'], array('category','multi_category','prop','multi_prop'))){
                $value = iSQL::values($rs,$field['name'],'array',null);
                $value = iSQL::explode_var($value);
                $call  = self::$callback[$field['type']];
                if($call && is_callable($call)){
                    self::$variable[$field['name']] = call_user_func_array($call, array($value));
                }
            }
        }
        return self::$variable;
    }
    /**
     * 将由查询字符串(query string)组成的数组转换成二维数组
     * @param  [type]  $data [查询字符串 数组]
     * @param  boolean $ui   [是否把UI标识返回数组]
     * @return [type]        [description]
     */
    public static function fields($data,$ui=false) {
        $array = array();
        foreach ($data as $key => $value) {
          $output = array();
          if($value=='UI:BR'){
              $ui && $output = array('type'=>'br');
          }else{
              parse_str($value,$output);
          }
          $output && $array[$key] = $output;
        }
        return $array;
    }

    public static function widget($name,$attr=null) {
        $widget = new iQuery($name);
        $attr && $widget->attr($attr);
        return $widget;
    }

    public static function render($app,$rs) {
        $fields = iFormer::fields($app['fields'],true);
        foreach ($fields as $key => $value) {
          iFormer::html($value,$rs[$value['name']]);
          iFormer::validate($value);
        }
    }
    public static function html($field,$value=null) {
        if($field['type']=='br'){
            self::$line = true;
            $div = self::widget("div")->addClass("clearfloat mt10");
        }else{
            $id      = $field['id'];
            $name    = $field['name'];
            $class   = $field['class'];
            $default = $field['default'];

            list($type,$type2) = explode(':', $field['type']);
            isset($value) OR $value = $default;
            if(!isset($value)){
                if(in_array($field['field'], array('BIGINT','INT','MEDIUMINT','SMALLINT','TINYINT'))){
                    $value ='0';
                }else{
                    $value ='';
                }
            }

            $div_class="input-prepend";

            // if(self::$line === false){
            //     $div_class.=' input-append';
            // }
            $field['label'] && $label = ' <span class="add-on">'.$field['label'].'</span>';
            $attr = compact(array('id','name','type','class','value'));
            $attr['id']   = self::$prefix.'_'.$id.'';
            $attr['name'] = self::$prefix.'['.$name.']';
            $field['holder'] && $attr['placeholder'] = $field['holder'];
            $input = self::widget('input',$attr);
            $input->val($value);

            switch ($type) {
                case 'multi_image':
                case 'multi_file':
                    unset($attr['type']);
                    $div_class.=' input-append';
                    $input  = self::widget('textarea',$attr)->css('height','150px');
                    $picbtn = filesAdmincp::pic_btn($attr['id'],null,true);
                    $html   = $input.$picbtn;
                    $script = self::script('$("#'.$attr['id'].'").autoTextarea({maxHeight:300});',true);
                break;
                case 'image':
                case 'file':
                    $div_class.=' input-append';
                    $input->attr('type','text');
                    $picbtn = filesAdmincp::pic_btn($attr['id'],null,true);
                    $html = $input.$picbtn;
                break;
                case 'tpldir':
                case 'tplfile':
                    $div_class.=' input-append';
                    $input->attr('type','text');
                    $click ='file';
                    $type=='tpldir' && $click = 'dir';
                    $modal = filesAdmincp::modal_btn($name,$click,$attr['id']);
                    $html  = $input.$modal;
                break;
                case 'txt_prop':
                    $div_class.=' input-append';
                    $input->attr('type','text');
                    $prop = propAdmincp::btn_group($name,self::$config['app']['app'],$attr['id']);
                    $html = $input.$prop;
                break;
                case 'prop':
                case 'multi_prop':
                    unset($attr['type']);
                    $attr['data-placeholder']= '请选择'.$field['label'].'...';
                    if(strpos($type,'multi')!==false){
                        $attr['name']     = $attr['name'].'[]';
                        $attr['multiple'] = 'true';
                        $attr['data-placeholder']= '请选择'.$field['label'].'(可多选)...';
                        $orig = self::widget('input',array('type'=>'hidden','name'=>self::$prefix.'[_orig_'.$name.']','value'=>$value));
                    }
                    $select = self::widget('select',$attr)->addClass('chosen-select');
                    $option='<option value="0">默认'.$field['label'].'['.$name.'=\'0\']</option>';
                    $option.= propAdmincp::get($name,null,'option',null,self::$config['app']['app']);
                    $html = $select->html($option).$orig;
                    $script = self::script('iCMS.select("'.$attr['id'].'","'.trim($value).'");',true);
                break;
                case 'date':
                case 'datetime':
                    $attr['class'].= ' ui-datepicker';
                    $attr['type'] = 'text';
                    $input = self::widget('input',$attr);
                    if($type=='date'){
                        $value = get_date($value,'Y-m-d');
                    }else{
                        $value = get_date($value,'Y-m-d H:i:s');
                    }
                    $input->val($value);
                    $html = $input;
                break;
                case 'user_category':
                    if(self::$config['gateway']=='admincp'){
                        $div_class.=' hide';
                        $html = $input->attr('type','hidden');
                    }
                break;
                case 'PRIMARY':
                case 'union':
                case 'hidden':
                    $div_class.=' hide';
                    $html = $input->attr('type','hidden');
                break;
                case 'userid':
                    $div_class.=' hide';
                    $value OR $value = self::$config['value']['userid'];
                    $input->attr('type','text');
                    $html = $input->val($value);
                break;
                case 'nickname':
                case 'username':
                    $value OR $value = self::$config['value'][$type];
                    $input->attr('type','text');
                    $html = $input->val($value);
                break;
                case 'number':
                    $html = $input->attr('type','text');
                break;
                case 'text':
                    $html = $input;
                break;
                case 'seccode':
                    $input->addClass('seccode')->attr('maxlength',"4")->attr('type','text');
                    $seccode = publicApp::seccode();
                    $html = $input.$seccode;
                break;
                case 'editor':
                    if(self::$config['gateway']=='admincp'){
                        $label         = null;
                        $attr['class'] = '';
                        $attr['type']  = 'text/plain';
                        $attr['id']    = 'editor-body-'.$attr['id'];
                        $div_class     = 'editor-container';
                        $script        = editorAdmincp::ueditor_script($attr['id']);
                    }
                    $html = self::widget('textarea',$attr);
                break;
                case 'multitext':
                    unset($attr['type']);
                    $input = self::widget('textarea',$attr);
                    $html = $input->css('height','300px');
                break;
                case 'textarea':
                    unset($attr['type']);
                    $input = self::widget('textarea',$attr);
                    $html = $input->css('height','150px');
                break;
                case 'switch':
                case 'radio':
                case 'checkbox':
                    $span = self::widget('span',array('class'=>'add-on'));
                    if($type=='checkbox'){
                        $attr['name']     = $attr['name'].'[]';
                        $attr['multiple'] = 'true';
                    }
                    if($field['option']){
                        $div_class  .=' input-append';
                        $optionArray = explode(";", $field['option']);
                        foreach ($optionArray as $ok => $val) {
                            $val = trim($val,"\r\n");
                            if($val){
                                list($opt_text,$opt_value) = explode("=", $val);
                                $attr2 = $attr;
                                $attr2['value'] = $opt_value;
                                $attr2['class'].= ' '.$attr2['id'];
                                $attr2['id'].='_'.$ok;
                                $span->append($opt_text.self::widget('input',$attr2).' ');
                            }
                        }
                        $html = $span;
                    }else{
                        $html = $span->html($input);
                    }
                    $script= self::script('iCMS.checked(".'.$attr['id'].'","'.$value.'");',true);

                    if($type=='switch'){
                        $attr['type'] = 'checkbox';
                        $input = self::widget('input',$attr);
                        $html  ='<div class="switch">'.$input.'</div>';
                    }
                break;
                case 'multi_category':
                case 'category':
                    unset($attr['type']);
                    $attr['data-placeholder']= '== 请选择所属'.$field['label'].' ==';
                    if(strpos($type,'multi')!==false){
                        $attr['name']     = $attr['name'].'[]';
                        $attr['multiple'] = 'true';
                        $attr['data-placeholder']= '请选择'.$field['label'].'(可多选)...';
                        $orig = self::widget('input',array('type'=>'hidden','name'=>self::$prefix.'[_orig_'.$name.']','value'=>$value));
                    }
                    $select = self::widget('select',$attr)->addClass('chosen-select');
                    $option = category::appid(self::$config['app']['id'],'cs')->select();
                    $script = self::script('iCMS.select("'.$attr['id'].'","'.trim($value).'");',true);
                    $html = $select->html($option).$orig;
                break;
                case 'multiple':
                case 'select':
                    $attr['class'] = $field['class'];
                    if(strpos($type,'multi')!==false){
                        unset($attr['type']);
                        $attr['multiple'] = 'true';
                        $attr['name']     = $attr['name'].'[]';
                        $_input = self::widget('input',array('type'=>'hidden','name'=>self::$prefix.'[_orig_'.$name.']','value'=>$value));
                    }
                    $html = self::widget('select',$attr)->addClass('chosen-select');
                    if($field['option']){
                        $optionArray = explode(";", $field['option']);
                        foreach ($optionArray as $ok => $val) {
                            $val = trim($val,"\r\n");
                            if($val){
                                list($opt_text,$opt_value) = explode("=", $val);
                                $option.='<option value="'.$opt_value.'">'.$opt_text.' ['.$name.'="'.$opt_value.'"]</option>';
                            }
                        }
                        $html = $html->html($option);
                        $script = self::script('iCMS.select("'.$attr['id'].'","'.trim($value).'");',true);
                    }
                    $html.= $_input;
                break;
                case 'device':
                    $value = iPHP::$mobile?'1':'0';
                break;
                case 'postype':
                    $value = self::$config['gateway']=='admincp'?'1':'0';
                break;
                default:
                    $input->attr('type','text');
                    $html = $input;
                break;
            }
            if($type2=='hidden'){
                $div_class ='hide';
                $html = $input->attr('type','hidden');
            }
            if($field['label-after']){
                $label_after = self::widget('span',array('class'=>'add-on'));
                $label_after->html($field['label-after']);
                $div_class  .=' input-append';
            }

            $div='<div class="'.$div_class.'">'.$label.$html.$label_after.'</div>'.$script;

            $field['help'] && $div.='<span class="help-inline">'.$field['help'].'</span>';
            // $div.= self::script($field['javascript']);
            self::$line = false;
        }
        self::$html.= $div;
        // var_dump($field);
        // var_dump($script);
        // echo $div;
        // exit;
        return $div;
    }
    public static function script($code=null,$script=false,$ready=true) {
        if($code){
            $code = '+function(){'.$code.'}();';
            $ready && $code = '$(function(){'.$code.'});';
            return $script?'<script>'.$code.'</script>':$code;
        }
    }
    public static function js_test($id,$label,$msg,$pattern) {
        $script = '
            var '.$id.'_msg = "'.$msg.'",pattern = '.$pattern.';
            if(!pattern.test('.$id.'_value)){
                iCMS.UI.alert('.$id.'_error||"['.$label.'],"+'.$id.'_msg+",请重新填写!");
                '.$id.'.focus();
                return false;
            }';
        return $script;
    }
    public static function validate($field_array,$lang='js',$value='') {
        if(empty($field_array['validate'])) return;

        $id    = self::$prefix.'_'.$field_array['id'].'';
        $name  = self::$prefix.'['.$field_array['name'].']';
        $label = $field_array['label'];
        $type  = $field_array['type'];
        $error = $field_array['error'];

        if($lang=='js'){
            $javascript = 'var '.$id.' = $("#'.$id.'"),'.$id.'_value = '.$id.'.val(),'.$id.'_error="'.$error.'";';
            if($type=='editor'){
                // $script = 'var '.$id.' = iCMS.editor.get("editor-body-'.$id.'"),'.$id.'_value = '.$id.'.hasContents()';
                $javascript = 'var '.$id.' = iCMS.editor.get("editor-body-'.$id.'"),'.$id.'_value = '.$id.'.getContent()';
            }
        }

        foreach ($field_array['validate'] as $key => $vd) {
            $code = null;
            switch ($vd) {
                case 'zipcode':
                    $msg = "邮政编码有误";
                    $pattern = "/^[1-9]{1}(\d+){5}$/";
                break;
                case 'idcard':
                    $msg = "身份证有误";
                    $pattern = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
                break;
                case 'telphone':
                    $msg = "固定电话号码有误";
                    $pattern = "/^(\(\d{3,4}\)|\d{3,4}-|\s)?\d{7,14}$/";
                break;
                case 'phone':
                    $msg = "手机号码有误";
                    $pattern = "/^1[34578]\d{9}$/";
                break;
                case 'url':
                    $msg = "网址有误";
                    $pattern = "/^[a-zA-z]+:\/\/[^\s]*/";
                break;
                case 'email':
                    $msg = "邮箱地址有误";
                    $pattern = "/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/";
                break;
                case 'number':
                    $msg = "只能输入数字";
                    $pattern = "/^\d+(\.\d+)?$/";
                break;
                case 'hanzi':
                    $msg = "只能输入汉字";
                    $pattern = "/^[\u4e00-\u9fa5]*$/";
                    if($lang=='php'){
                        $pattern = "/^[\x{4e00}-\x{9fa5}]*$/u";
                    }
                break;
                case 'character':
                    $msg = "只能输入字母";
                    $pattern = "/^[A-Za-z]+$/";
                break;
                case 'empty':
                    $msg = $label.'不能为空!';
                    if($lang=='php'){
                        empty($value) && iUI::alert($msg);
                    }else{
                        $code = '
                            if(!'.$id.'_value){
                                iCMS.UI.alert('.$id.'_error||"'.$msg.'");
                                '.$id.'.focus();
                                return false;
                            }
                        ';
                    }
                break;
                case 'minmax':
                    $min  = $field_array['minmax'][0];
                    $max  = $field_array['minmax'][1];
                    $msg_min = '您填写的'.$label.'小于'.$min.',请重新填写!';
                    $msg_max = '您填写的'.$label.'大于'.$max.',请重新填写!';
                    if($lang=='php'){
                        ($value<$min) && iUI::alert($msg_min);
                        ($value>$max) && iUI::alert($msg_max);
                    }else{
                        $code = '
                            var value = parseInt('.$id.'_value)||0;
                            // console.log(value);
                            if (value < '.$min.') {
                                iCMS.UI.alert('.$id.'_error||"'.$msg_min.'");
                                '.$id.'.focus();
                                return false;
                            }
                            if (value > '.$max.') {
                                iCMS.UI.alert('.$id.'_error||"'.$msg_max.'");
                                '.$id.'.focus();
                                return false;
                            }
                        ';
                    }

                break;
                case 'count':
                    $min  = $field_array['count'][0];
                    $max  = $field_array['count'][1];
                    $msg_min = '您填写的'.$label.'小于'.$min.'字符,请重新填写!';
                    $msg_max = '您填写的'.$label.'大于'.$max.'字符,请重新填写!';
                    if($lang=='php'){
                        (strlen($value)<$min) && iUI::alert($msg_min);
                        (strlen($value)>$max) && iUI::alert($msg_max);
                    }else{
                        $code = '
                            var value = '.$id.'_value.replace(/[^\x00-\xff]/g, \'xx\').length;
                            // console.log(value);
                            if (value < '.$min.') {
                                iCMS.UI.alert('.$id.'_error||"'.$msg_min.'");
                                '.$id.'.focus();
                                return false;
                            }
                            if (value > '.$max.') {
                                iCMS.UI.alert('.$id.'_error||"'.$msg_max.'");
                                '.$id.'.focus();
                                return false;
                            }
                        ';
                    }

                break;
                case 'defined':
                    $field_array['defined'] && $code = $field_array['defined'];
                break;
                default:
                    # code...
                    break;
            }
            if($lang=='php'){
                if($pattern && $msg){
                    preg_match($pattern, $value) OR iUI::alert($msg);
                }
            }else{
                if(empty($code)){
                    $code = self::js_test($id,$label,$msg,$pattern);
                }
                $javascript.= $code;
            }
        }
        self::$validate.= $javascript;
        self::$script.= self::script($field_array['javascript']);

        return $javascript;
    }
    public static function de_value($value,$fields,$vArray=null) {
        //字段数据类型
        $field = $fields['field'];

        //字段类型
        list($type,$type2) = explode(':', $fields['type']);

        //时间转换
        if(in_array($type, array('date','datetime'))){
          $value = get_date($value,'Y-m-d H:i:s');
        }
        if(in_array($type, array('category','multi_category'))){
            $variable = iFormer::$variable[$fields['name']];
            $valArray = explode(",", $value);
            $value = '';
            foreach ($valArray as $i => $val) {
                $array = $variable[$val];
                $value.= '<a href="'.APP_DOURI.'&cid='.$val.'&'.$uri.'">'.$array->name.'</a>';
            }
        }
        //多选字段转换
        if(isset($fields['multiple'])){
          $value = explode(',',$value);
        }

        return $value;
    }
    public static function en_value($value,$fields) {
        //字段数据类型
        $field = $fields['field'];

        //字段类型
        list($type,$type2) = explode(':', $fields['type']);

        //时间转换
        if(in_array($type, array('date','datetime'))){
          $value = str2time($value);
        }
        //多选字段转换
        if(isset($fields['multiple'])){
          is_array($value) && $value = implode(',',$value);
        }

        //数字转换
        if(in_array($field, array('BIGINT','INT','MEDIUMINT','SMALLINT','TINYINT'))){
          $value = (int)$value;
        }
        //编辑器不处理
        if($type=='editor'){
          // $post[$key] = $value;
        }else{
          $value = iSecurity::escapeStr($value);
        }
        return $value;
    }
    /**
     * 处理表单数据
     * @param  [type] $app [app数据]
     * @param  [type] $post[表单POST数组]
     * @return [array]     [description]
     */
    public static function post($app,$post=null) {
        if($post===null) $post = $_POST[iFormer::$prefix];

        if(empty($post)) return array(false,false,false);

        $orig_post   = array();
        $data_post = array();

        $field_array = iFormer::fields($app['fields']);
        $data_table  = next($app['table']);

        foreach ($post as $key => $value) {
            $fields = $field_array[$key];
            //字段绑定的函数处理
            $fields['func'] && $value = iFormer::func($fields['func'],$value);
            //字段数据处理
            $value = iFormer::en_value($value,$fields);
            //数据验证
            iFormer::validate($fields,'php',$value);

            $post[$key] = $value;
            //找查原始数据 并移除当前POST
            if(strpos($key,'_orig_')!==false){
              $orig_post[$key] = $value;
              unset($post[$key]);
            }
            //找查MEDIUMTEXT字段 并移除当前POST
            if($fields['field']=='MEDIUMTEXT'){
              $data_post[$key] = $value;
              unset($post[$key]);
            }
            if($data_table){
                if($data_table['primary']==$key||$data_table['union']==$key){
                  $data_post[$key] = $value;
                  unset($post[$key]);
                }
            }
        }
        if($data_table){
            $values = compact('post','data_post'); //将表单数据存入数组
        }else{
            $values = compact('post'); //将表单数据存入数组
        }
        $tables = array_keys($app['table']);//返回所有表名
        //创建一个数组，用一个表名数组的值作为其键名，表单数据的值作为其值
        $variable = array_combine($tables,$values);

        /**
         * array(表单数据,表名,_orig_字段数据用于比较);
         */
        return array($variable,$tables,$orig_post);
    }
    /**
     * 表单数据处理
     * @param  [type] $func  [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public static function func($func,$value) {
        return $value;
    }
}
