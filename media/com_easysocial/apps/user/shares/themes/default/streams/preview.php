<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<?php if( $sharetext ) { ?>
<div class="stream-repost-text"><?php echo $sharetext; ?></div>
<?php } ?>
<div class="stream-media-preview-body pl-10 mt-10 mb-20 stream-shared-border">
	<div class="row-fluid stream-meta">
		<div class="stream-title"><?php echo $title; ?></div>
		<div class="stream-content"><?php echo $content; ?></div>
		<?php if( $preview ) { ?>
			<?php echo $preview; ?>
		<?php } ?>
	</div>
</div>
