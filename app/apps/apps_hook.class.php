<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */

class apps_hook {
    /**
     * 获取带钩子APP
     * @param  [type] $app [description]
     * @return [type]      [description]
     */
    public static function app_select($app=null) {
        foreach (apps::get_array(array("table"=>true)) as $key => $value) {
            list($path,$obj_name)= apps::get_path($value['app'],'app',true);
            if(is_file($path) && method_exists($obj_name,'hooked')){
                $option[]='<option '.($app==$value['app']?' selected="selected"':'').' value="'.$value['app'].'">'.$value['app'].':'.$value['name'].'</option>';
            }
        }
        return implode('', (array)$option);
    }
    /**
     * 获取APP字段
     * @param  [type] $app [description]
     * @return [type]      [description]
     */
    public static function app_fields($app=null) {
        $rs = apps::get($app,'app');
        if($rs['table'])foreach ($rs['table'] as $key => $table) {
            $tbn = $table['table'];
            if(apps_db::check_table($tbn)){
                $option[] = '<optgroup label="'.$table['label'].'表">';
                $orig_fields  = apps_db::fields($tbn);
                foreach ((array)$orig_fields as $field => $value) {
                    $option[]='<option value="'.$field.'">'.($value['comment']?$value['comment'].' ('.$field.')':$field).'</option>';
                }
                $option[] = '</optgroup>';
            }

        }
        return implode('', (array)$option);
    }
    /**
     * 获取APP 插件等可用钩子
     * @return [type] [description]
     */
    public static function app_method() {
        $option = '';
        foreach (apps::get_array(array("status"=>'1')) as $key => $value) {
            // $option.=self::app_hook_method($value['app']);
            list($path,$obj_name)= apps::get_path($value['app'],'app',true);
            if(@is_file($path)){
                $option.= self::app_hook_method($obj_name);
            }
        }
        //plugins
        foreach (glob(iPHP_APP_DIR."/plugin/plugin_*.class.php") as $filename) {
            $parts = pathinfo($filename);
            $path = str_replace(iPHP_APP_DIR.'/','',$filename);
            $obj_name = substr($parts['filename'],0,-6);
            $option.= self::app_hook_method($obj_name);
        }
        return $option;
    }
    /**
     * 获取app钩子
     * @param  [type] $obj_name [description]
     * @return [type]           [description]
     */
    public static function app_hook_method($obj_name=null) {
        $class_methods = get_class_methods ($obj_name);
        foreach ($class_methods as $key => $method) {
            if(stripos($method, 'HOOK_') !== false||$method=="HOOK"){
                $doc = self::get_doc($obj_name,$method);
                if($doc){
                    $title = $doc[0];
                }else{
                    $title = $obj_name.'::'.$method;
                }
                $option[]='<option value="'.$obj_name.'::'.$method.'">'.$title.'</option>';
            }
        }
        return implode('', (array)$option);
    }
    /**
     * 获取注释
     * @param  [type] $class  [description]
     * @param  [type] $method [description]
     * @return [type]         [description]
     */
    public static function get_doc($class,$method) {
        $reflection = new ReflectionMethod($class,$method);
        $docblockr  = $reflection->getDocComment();
        preg_match_all ( '#^\s*\s(.+)\n#m', $docblockr, $lines );
        $doc = array();
        foreach ($lines[1] as $key => $line) {
            $doc[$key]= self::parseLine($line);
        }
        return $doc;
    }
    /**
     * 解析注释
     * @param  [type] $line [description]
     * @return [type]       [description]
     */
    private static function parseLine($line) {
        // trim the whitespace from the line
        $line = trim ( $line );

        if (empty ( $line ))
            return null; // Empty line

        if (strpos ( $line, '@' ) !== false) {
            preg_match ('#\*\s@(\w+)\s+\[(\w+)\]\s(.+)\s\[(.+)\]#is', $line, $match );
            $rs = array(
                'desc'=>$match[4],
                'type'=>$match[1],
                'var' => '('.$match[2].')'.$match[3]
            );
        }else{
            preg_match ('#^\*\s\[(.+)\]#is',$line,$match);
            $rs = $match[1];
        }
        if($rs){
            return $rs;
        }
    }
}
