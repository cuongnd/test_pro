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
<?php if( $external ){ ?>
<script type="text/javascript">
EasyBlog.require().script('legacy').done(function($)
{
	<?php if($blogSource == 'event') { ?>
	eblog.dashboard.changeCollab('jomsocial.event');
	<?php } ?>
});
</script>
<?php } ?>

<?php foreach( $events as $event ){ ?>
    <div class="blog-contributions clearfix mts">
		<input<?php echo $external == $event->id && $blogSource == 'event' ? ' checked="checked"' : '';?> id="event-<?php echo $event->id;?>" type="radio" name="blog_contribute" value="<?php echo $event->id;?>" class="input radio" onclick="eblog.dashboard.changeCollab('jomsocial.event');"/>
        <label for="event-<?php echo $event->id;?>"><img src="<?php echo $event->avatar;?>" width="16" height="16" class="avatar float-l mrm" /><?php echo $event->title;?></label>
    </div>
<?php } ?>
