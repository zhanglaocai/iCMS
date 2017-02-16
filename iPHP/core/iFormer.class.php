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

    public static $prefix  = 'iDATA';
    public static $html    = null;
    public static $gateway = null;
    public static $config  = array();
    public static $default = array();

    public static function widget($name,$attr=null) {
        $widget = new iQuery($name);
        $attr && $widget->attr($attr);
        return $widget;
    }

    public static function render() {

    }
    public static function html($field,$value=null) {
        if($field['type']=='br'){
            self::$line = true;
            return self::widget("div")->addClass("clearfloat mt10");
        }else{
            $id      = $field['id'];
            $name    = $field['name'];
            $class   = $field['class'];
            $default = $field['default'];

            list($type,$type2) = explode(':', $field['type']);
            empty($value) && $value = $default;


            if(empty($value)){
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
                    $input  = self::widget('textarea',$attr);
                    $picbtn = filesAdmincp::pic_btn($attr['id'],null,true);
                    $html   = $input.$picbtn;
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
                case 'prop':
                    $div_class.=' input-append';
                    $input->attr('type','text');
                    $prop = propAdmincp::btn_group($name,self::$config['app'],$attr['id']);
                    $html = $input.$prop;
                break;
                case 'multi_prop':
                    unset($attr['type']);
                    $attr['name']     = $attr['name'].'[]';
                    $attr['class']   .= ' chosen-select';
                    $attr['multiple'] = 'true';
                    $select = self::widget('select',$attr);
                    $option='<option value="0">普通'.self::$config['name'].'[pid=\'0\']</option>';
                    $option.= propAdmincp::get($name,null,'option',null,self::$config['app']);
                    $_input = self::widget('input',array('type'=>'hidden','name'=>self::$prefix.'[_orig_'.$name.']','value'=>$value));
                    $html = $select->html($option).$_input;
                    $script = self::script('iCMS.select("'.$attr['id'].'","'.($value?trim($value):0).'");',true);
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
                    if(self::$gateway=='admincp'){
                        $div_class.=' hide';
                        $html = $input->attr('type','hidden');
                    }
                break;
                case 'PRIMARY':
                case 'hidden':
                    $div_class.=' hide';
                    $html = $input->attr('type','hidden');
                break;
                case 'userid':
                    $div_class.=' hide';
                    $value OR $value = self::$default['userid'];
                    $input->attr('type','text');
                    $html = $input->val($value);
                break;
                case 'username':
                    $value OR $value = self::$default['username'];
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
                    if(self::$gateway=='admincp'){
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
                        $attr['name'] = $attr['name'].'[]';
                    }
                    if($field['option']){
                        $div_class  .=' input-append';
                        $optionText  = str_replace(array("\r","\n"), '', $field['option']);
                        $optionArray = explode(";", $optionText);
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
                    if(strpos($attr['class'],'chosen-select')===false){
                        $attr['class'].= ' chosen-select';
                    }
                    $select = self::widget('select',$attr);
                    $categoryAdmincp = new categoryAdmincp(self::$config['id']);
                    $option = $categoryAdmincp->select('ca',$value);
                    $html = $select->html($option).$orig;
                break;
                case 'multiple':
                case 'select':
                    $attr['class'] = $field['class']?$field['class']:'chosen-select';
                    if(strpos($type,'multi')!==false){
                        unset($attr['type']);
                        $attr['multiple'] = 'true';
                        $attr['name']     = $attr['name'].'[]';
                        $_input = self::widget('input',array('type'=>'hidden','name'=>self::$prefix.'[_orig_'.$name.']','value'=>$value));
                    }
                    $html = self::widget('select',$attr);
                    if($field['option']){
                        $optionText = str_replace(array("\r","\n"), '', $field['option']);
                        $optionArray = explode(";", $optionText);
                        foreach ($optionArray as $ok => $val) {
                            $val = trim($val,"\r\n");
                            if($val){
                                list($opt_text,$opt_value) = explode("=", $val);
                                $option.='<option value="'.$opt_value.'">'.$opt_text.' ['.$name.'="'.$opt_value.'"]</option>';
                            }
                        }
                        $html = $html->html($option);
                        $script= self::script('iCMS.select("'.$attr['id'].'","'.($value?trim($value):0).'");',true);
                    }
                    $html.= $_input;
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
    public static function test_js($id,$label,$msg,$pattern) {
        $script = '
            var '.$id.'_msg = "'.$msg.'";
            var pattern    = "'.$pattern.'";
            if(!pattern.test('.$id.'_value)){
                iCMS.UI.alert('.$id.'_error||"['.$label.'],"+'.$id.'_msg+",请重新填写!");
                '.$id.'.focus();
                return false;
            }
        ';
        return $script;
    }
    public static function validate($field_array,$lang='js',$value='') {
        if(empty($field_array['validate'])) return;

        $id    = self::$prefix.'_'.$field_array['id'].'';
        $name  = self::$prefix.'['.$field_array['name'].']';
        $label = $field_array['label'];
        $type  = $field_array['type'];
        $error = $field_array['error'];

        foreach ($field_array['validate'] as $key => $vd) {
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
                default:
                    # code...
                    break;
            }
            if($lang=='php'){
                if($pattern && $msg){
                    preg_match($pattern, $value) OR iUI::alert($msg);
                }
            }else{
                $script = 'var '.$id.' = $("#'.$id.'"),'.$id.'_value = '.$id.'.val(),'.$id.'_error="'.$error.'";';
                if($type=='editor'){
                    // $script = 'var '.$id.' = iCMS.editor.get("editor-body-'.$id.'"),'.$id.'_value = '.$id.'.hasContents()';
                    $script = 'var '.$id.' = iCMS.editor.get("editor-body-'.$id.'"),'.$id.'_value = '.$id.'.getContent()';
                }
                if(empty($code)){
                    $code = self::test_js($id,$label,$msg,$pattern);
                }
                $script.= $code;
            }
        }

        return $script;
    }
    /**
     * 处理表单数据
     * @param  [type] $post        [表单POST数组]
     * @param  [type] $field_array [字段数组]
     * @param  [type] $table_array [字段表]
     * @return [array]              [description]
     */
    public static function post($post,$field_array,$table_array) {
        if(empty($post)) return array(false,false,false);

        $orig_post   = array();
        $addons_post = array();

        foreach ($post as $key => $value) {
            //字段数据类型
            $field = $field_array[$key]['field'];
            //字段类型
            list($type,$type2) = explode(':', $field_array[$key]['type']);
            //字段数据处理
            if($func  = $field_array[$key]['func']){
                $value = iFormer::func($func,$value);
            }
            //时间转换
            if(in_array($type, array('date','datetime'))){
              $value = str2time($value);
            }
            //多选字段转换
            if(isset($field_array[$key]['multiple'])||in_array($type, array('checkbox'))){
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
            iFormer::validate($field_array[$key],'php',$value);

            $post[$key] = $value;
            //找查原始数据 并移除当前POST
            if(strpos($key,'_orig_')!==false){
              $orig_post[$key] = $value;
              unset($post[$key]);
            }
            //找查MEDIUMTEXT字段 并移除当前POST
            if($field=='MEDIUMTEXT'){
              $addons_post[$key] = $value;
              unset($post[$key]);
            }
        }
        $keys = array_keys($table_array);//返回所有表名
        $values   = compact('post','addons_post'); //将表单数据存入数组
        //创建一个数组，用一个表名数组的值作为其键名，表单数据的值作为其值

        $variable = array_combine($keys,$values);
        print_r($variable);
        /**
         * array(表单数据,表名,_orig_字段数据用于比较);
         */
        return array($variable,$keys,$orig_post);
    }
    /**
     * 表单数据处理
     * @param  [type] $func  [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public static function func($func,$value) {

    }
}
