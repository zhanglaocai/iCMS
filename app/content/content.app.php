<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/
class contentApp {
    public $methods = array('iCMS', 'hits','vote', 'good', 'bad', 'like_comment', 'comment');
    public $appid   = null;
    public $app     = null; //应用名
    public $table   = null;

    public function __construct($data) {
        $this->data    = $data;
        $this->appid   = $data['id'];
        $this->app     = $data['app'];
        $this->table   = apps::get_table($data);
        $this->primary = $this->table['primary'];
        $this->id      = (int)$_GET[$this->primary];
        unset($data);
    }
    public function API_iCMS(){
        return $this->do_iCMS();
    }
    public function API_search($a = null) {
        $app = iPHP::app("search");
        return $app->search("{iTPL}/{$this->app}.search.htm");
    }
    public function API_hits($id = null) {
        apps_common::api_hits('content',$id,$this->primary,$this->table['table']);
    }
    public function ACTION_vote() {
        apps_common::action_vote('content',$this->primary,$this->table['table']);
    }
    public function do_iCMS($a = null) {
        return $this->content($this->id, isset($_GET['p']) ? (int) $_GET['p'] : 1);;
    }
    public function hooked(&$data){
        iPHP::hook($this->app,$data,iCMS::$config['hooks'][$this->app]);
    }
    public function content($id, $page = 1, $tpl = true) {
        $rs = apps_mod::get_data($this->data,$id);
        $rs OR iPHP::error_404('找不到'.$this->data['title'].': <b>'.$this->primary.':' . $id . '</b>', 10001);
        if ($rs['url']) {
            if (iView::$gateway == "html") {
                return false;
            } else {
                $this->API_hits($id);
                iPHP::redirect($rs['url']);
            }
        }
        $vars = array(
            'tag'  => true,
            'user' => true,
        );
        $rs = $this->value($rs,$vars,$page,$tpl);
        if ($rs === false) {
            return false;
        }
        $rs+=(array)apps_meta::data($this->app,$id);
        $this->hooked($rs);

        if ($tpl) {
            $apps = apps::get_app_lite($this->data);
            //自定义应用模板信息
            $apps['type']=="2" && iPHP::callback(array("contentFunc","__set_apps"),array($apps));
            iView::assign('apps', $apps);
            $content = $rs;unset($content['category']);
            iView::assign('content', $content);unset($content);
        }

        return apps_common::render($rs,$this->app,$tpl);
    }

    public function value($rs, $vars = array(),$page = 1, $tpl = false) {
        $rs['appid'] = $this->appid;
        $category = categoryApp::category($rs['cid'],false);

        if ($tpl) {
            $category OR iPHP::error_404('找不到该'.$this->data['title'].'的栏目缓存<b>cid:' . $rs['cid'] . '</b> 请更新栏目缓存或者确认栏目是否存在', 10002);
        } else {
            if (empty($category)) {
                return false;
            }

        }

        if ($category['status'] == 0) {
            return false;
        }
        if(iCMS::check_view_html($tpl,$category,$this->app)){
            return false;
        }

        $rs['category'] = categoryApp::get_lite($category);

        $rs['iurl'] = (array)iURL::get($this->app, array($rs, $category));
        $rs['url'] OR $rs['url'] = $rs['iurl']['href'];

        ($tpl && $category['mode'] == '1') && iCMS::redirect_html($rs['iurl']);

        if($category['mode'] && stripos($rs['url'], '.php?')===false){
            iURL::page_url($rs['iurl']);
        }

        $vars['tag'] && tagApp::get_array($rs,$category['name'],'tags');

        apps_common::init($rs,$this->app,$vars,$this->primary);
        apps_common::link();
        apps_common::text2link();
        apps_common::user();
        apps_common::comment();
        apps_common::pic();
        apps_common::hits();
        apps_common::param();

        if($this->data['fields']){
            $fields = former::fields($this->data['fields']);
            foreach ((array)$fields as $key => $field) {
                formerApp::vars($field,$key,$rs,$vars,$category,$this->app);
            }
        }
        return $rs;
    }
    // public static function data($aids=0){
    //     if(empty($aids)) return array();

    //     list($aids,$is_multi)  = iSQL::multi_var($aids);
    //     $sql  = iSQL::in($aids,'aid',false,true);
    //     $data = array();
    //     $rs   = iDB::all("SELECT * FROM `#iCMS@__article_data` where {$sql}");
    //     if($rs){
    //         $_count = count($rs);
    //         for ($i=0; $i < $_count; $i++) {
    //             $data[$rs[$i]['aid']]= $rs[$i];
    //         }
    //         $is_multi OR $data = $data[$aids];
    //     }
    //     if(empty($data)){
    //         return;
    //     }
    //     return $data;
    // }
    /**
     * [iPHP::run回调]
     * @param  [type] $app [description]
     * @return [type]      [description]
     */
    public static function run($app){
        $data = apps::get_app($app);
        if($data){
            iPHP::$app_path = iPHP_APP_DIR . '/content';
            iPHP::$app_file = iPHP::$app_path . '/content.app.php';
            iPHP::$app      = new contentApp($data);
        }else{
            iPHP::error_404('Unable to find custom application <b>' . $app . '.app.php</b>', '0003');
        }
    }
}
