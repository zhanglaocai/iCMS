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
    public $app     = null;
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
    public function API_hits($id = null) {
        $id === null && $id = $this->id;
        if ($id) {
            $sql = iSQL::update_hits();
            iDB::query("UPDATE `".$this->table['table']."` SET {$sql} WHERE `".$this->primary."` ='$id'");
        }
    }
    public function ACTION_vote() {
        $type = $_POST['type'];
        $this->__vote($type);
    }
    private function __vote($type) {
        // user::get_cookie() OR iUI::code(0,'iCMS:!login',0,'json');

        $id = (int) $_POST['iid'];
        $id OR iUI::code(0, 'iCMS:content:empty_id', 0, 'json');

        $ackey = $this->app.'_' . $type . '_' . $id;
        $vote = iPHP::get_cookie($ackey);
        $vote && iUI::code(0, 'iCMS:content:!' . $type, 0, 'json');

        if ($type == 'good') {
            $sql = '`good`=good+1';
        } else {
            $sql = '`bad`=bad+1';
        }
        iDB::query("UPDATE `".$this->table['table']."` SET {$sql} WHERE `".$this->primary."` ='{$id}' limit 1");
        iPHP::set_cookie($ackey, time(), 86400);
        iUI::code(1, 'iCMS:content:' . $type, 0, 'json');
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
        $rs+=(array)apps_meta::data($this->app,$id);
        $this->hooked($rs);

        if ($tpl) {
            iView::set_iVARS($rs['iurl'],'iURL');
            $app_tpl = empty($rs['tpl']) ? $rs['category']['template'][$this->app] : $rs['tpl'];
            strstr($tpl, '.htm') && $article_tpl = $tpl;
            iView::assign('category', $rs['category']);unset($rs['category']);
            iView::assign('app', apps::get_app_lite($this->data));
            iView::assign($this->app, $rs);
            iView::assign('content', $rs);
            $view = iView::render($app_tpl, $this->app);
            if($view) return array($view,$rs);
        } else {
            return $rs;
        }
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

        if (iView::$gateway == "html" && $tpl && (strstr($category['rule'][$this->app], '{PHP}') || $category['outurl'] || $category['mode'] == "0")) {
            return false;
        }

        $rs['category'] = categoryApp::get_lite($category);

        $rs['iurl'] = (array)iURL::get($this->app, array($rs, $category));
        $rs['url'] OR $rs['url'] = $rs['iurl']['href'];
        $rs['link'] = '<a href="'.$rs['url'].'" class="'.$this->app.'">'.$rs['title'].'</a>';

        ($tpl && $category['mode'] == '1') && iCMS::redirect_html($rs['iurl']['path'], $rs['iurl']['href']);

        if($category['mode'] && stripos($rs['url'], '.php?')===false){
            iURL::page_url($rs['iurl']);
        }
        if($vars['user']){
            if ($rs['postype']) {
                $rs['user'] = user::empty_info($rs['userid'], '#' . $rs['editor']);
            } else {
                $rs['user'] = user::info($rs['userid'], $rs['editor']);
            }
        }

        $rs['hits'] = array(
            'script' => iCMS_API . '?app='.$this->app.'&do=hits&cid=' . $rs['cid'] . '&id=' . $rs[$this->primary],
            'count'  => $rs['hits'],
            'today'  => $rs['hits_today'],
            'yday'   => $rs['hits_yday'],
            'week'   => $rs['hits_week'],
            'month'  => $rs['hits_month'],
        );
        $rs['comment'] = array(
            'url'   => iCMS_API . '?app='.$this->app.'&do=comment&appid='.$rs['appid'].'&iid='.$rs[$this->primary].'&cid='.$rs['cid'].'',
            'count' => $rs['comments'],
        );
        $rs['param'] = array(
            "appid" => $rs['appid'],
            "iid"   => $rs['id'],
            "cid"   => $rs['cid'],
            "suid"  => $rs['userid'],
            "title" => $rs['title'],
            "url"   => $rs['url'],
        );

        $fields = array();
        if($this->data['fields']){
            $fields = former::fields($this->data['fields']);
        }
        $option_array = array();
        foreach ($fields as $key => $field) {
            formerApp::vars($field,$key,$rs,$vars,$category,$this->app);
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
