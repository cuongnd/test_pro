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
	<?php echo $settings->renderSettingText( 'Akismet Introduction' ); ?>
	<br /><br />
	<a href="http://akismet.com" class="btn btn-mini btn-success"><?php echo $settings->renderSettingText( 'Akismet Get' ); ?></a>
	<a href="#" class="btn btn-mini btn-primary"><?php echo $settings->renderSettingText( 'Akismet Documentation' ); ?></a>
</p>
