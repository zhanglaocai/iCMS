<?php /**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
defined('iPHP') OR exit('What are you doing?');
?>
<script type="text/javascript">
$(function(){
    window.setTimeout(function(){
        $.getJSON('<?php echo __ADMINCP__;?>=patch&do=version&t=<?php echo time(); ?>',
            function(o){
            $('#iCMS_RELEASE').text(o.release);
            $('#iCMS_GIT').text(o.git);
            }
        );
    },1000);
});
</script>
