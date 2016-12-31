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
defined('iPHP') OR exit('What are you doing?');
defined('iPHP_LIB') OR exit('iPHP vendor need define iPHP_LIB');
iPHP::import(iPHP_LIB.'/sphinx.class.php');

function SPHINX($hosts) {

	if(isset($GLOBALS['iSPH'])) return $GLOBALS['iSPH'];
	if(empty($hosts)){
		return false;
	}

	$GLOBALS['iSPH'] = new SphinxClient();
	if(strstr($hosts, 'unix:')){
		$hosts	= str_replace("unix://",'',$hosts);
		$GLOBALS['iSPH']->SetServer($hosts);
	}else{
		list($host,$port)=explode(':',$hosts);
		$GLOBALS['iSPH']->SetServer($host,(int)$port);
	}
	return $GLOBALS['iSPH'];
}
