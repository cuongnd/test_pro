<?php
/**
 * @package		EasyBlog
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *  
 * EasyBlog is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Restricted access');
?>
<div id="section-comments">
<script type="text/javascript">
var idcomments_acct		= '<?php echo $code; ?>';
var idcomments_post_id	= "<?php echo $blog->id;?>";
var idcomments_post_url;
</script>
<span id="IDCommentsPostTitle" style="display:none"></span>
<script type="text/javascript" src="http://www.intensedebate.com/js/genericCommentWrapperV2.js"></script>
</div>