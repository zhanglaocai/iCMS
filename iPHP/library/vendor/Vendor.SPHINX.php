<?php
/**
 * iPHP - i PHP Framework
 * Copyright (c) 2012 iiiphp.com. All rights reserved.
 *
 * @author coolmoo <iiiphp@qq.com>
 * @site http://www.iiiphp.com
 * @licence http://www.iiiphp.com/license
 * @version 1.0.1
 * @package common
 * @$Id: iPHP.php 2330 2014-01-03 05:19:07Z coolmoo $
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
