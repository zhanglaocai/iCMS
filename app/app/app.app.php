<?php

/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class appApp {
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
    public function do_iCMS($a = null) {
        return $this->app($this->id, isset($_GET['p']) ? (int) $_GET['p'] : 1);;
    }
    public function hooked($data){
        return iPHP::hook($this->app,$data,iCMS::$config['hooks'][$this->app]);
    }
    public function app($id, $page = 1, $tpl = true) {
        $rs = apps_mod::get_data($this->data,$id);
        $rs OR iPHP::error_404('找不到'.$this->data['name'].': <b>'.$this->primary.':' . $id . '</b>', 10001);
        if ($rs['url']) {
            if (iView::$gateway == "html") {
                return false;
            } else {
                $this->API_hits($id);
                iPHP::redirect($rs['url']);
            }
        }

        $rs = $this->value($rs,$page,$tpl);
        $rs = $this->hooked($rs);

        // $rs['param'] = array(
        //     "appid" => $this->appid,
        //     "iid"   => $rs[$this->primary],
        //     "cid"   => $rs['cid'],
        //     "suid"  => $rs['uid'],
        //     "title" => $rs['name'],
        //     "url"   => $rs['url']
        // );
        //
        //
var_dump($rs);
        if ($tpl) {
            $app_tpl = empty($rs['tpl']) ? $rs['category']['template'][$this->app] : $rs['tpl'];
            strstr($tpl, '.htm') && $app_tpl = $tpl;
            iView::assign('category', $rs['category']);unset($rs['category']);
            iView::assign($this->app, $rs);
            $html = iView::render($app_tpl, $this->app);
            if (iView::$gateway == "html") {
                return array($html, $rs);
            }
        } else {
            return $rs;
        }
    }
    public function value($rs, $page = 1, $tpl = false) {
        $rs['appid'] = $this->appid;
        $category = categoryApp::category($rs['cid'],false);

        if ($tpl) {
            $category OR iPHP::error_404('找不到该'.$this->data['name'].'的栏目缓存<b>cid:' . $rs['cid'] . '</b> 请更新栏目缓存或者确认栏目是否存在', 10002);
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

        $rs['iurl'] = iURL::get($this->app, array($rs, $category));
        $rs['url'] OR $rs['url'] = $rs['iurl']->href;
        $rs['link'] = '<a href="'.$rs['url'].'" class="'.$this->app.'">'.$rs['title'].'</a>';

        ($tpl && $category['mode'] == '1') && iCMS::redirect_html($rs['iurl']->path, $rs['iurl']->href);

        if($category['mode'] && stripos($rs['url'], '.php?')===false){
            iURL::page_url($rs['iurl']);
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
            $fields = iFormer::fields($this->data['fields']);
        }
        foreach ($fields as $key => $field) {
            $value  = $rs[$key];
            $values = array();
            $nkey   = null;
            switch ($field['type']) {
                case 'multi_image':
                    $imageArray = explode("\n", $value);
                    $pic = array();
                    foreach ($imageArray as $ik => $iv) {
                        $iv && $pic[]= filesApp::get_pic(trim($iv));
                    }
                    $nkey   = $key.'_array';
                    $values = $pic;
                break;
                case 'image':
                    $nkey   = $key.'_array';
                    $values = filesApp::get_pic($value);
                break;
                case 'file':
                    $nkey = $key.'_file';
                    $pi   = pathinfo($value);
                    $values   = array(
                        'name' => $pi['filename'],
                        'ext'  => $pi['extension'],
                        'dir'  => $pi['dirname'],
                        'url'  => filesApp::get_url($pi['filename'],'download')
                    );

                break;
                case 'category':
                    $category = iCache::get(categoryApp::CACHE_CATEGORY_ID.$value);
                    $values   = categoryApp::get_lite($category);
                break;
                case 'multi_category':
                    $nkey   = $key.'_category';
                    $cidsArray = explode(",", $value);
                    foreach ($cidsArray as $i => $_cid) {
                        $category   = iCache::get(categoryApp::CACHE_CATEGORY_ID.$_cid);
                        $values[$i] = categoryApp::get_lite($category);
                    }
                break;
                default:
                    // $values = $value;
                break;
            }
            if($field['option']){
                $optionArray = explode(";", $field['option']);
                foreach ($optionArray as $ok => $val) {
                    $val = trim($val,"\r\n");
                    if($val){
                        list($opt_text,$opt_value) = explode("=", $val);
                        $values[$opt_value] = $opt_text;
                    }
                }
                $nkey = $key.'_option';
            }
            $nkey && $rs[$nkey] = $values;

            var_dump($field,$value,$values);
            echo "<hr />";
           // $rs[$key] = iFormer::de_value($rs[$key],$field);
        }
        return $rs;
    }

}
