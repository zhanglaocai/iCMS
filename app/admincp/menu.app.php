<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.0.0
*/

class menuApp{
    public function __construct() {
    	// admincp::$menu->get_array();
    }
    public function do_add(){
    	$id	= $_GET['id'];
        if($id) {
            $rs		= iDB::row("SELECT * FROM `#iCMS@__menu` WHERE `id`='$id' LIMIT 1;",ARRAY_A);
            $rootid	= $rs['rootid'];
        }else{
        	$rootid	= $_GET['rootid'];
        }
        include admincp::view("menu.add");
    }
    public function do_addseparator(){
    	$rootid	= $_GET['rootid'];
    	$class	= $rootid?'divider':'divider-vertical';
    	iDB::query("INSERT INTO `#iCMS@__menu` (`rootid`,`app`,`class`) VALUES($rootid,'separator','$class');");
    	admincp::$menu->cache();
    	iPHP::success('添加完成');
    }
    public function do_updateorder(){
    	foreach((array)$_POST['sortnum'] as $sortnum=>$id){
            iDB::query("UPDATE `#iCMS@__menu` SET `sortnum` = '".intval($sortnum)."' WHERE `id` ='".intval($id)."' LIMIT 1");
    	}
		admincp::$menu->cache();
    }
    public function do_iCMS(){
    	admincp::$APP_METHOD="domanage";
    	$_GET['tab'] OR $_GET['tab']="tree";
    	$this->do_manage();
    }
    public function do_manage($doType=null) {
        include admincp::view("menu.manage");
    }
    public function power_tree($id=0){
        $li   = '';
        foreach((array)admincp::$menu->root_array[$id] AS $root=>$M) {
            $li.= '<li>';
            $li.= $this->power_holder($M);
            if(admincp::$menu->child_array[$M['id']]){
                $li.= '<ul>';
                $li.= $this->power_tree($M['id']);
                $li.= '</ul>';
            }
            $li.= '</li>';
        }
        return $li;
    }
    public function power_holder($M) {
        $name ='<span class="add-on">'.$M['caption'].'</span>';
        if($M['app']=='separator'){
            $name ='<span class="add-on tip" title="分隔符权限,仅为UI美观">───分隔符───</span>';
        }
        return '<div class="input-prepend input-append li2">
        <span class="add-on"><input type="checkbox" name="power[]" value="'.$M['id'].'"></span>
        '.$name.'
        </div>';
    }

    public function do_ajaxtree(){
		$expanded = $_GET['expanded']?true:false;
	 	echo $this->tree($expanded);
    }

    public function tree($expanded=false,$func='li'){
    	$id=='source' && $id=0;
        foreach((array)admincp::$menu->menu_array AS $root=>$M) {
        	$a			= array();
        	$a['id']	= $M['id'];
        	$a['text']	= $this->$func($M);
            if($M['children']){
            	if($expanded){
                    $a['hasChildren'] = false;
                    $a['expanded']    = true;
                    $a['children']    = $this->tree($expanded,$func);
            	}else{
                    $a['hasChildren'] = true;
            	}
            }
            $tr[]=$a;
        }
        if($expanded && $id!='source'){ return $tr; }
        return $tr?json_encode($tr):'[]';
    }
    public function li($M) {
    	if($M['-']){
    		return '<span class="operation"><a href="'.APP_FURI.'&do=del&id='.$M['id'].'" class="btn btn-danger btn-small" onClick="return confirm(\'确定要删除此菜单?\');" target="iPHP_FRAME"><i class="fa fa-trash-o"></i> 删除</a></span><div class="separator"><span class="sortnum" style="display:none;"><input type="text" data-id="'.$M['id'].'" name="sortnum['.$M['id'].']" value="'.$M['sortnum'].'"/></span> </div>';
    	}
        $M['rootid']==0 && $bold =' style="font-weight:bold"';
        $tr='<div class="row-fluid">
        <span class="sortnum" style="display:none;"><input type="text" data-id="'.$M['id'].'" name="sortnum['.$M['id'].']" value="'.$M['sortnum'].'" style="width:32px;"/></span>
        <span class="name"'.$bold.'>'.$M['caption'].'</span><span class="operation">';
        $tr.='
        <a href="'.APP_URI.'&do=copy&id='.$M['id'].'" title="复制本菜单设置"  class="btn btn-small" target="iPHP_FRAME"><i class="fa fa-copy"></i> 复制</a>
        <a href="'.APP_URI.'&do=add&rootid='.$M['id'].'" class="btn btn-info btn-small"><i class="fa fa-plus-square"></i> 子菜单</a>
        <a href="'.APP_FURI.'&do=addseparator&rootid='.$M['id'].'" class="btn btn-success btn-small" target="iPHP_FRAME"><i class="fa fa-minus-square"></i> 分隔符</a>
        <a href="'.APP_URI.'&do=add&id='.$M['id'].'" title="编辑菜单设置"  class="btn btn-primary btn-small"><i class="fa fa-edit"></i> 编辑</a>
        <a href="'.APP_FURI.'&do=del&id='.$M['id'].'" class="btn btn-danger btn-small" onClick="return confirm(\'确定要删除此菜单?\');" target="iPHP_FRAME"><i class="fa fa-trash-o"></i> 删除</a></span></div>';
        return $tr;
    }
    public function do_copy() {
        $id = $_GET['id'];
        $field = '`rootid`, `sortnum`, `app`, `name`, `title`, `href`, `icon`, `class`, `a_class`, `target`, `caret`, `data-toggle`, `data-meta`, `data-target`';
        iDB::query("insert into `#iCMS@__menu` ({$field}) select {$field} from `#iCMS@__menu` where id = '$id'");
        $nid = iDB::$insert_id;
        iPHP::success('复制完成,编辑此菜单', 'url:' . APP_URI . '&do=add&id=' . $nid);
    }
    public function do_save(){
        $id          = $_POST['id'];
        $rootid      = $_POST['rootid'];
        $app         = $_POST['app'];
        $name        = $_POST['name'];
        $title       = $_POST['title'];
        $href        = $_POST['href'];
        $a_class     = $_POST['a_class'];
        $icon        = $_POST['icon'];
        $target      = $_POST['target'];
        $data_toggle = $_POST['data-toggle'];
        $sortnum    = $_POST['sortnum'];
        $class       = '';
        $caret       = '';
        $data_meta   = $_POST['data-meta'];
        $data_target = '';

    	if($data_toggle=="dropdown"){
    		$class		= 'dropdown';
    		$a_class	= 'dropdown-toggle';
    		$caret		= '<b class="caret"></b>';
    	}else if($data_toggle=="modal"){
    		$data_meta	OR	$data_meta	= '{"width":"800px","height":"600px"}';
    		$data_target = '#iCMS-MODAL';
    	}
        $fields = array('rootid', 'sortnum', 'app', 'name', 'title', 'href', 'icon', 'class', 'a_class', 'target', 'caret', 'data-toggle', 'data-meta', 'data-target');
        $data   = compact ($fields);
        $data['data-toggle'] = $data_toggle;
        $data['data-meta']   = $data_meta;
        $data['data-target'] = $data_target;

		if($id){
            iDB::update('menu', $data, array('id'=>$id));
    		$msg = "编辑完成!";
    	}else{
            iDB::insert('menu',$data);
			$msg = "添加完成!";
    	}
		admincp::$menu->cache();
		iPHP::success($msg,'url:' . APP_URI . '&do=manage');
    }
    public function do_del(){
        $id		= (int)$_GET['id'];
        if(empty(admincp::$menu->root_array[$id])) {
            iDB::query("DELETE FROM `#iCMS@__menu` WHERE `id` = '$id'");
            admincp::$menu->cache();
            $msg	= '删除成功!';
        }else {
        	$msg	= '请先删除本菜单下的子菜单!';
        }
		iPHP::dialog($msg,'js:parent.$("#'.$id.'").remove();');
    }
    public function select($currentid="0",$id="0",$level = 1) {
        foreach((array)admincp::$menu->root_array[$id] AS $root=>$M) {
			$t=$level=='1'?"":"├ ";
			$selected=($currentid==$M['id'])?"selected":"";
			if($M['app']=='separator'){
				$M['caption']	= "─────────────";
				$M['id']	= "-1";
			}
			$text	= str_repeat("│　", $level-1).$t.$M['caption'];
			$option.="<option value='{$M['id']}' $selected>{$text}</option>";
			admincp::$menu->child_array[$M['id']] && $option.=$this->select($currentid,$M['id'],$level+1);
        }
        return $option;
    }
}
