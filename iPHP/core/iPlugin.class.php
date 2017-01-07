<?php
/**
 * iPHP - i PHP Framework
 * Copyright (c) 2012 iiiphp.com. All rights reserved.
 *
 * @author coolmoo <iiiphp@qq.com>
 * @website http://www.iiiphp.com
 * @license http://www.iiiphp.com/license
 * @version 2.0.0
 */
class iPlugin {
    public static function hook($app,$resource=null,$plugin){
        // if($plugin){
        //  foreach ($plugin as $_app => $callback) {
        //      if($_app==$app){
        //          foreach ($callback as $field => $call) {
        //              if(is_array($call[0])){
        //                  foreach ($call as $key => $cb) {
        //                      $resource[$field] = self::call_func($cb,$resource[$field]);
        //                  }
        //              }else{
        //                  $resource[$field] = self::call_func($call,$resource[$field]);
        //              }
        //          }
        //      }
        //  }
        // }else{
        //  return false;
        // }
        if($plugin){
            foreach ($plugin as $field => $call) {
                // if(is_array($call[0])){
                    foreach ($call as $key => $cb) {
                        $resource[$field] = self::call_func($cb,$resource[$field]);
                    }
                // }else{
                //     $resource[$field] = self::call_func($call,$resource[$field]);
                // }
            }
        }
        return $resource;

    }
    public static function call_func($callback,$value){
        if (is_array($callback) && @class_exists($callback[0]) && method_exists($callback[0], $callback[1])) {
            return call_user_func_array($callback, (array)$value);
        }else{
            return $value;
        }
    }
}
