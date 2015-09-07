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
<div class="row-fluid mb-10 discuss-stream-content">
	<div class="span12">
		<div class="post-details">
			<h3>
				<a href="<?php echo $permalink;?>"><?php echo $post->title;?></a>
			</h3>
			<?php echo $this->html( 'string.truncater' , $post->content , 300 ); ?>
			<br /><br />

			<a href="<?php echo $permalink;?>" class="btn btn-es-primary"><?php echo JText::_( 'APP_DISCUSS_STREAM_VIEW_POST' ); ?> &rarr;</a>
		</div>
	</div>
</div>
