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

if( JRequest::getVar( 'external', '' ) != '' )
{
	$groupId	= $external;
}
?>
<?php if( $external ){ ?>
<script type="text/javascript">
EasyBlog.require().script('legacy').done(function($)
{
	<?php if($blogSource == 'group') { ?>
	eblog.dashboard.changeCollab('jomsocial');
	<?php } ?>
});
</script>
<?php } ?>
<?php foreach( $groups as $group ){ ?>
    <div class="blog-contributions clearfix mts">
		<input<?php echo $groupId == $group->id && $blogSource == 'group' ? ' checked="checked"' : '';?> id="group-<?php echo $group->id;?>" type="radio" name="blog_contribute" value="<?php echo $group->id;?>" class="input radio" onclick="eblog.dashboard.changeCollab('jomsocial');"/>
        <label for="group-<?php echo $group->id;?>"><img src="<?php echo $group->avatar;?>" width="16" height="16" class="avatar float-l mrm" /><?php echo $group->title;?></label>
    </div>
<?php } ?>
