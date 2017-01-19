<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */

class apps_hook {
    public static function app_select($app=null) {
        foreach (apps::get_array(array("table"=>true)) as $key => $value) {
            list($path,$obj_name)= apps::path($value['app'],'app',true);
            if(is_file($path) && method_exists($obj_name,'hooked')){
                $option[]='<option '.($app==$value['app']?' selected="selected"':'').' value="'.$value['app'].'">'.$value['app'].':'.$value['name'].'</option>';
            }
        }
        return implode('', (array)$option);
    }
    public static function app_fields($app=null) {
        $rs = apps::get($app,'app');
        if($rs['table'])foreach ($rs['table'] as $key => $table) {
            $tbn = iPHP_DB_PREFIX.$table[0];
            if(apps_db::check_table($tbn)){
                $option[] = '<optgroup label="'.$table[0].'表">';
                $orig_fields  = apps_db::fields($tbn);
                foreach ((array)$orig_fields as $field => $value) {
                    $option[]='<option value="'.$field.'">'.($value['comment']?$value['comment'].' ('.$field.')':$field).'</option>';
                }
                $option[] = '</optgroup>';
            }

        }
        return implode('', (array)$option);
    }
    public static function app_method() {
        $option = '';
        foreach (apps::get_array(array("status"=>'1')) as $key => $value) {
            $option.=self::app_hook_method($value['app']);
        }
        return $option;
    }
    public static function app_hook_method($app=null) {
        list($path,$obj_name)= apps::path($app,'app',true);

        if(!is_file($path)){
            return false;
        }
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
