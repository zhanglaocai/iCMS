<?php
/*
 * Template Lite plugin
 */
function tpl_output_clean(&$output,&$tpl){
    $output = preg_replace('/\n+/is', '', $output);
}
