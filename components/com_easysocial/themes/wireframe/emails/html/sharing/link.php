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
<tr>
	<td style="text-align: center;padding: 40px 10px 0;">
		<div style="margin-bottom:15px;">
			<div style="font-family:Arial;font-size:32px;font-weight:normal;color:#333;display:block; margin: 4px 0">
				<?php echo JText::_( 'COM_EASYSOCIAL_EMAILS_SHARING_HEADING' ); ?>
			</div>
			<div style="font-size:12px; color: #798796;font-weight:normal">
				<?php echo JText::_( 'COM_EASYSOCIAL_EMAILS_SHARING_SUBHEADING' ); ?>
			</div>
		</div>
	</td>
</tr>

<tr>
	<td style="text-align: center;font-size:12px;color:#888">

		<div style="margin:30px auto;text-align:center;display:block">
			<img src="<?php echo rtrim( JURI::root() , '/' );?>/components/com_easysocial/themes/wireframe/images/emails/divider.png" alt="<?php echo JText::_( 'divider' );?>" />
		</div>
		
		<table width="540" cellspacing="0" cellpadding="0" border="0" align="center">
			<tr>
				<td>
					<div style="font-size:13px;margin: 0 0 20px;text-align:left;display:block;color:#798796">
						<p>
							<?php echo JText::_( 'COM_EASYSOCIAL_EMAILS_HELLO' ); ?>,<br />
						</p>

						<p style="text-align:left;">
							<?php echo JText::sprintf( 'COM_EASYSOCIAL_EMAILS_SHARING_LINK_DESC' , $senderName );?>
						</p>

						<?php if( isset( $content ) ) { ?>
						<blockquote style="font: 14px/22px normal helvetica, sans-serif;margin-top: 10px;margin-bottom: 10px;margin-left: 0;padding-left: 15px;border-left: 3px solid #ccc;"><?php echo $content; ?></blockquote>
						<?php } ?>

						<p>
							<a href="<?php echo $url;?>"><?php echo $url;?></a>
						</p>

						<p style="font-size: 10px;margin-top:40px;">
							* <?php echo JText::sprintf( 'COM_EASYSOCIAL_EMAILS_SHARING_DISCLAIMER', $sender ); ?>
						</p>
					</div>
				</td>
			</tr>	
		</table>

	</td>
</tr>
