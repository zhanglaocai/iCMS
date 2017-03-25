<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class formsApp {
    public $methods = array('iCMS','save');
    public function do_iCMS(){
        $formid = (int) $_GET['id'];
        $this->form($formid);
    }
    public function API_iCMS(){
        $this->do_iCMS();
    }
    public function ACTION_save(){
        $formid = (int) $_POST['form_id'];
        $time   = iPHP::get_cookie('token_time');
        $token  = $_POST['token'];
        list($_formid,$_time) = explode("#", authcode($token));
        if($_formid==$formid && $_time==$time){
            $formsAdmincp = new formsAdmincp();
            $ret  = $formsAdmincp->do_savedata(false);
            $form = $formsAdmincp->form;
            iPHP::set_cookie('token_time','',-31536000);
            iUI::success($form['config']['success']);
        }else{
            iUI::alert('提交出错!');
        }
    }
    public function form($formid,$tpl = true){
        $form = forms::get($formid);
        $form OR iPHP::error_404('找不到相关表单,<b>ID:' . $id . '</b>', 10001);

        $form['fieldArray'] = former::fields($form['fields']);
        $form['action'] = iURL::router(array('forms'));
        $form['url']    = iURL::router(array('forms:id',$form['id']));
        $form['result'] = iURL::router(array('forms:result',$form['id']));
        $form['link']   = '<a href="'.$form['url'].'" class="forms" target="_blank">'.$form['title'].'</a>';
        $form['pic']    = filesApp::get_pic($form['pic']);
        $form['time']   = time();
        $form['token']  = authcode($formid.'#'.$form['time'],'decode');
        iPHP::set_cookie('token_time', $form['time'], 600);

        if ($tpl) {
            $forms_tpl = $form['tpl'];
            strstr($tpl, '.htm') && $forms_tpl = $tpl;
            iView::assign('form', $form);
            $html = iView::render($forms_tpl, 'article');
            if (iView::$gateway == "html") {
                return array($html, $article);
            }
        } else {
            return $form;
        }
    }
}
