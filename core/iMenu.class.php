<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.0.0
* @$Id: iMenu.class.php 2334 2014-01-04 12:18:19Z coolmoo $
*/
class iMenu {
	public $menu_array = array();

	function __construct() {
		// $a=array('a'=>'{"width":"85%","height":"640px"}');
		// echo json_encode($a);
		// exit;

		$this->menu_array = $this->menu_array();
		// print_r($this->menu_array);
		// $this->show();
		// exit;
	}

    function menu_array($cache=false){

        $variable = array();
        foreach (glob(iPHP_APP_DIR."/*/config/menu.*.*") as $filename) {
            $json  = file_get_contents($filename);
            $array = json_decode($json,ture);
            $array && $variable[]= $this->menu_id($array);
            // $array && $variable[]= $array;

        }
        $variable = call_user_func_array('array_merge_recursive',$variable);
        array_walk($variable,array($this,'menu_item_unique'));
        $this->menu_item_order($variable);
        return $variable;
    }
    function menu_item_order(&$variable){
        uasort ($variable,array($this,'array_order'));
    	foreach ($variable as $key => $value) {
    		if($value['children']){
	    		usort($variable[$key]['children'],array($this,'array_order'));
	    		$this->menu_item_order($variable[$key]['children']);
    		}
    	}
    }
    function  array_order($a,$b){
        if ( $a['order']  ==  $b['order'] ) {
            return  0 ;
        }
        return ( $a['order']  <  $b['order'] ) ? - 1  :  1 ;
    }

    function  menu_item_unique (&$items ){
        if(is_array($items)){
            foreach ($items as $key => $value) {
                if(is_array($value)){
                    if(in_array($key, array('id','name','icon','caption','order'))){
                        $items[$key] = $value[0];
                    }
                    if($key=='children'){
                        array_walk ($items[$key],array($this,'menu_item_unique'));
                    }
                }

            }
        }
    }

    function menu_id($variable){
        if(empty($variable)) return;
        if(is_array($variable)){
            // $array = array();
            foreach ($variable as $key => $value) {

                if($value['children'] && is_array($value['children'])){
                    $value['children'] = $this->menu_id($value['children']);
                }
                empty($value['order']) && $value['order'] = $key;
                $variable[$key] = $value;
                if($value['id']){
                    $variable[$value['id']]= $value;
                    unset($variable[$key]);
                }
            }
            return $variable;
        }else{
            return $this->menu_id($variable);
        }

    }


	// function get_array($cache=false){
	// 	$rs	= iDB::all("SELECT * FROM `#iCMS@__menu` ORDER BY `ordernum` , `id` ASC");
	// 	$this->menu_array  = array();
	// 	$this->root_array  = array();
	// 	$this->parent      = array();
	// 	$this->menu_uri    = array();
	// 	$this->child_array = array();

	// 	foreach((array)$rs AS $M) {
	// 		$this->menu_array[$M['id']]               = $M;
	// 		$this->root_array[$M['rootid']][$M['id']] = $M;
	// 		$this->parent[$M['id']]                   = $M['rootid'];
	//         $M['app']!='separator' && $this->child_array[$M['rootid']][$M['id']] = $M['id'];
	// 		if(!$this->menu_uri[$M['app']][$M['href']]){
	// 			$this->menu_uri[$M['app']][$M['href']] = $M['id'];
	// 		}
	// 		$this->menu_uri[$M['app']]['#']        = $M['rootid'];
	// 	}

	// 	foreach ((array)$this->root_array as $rid => $array) {
	// 		uasort($array, "order_num");
	// 		$this->root_array[$rid] = $array;
	// 	}
	// 	if($cache){
	// 		$cache = iCache::sysCache();
	// 		$cache->add('iCMS/iMenu/menu_array',	$this->menu_array,0);
	//         $cache->add('iCMS/iMenu/root_array',	$this->root_array,0);
	//         $cache->add('iCMS/iMenu/child_array',	$this->child_array,0);
	//         $cache->add('iCMS/iMenu/parent',		$this->parent,0);
	//         $cache->add('iCMS/iMenu/menu_uri',		$this->menu_uri,0);
	//         // iCache::destroy();
	// 	}
	// }
	// function getAllCache(){
	// 	$cache = iCache::sysCache();
	// 	$this->menu_array  = $cache->get('iCMS/iMenu/menu_array');
	// 	$this->root_array  = $cache->get('iCMS/iMenu/root_array');
	// 	$this->child_array = $cache->get('iCMS/iMenu/child_array');
	// 	$this->parent      = $cache->get('iCMS/iMenu/parent');
	// 	$this->menu_uri    = $cache->get('iCMS/iMenu/menu_uri');
	// 	// iCache::destroy();
	// }
	//
	// function cache(){
	// 	$this->get_array(true);
	// }
	// function rootid($id){
	// 	$rootid = $this->parent[$id];
	// 	if(!$rootid){
	// 		return $id;
	// 	}
	// 	return $this->rootid($rootid);
	// }

	// function h1(){
	// 	if($this->rootid){
	// 		$a	= $this->menu_array[$this->rootid];
	// 		echo $a['name'];
	// 	}
	// }
	// function breadcrumb(){
	// 	$this->a($this->rootid);
	// 	if($this->parentid!=$this->rootid){
	// 		$this->a($this->parentid);
	// 	}
	// 	if($this->do_mid!=$this->parentid  && $this->do_mid!=$this->rootid){
	// 		$this->a($this->do_mid);
	// 	}
	// }
	function a($a){

		if(empty($a)) return;

		$a['href'] &&	$href	= __ADMINCP__.'='.$a['href'];
		if(strstr($a['href'], 'http://')||strstr($a['href'], '#')) $href = $a['href'];
		$a['href']=='__SELF__' && $href = __SELF__;
		$a['icon'] && $icon='<i class="'.$a['icon'].'"></i> ';
		$link = '<a href="'.$href.'"';
		$a['title']  && $link.= ' title="'.$a['title'].'"';
		$a['a_class']&& $link.= ' class="'.$a['a_class'].'"';
		$link.='>';
		echo $link.$icon.' '.$a['name'].'</a>';
	}
	function sidebar(){
		return $this->show('sidebar',0);
	}
	function show($mType='nav',$level = 0){
		// print_r($this->menu_array);

		foreach((array)$this->menu_array AS $id=>$array) {
			$nav.= $this->li($mType,$array,$level);
		}
		return $nav;
	}


	function li($mType,$a,$level = 0){
		// if(!admincp::MP($id)) return false;

		if($a['-']){
			return '<li class="'.(($level||$mType=='sidebar')?'divider':'divider-vertical').'"></li>';
		}


		$a['href'] && $href	= __ADMINCP__.'='.$a['href'];
		$a['target']=='iPHP_FRAME' && $href.='&frame=iPHP';

		if(strstr($a['href'], 'http://')||strstr($a['href'], '#')) $href = $a['href'];

		$a['href']=='__SELF__' && $href = __SELF__;
		$a['href'] OR $href = 'javascript:;';

		$children = count($a['children']);

		if($children && $mType=='nav'){
			$a['class']	= $level?'dropdown-submenu':'dropdown';
			$a['a_class'] = 'dropdown-toggle';
			$level==0 && $caret = true;
		}

		if($mType=='sidebar' && $children && $level==0){
			$href		= 'javascript:;';
			$a['class']	= 'submenu';
			$label		= '<span class="label">'.$children.'</span>';
		}

		if($mType=='tab'){
			$href = "#".$a['href'];
		}


		$li = '<li class="'.$a['class'].'" title="'.$a['title'].'" data-level="'.$level.'" data-menu="'.$a['id'].'">';

		$link = '<a href="'.$href.'"';
		$a['title']  && $link.= ' title="'.$a['title'].'"';
		$a['a_class']&& $link.= ' class="'.$a['a_class'].'"';
		$a['target'] && $link.= ' target="'.$a['target'].'"';

		if($a['data-toggle']=='modal'){
			$link.= ' data-toggle="modal"';
			$link.= ' data-target="#iCMS-MODAL"';
			$a['data-meta']  && $link.= " data-meta='".$a['data-meta']."'";

		}elseif($mType=='nav'){
			$children && $link.= ' data-toggle="dropdown"';
		}elseif($mType=='tab'){
			$link.= ' data-toggle="tab"';
		}
		$link.=">";
		$li.=$link;
		$a['icon'] && $li.='<i class="fa fa-'.$a['icon'].'"></i> ';
		$li.='<span>'.$a['caption'].'</span>'.$label;
		$caret && $li.='<b class="caret"></b>';
		$li.='</a>';
		if($children){
			// ksort ( $a['children'] );
			// usort ($a['children'],array($this,'menu_order'));
			// print_r($a['children']);
			// sort($a['children']);
			$SMli	= '';
			foreach((array)$a['children'] AS $id=>$ca) {
				$SMli.= $this->li($mType,$ca,$level+1);
			}
			$mType =='nav' && $SMul='<ul class="dropdown-menu">'.$SMli.'</ul>';
			if($mType=='sidebar'){
				$SMul = $level>1?$SMli:'<ul style="display: none;">'.$SMli.'</ul>';
			}
		}
		$li.=$SMul.'</li>';
		return $li;
	}

    function check_power($p){
    	return is_array($p)?array_intersect((string)$p,$this->power):in_array((string)$p,$this->power);
    }
}
