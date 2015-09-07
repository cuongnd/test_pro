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
<div class="mt-10" style="padding:8px">
	<h5><?php echo $registry->get( 'title' ); ?></h5>
	
	<p>
		<img src="<?php echo $registry->get( 'image' );?>" align="left" style="padding: 0 10px 10px 0;"/>
		<?php echo $registry->get( 'content' ); ?>
	</p>
</div>
