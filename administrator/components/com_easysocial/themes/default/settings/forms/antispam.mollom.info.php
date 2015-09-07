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
<p>
	<img src="<?php echo SOCIAL_MEDIA_URI;?>/images/admin/mollom.gif" width="150" align="left" style="padding: 0 10px 0 0;" />
	<?php echo $settings->renderSettingText( 'Mollom Introduction' ); ?>
	<br /><br />
	<a href="http://mollom.com" class="btn btn-mini btn-success"><?php echo $settings->renderSettingText( 'Mollom Get' ); ?></a>
	<a href="#" class="btn btn-mini btn-primary"><?php echo $settings->renderSettingText( 'Mollom Documentation' ); ?></a>
</p>
