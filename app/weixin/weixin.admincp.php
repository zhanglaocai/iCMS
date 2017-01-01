<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
*/
defined('iPHP') OR exit('What are you doing?');

class weixinAdmincp{
    public function __construct() {
        $this->appid  = iCMS_APP_WEIXIN;
        $this->config = iCMS::$config[admincp::$APP_NAME];

        $this->config['component']==="1" && weixin::$component = true;
        weixin::$config = $this->config;
    }
    public function do_config(){
        weixin::init();
        // $a = weixin::mediaList('news');
        $a = weixin::qrcode_create('GwKA9BTkSixf_nsyg1jROoJtBv8ey59xP3bALbwYS7E');
        var_dump($a);

        exit;
        configAdmincp::app($this->appid);
    }
    public function do_save_config(){
        $_POST['config'] = array_merge((array)$this->config,(array)$_POST['config']);
        configAdmincp::save($this->appid);
    }
    public function do_save_menu(){
        $this->config['menu'] = $_POST['wx_button'];
        $this->do_save_config();
    }
    public function do_rsync_menu(){
        weixin::init();
        // $a = weixin::mediaList('image');
        // print_r($a);
        // exit;
        $response = weixin::setMenu();
        if(empty($response->errcode)){
            iUI::success('同步成功');
        }else{
            iUI::alert('同步出错 <br />errcode:"'.$response->errcode.'" errmsg:"'.$response->errmsg.'"');
        }
    }
    public function do_menu(){
        include admincp::view("weixin.menu");
    }
    public function do_component_login(){
        $token = iSecurity::escapeStr($_GET['token']);
        if($token!=$this->config['token']){
            iUI::alert("Token(令牌)出错！请先保存Token(令牌)配置！",'js:window.iCMS_MODAL.destroy();');
        }
        $url = iCMS_WEIXIN_COMPONENT.'/iCMS/login?'.
        'token='.$token.
        '&url='.urlencode(iCMS::$config['router']['public_url']);
        iPHP::redirect($url);
    }
    public function do_event(){
        $sql = " where ";
        switch($doType){ //status:[0:草稿][1:正常][2:回收]
            case 'inbox'://草稿
                $sql.="`status` ='0'";
            break;
            case 'trash'://回收站
                $sql.="`status` ='2'";
            break;
            default:
                $sql.=" `status` ='1'";
        }

        if($_GET['keywords']) {
            $sql.=" AND `keyword` REGEXP '{$_GET['keywords']}'";
        }

        $_GET['starttime']   && $sql.=" and `addtime`>=UNIX_TIMESTAMP('".$_GET['starttime']." 00:00:00')";
        $_GET['endtime']     && $sql.=" and `addtime`<=UNIX_TIMESTAMP('".$_GET['endtime']." 23:59:59')";

        $orderby    =$_GET['orderby']?$_GET['orderby']:"id DESC";
        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
        $total      = iCMS::page_total_cache("SELECT count(*) FROM `#iCMS@__weixin_event` {$sql}","G");
        iUI::pagenav($total,$maxperpage,"个事件");
        $rs     = iDB::all("SELECT * FROM `#iCMS@__weixin_event` {$sql} order by {$orderby} LIMIT ".iUI::$offset." , {$maxperpage}");
        // var_dump(iDB::$last_query);
        $_count = count($rs);
        include admincp::view("weixin.event");
    }
    public function do_event_add(){
        $id = (int)$_GET['id'];
        if($id) {
            $rs = iDB::row("SELECT * FROM `#iCMS@__weixin_event` WHERE `id`='$id' LIMIT 1;",ARRAY_A);
            if(strpos($rs['msg'],'a:') !== FALSE){
              $rs['msg'] = unserialize($rs['msg']);
            }
        }
        include admincp::view("weixin.event.add");
    }

    public function do_event_save(){
        $id       = (int)$_POST['id'];
        $pid      = $_POST['pid'];
        $eventype = $_POST['eventype'];
        $name     = $_POST['name'];
        $eventkey = $_POST['eventkey'];
        $operator = $_POST['operator'];
        $msgtype  = $_POST['msgtype'];
        $msg      = $_POST['msg'];
        $msg      = $_POST['status'];

        $eventype OR iUI::alert("请选择事件类型");
        $name OR iUI::alert("请填写事件名称");
        $eventkey OR iUI::alert("请填写事件KEY值");
        if($eventype=="keyword"){
            $operator OR iUI::alert("请选择关键词匹配模式");
        }
        $msgtype OR iUI::alert("请选择回复消息的类型");
        $msg OR iUI::alert("请填写回复内容");
        if($msgtype!='text'){
            $msg = json_encode($msg);
        }
        $fields = array('pid', 'name', 'eventype', 'eventkey', 'msgtype', 'operator', 'msg', 'addtime', 'status');
        $data   = compact ($fields);
        if(empty($id)) {
            iDB::value("SELECT `id` FROM `#iCMS@__weixin_event` where `eventkey` ='$eventkey'") && iUI::alert('该事件已经存在!');
            iDB::insert('weixin_event',$data);
            iUI::success('添加完成','url:'.APP_URI.'&do=event');
        }else{
            iDB::update('weixin_event', $data, array('id'=>$id));
            iUI::success('更新完成','url:'.APP_URI.'&do=event');
        }
    }
    public function menu_get_type($type,$out='value'){
      $type_map = array(
        'click'              =>'key',
        'view'               =>'url',
        'scancode_push'      =>'key',
        'scancode_waitmsg'   =>'key',
        'pic_sysphoto'       =>'key',
        'pic_photo_or_album' =>'key',
        'pic_weixin'         =>'key',
        'location_select'    =>'key',
        'media_id'           =>'media_id',
        'view_limited'       =>'media_id'
      );
      if($out=='value'){
        empty($type) && $type='click';
        return $type_map[$type];
      }
      if($out=='opt'){
        $option = '';
        foreach ($type_map as $key => $value) {
          $seltext = '';
          if($type==$key){
            $seltext =' selected="selected"';
          }
          $option.='<option value="'.$key.'"'.$seltext.'>'.$key.'</option>';
        }
        return $option;
      }
    }
    public function menu_button_li($key='~KEY~',$i='~i~',$a=array()){
      $keyname = $this->menu_get_type($a['type']);
      $html = '<li>'.
        '<div class="input-prepend input-append">'.
          '<span class="add-on">类型</span>'.
          '<select name="wx_button['.$key.'][sub_button]['.$i.'][type]">'.
            $this->menu_get_type($a['type'],'opt').
          '</select>'.
          '<span class="add-on">名称</span>'.
          '<input type="text" name="wx_button['.$key.'][sub_button]['.$i.'][name]" value="'.$a['name'].'">'.
          '<span class="button_key">'.
            '<span class="add-on">'.strtoupper($keyname).'</span>'.
            '<input type="text" name="wx_button['.$key.'][sub_button]['.$i.']['.$keyname.']" value="'.$a[$keyname].'">'.
          '</span>'.
          '<a href="javascript:void(0);" class="btn wx_del_sub_button"><i class="fa fa-del"></i>删除</a>'.
        '</div>'.
      '</li>';
      return $html;
    }
    public function do_save(){
        iUI::success('更新完成');
    }
    public function cache(){
    }
}
