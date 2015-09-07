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
<div class="row-fluid" style="background: #f4f4f4;border: 1px solid #ccc;pading: 8px;">
	<div class="pull-left">
		<img src="<?php echo $profile->getAvatar();?>" />
	</div>
	<div>
		<?php echo $profile->description; ?>
	</div>

	<div style="text-align:right;">
		<a href="#" class="btn btn-es-success btn-small">Join Now</a>
	</div>
</div>