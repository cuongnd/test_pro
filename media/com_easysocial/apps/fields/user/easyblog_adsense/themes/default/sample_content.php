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
<div>
	<ul class="input-vertical unstyled" <?php if( $params->get( 'format' , 1 ) != 1 ) { ?>style="display: none;"<?php } ?>>
		<li>
			<div class="small"><?php echo JText::_( 'PLG_FIELDS_EASYBLOG_ENABLE_ADSENSE' );?>:</div>
			<?php echo $this->html( 'grid.boolean' , 'easyblog-adsense-published' , true ); ?>

			<div class="small mt-10"><?php echo JText::_( 'PLG_FIELDS_EASYBLOG_ADSENSE_CODE' );?>:</div>

			<div><textarea class="full-width mb-10" id="easyblog-adsense" rows="5" name="easyblog-adsense" data-field-easyblog-adsense-input></textarea></div>
			<div class="small mt-10" style="clear:both"><?php echo JText::_( 'PLG_FIELDS_EASYBLOG_ADSENSE_SAMPLE' );?>:</div>

<pre>
google_ad_client= "pub-888888888888";
google_ad_slot = "8888888888";
google_ad_width = 468;
google_ad_height =60;
</pre>
			<div class="small mt-10"><?php echo JText::_( 'PLG_FIELDS_EASYBLOG_ADSENSE_APPEARENCE' );?>:</div>
			<select name="easyblog-adsense-display" class="mt-5 input-xlarge">
				<option value="both"><?php echo JText::_('COM_EASYBLOG_ADSENSE_HEADER_AND_FOOTER'); ?></option>
				<option value="header"><?php echo JText::_('COM_EASYBLOG_ADSENSE_HEADER'); ?></option>
				<option value="footer"><?php echo JText::_('COM_EASYBLOG_ADSENSE_FOOTER'); ?></option>
				<option value="beforecomments"><?php echo JText::_('COM_EASYBLOG_ADSENSE_BEFORE_COMMENTS'); ?></option>
				<option value="userspecified"><?php echo JText::_('COM_EASYBLOG_ADSENSE_USER_SPECIFIED'); ?></option>
			</select>
		</li>
	</ul>
</div>
