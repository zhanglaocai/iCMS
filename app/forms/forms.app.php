<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/

class formsApp {
    public $methods = array('iCMS','save');
    public function do_iCMS(){
        $fid = (int) $_GET['id'];
        $this->forms($fid);
    }
    public function API_iCMS(){
        $this->do_iCMS();
    }
    public function ACTION_save(){
        $fid   = (int) $_POST['fid'];
        $time  = iPHP::get_cookie('token_time');
        $token = $_POST['token'];
        list($_fid,$_time) = explode("#", authcode($token));

        if($_fid==$fid && $_time==$time){
            $active = true;
            $forms  = forms::get($fid);
            if(empty($forms)||empty($forms['status'])){
                $array = array('code'=>0,'msg'=>'找不到相关表单<b>ID:' . $fid . '</b>');
                $active = false;
            }
            if(empty($forms['config']['enable'])){
                $array = array('code'=>0,'msg'=>'该表单设置不允许用户提交!');
                $active = false;
            }
            if($active){
                $formsAdmincp = new formsAdmincp();
                $ret   = $formsAdmincp->do_savedata(false);
                iPHP::set_cookie('token_time','',-31536000);
                $array = array('code'=>1,'msg'=>$forms['config']['success']);
            }
        }else{
            $array = array('code'=>0,'msg'=>'提交出错!');
        }

        if(iPHP::is_ajax()){
            echo json_encode($array);
        }else{
            if ($array['code']){
                iUI::success($array['msg']);
            }else{
                iUI::alert($array['msg']);
            }
        }
    }

    public function forms($fid,$tpl = true){
        $forms = forms::get($fid);

        if(empty($forms)||empty($forms['status'])){
            iPHP::error_404('找不到相关表单<b>ID:' . $fid . '</b>', 10001);
        }
        // if(empty($forms['config']['enable'])){
        //     iPHP::error_404('该表单设置不允许用户提交', 10002);
        // }

        $forms['fieldArray']   = former::fields($forms['fields']);
        $forms['action']       = iURL::router(array('forms'));
        $forms['url']          = iURL::router(array('forms:id',$forms['id']));
        $forms['iurl']         = iDevice::urls(array('href'=>$forms['url']));
        $forms['iurl']['href'] = $forms['url'];
        $forms['result']       = iURL::router(array('forms:result',$forms['id']));
        $forms['link']         = '<a href="'.$forms['url'].'" class="forms" target="_blank">'.$forms['title'].'</a>';
        $forms['pic']          = filesApp::get_pic($forms['pic']);
        $forms['layout_id']    = "former_".$forms['id'];

        return apps_common::render($forms,'forms',$tpl);
    }
}
