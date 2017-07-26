<?php
/*
 * Template Lite plugin
 */
function tpl_modifier_uri($url,$uri){
    return buildurl($uri,$url);
}
