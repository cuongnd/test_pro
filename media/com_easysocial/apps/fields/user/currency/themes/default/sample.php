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
<div class="control-group">
	<?php echo $this->includeTemplate( 'admin/fields/sample.title' ); ?>

	<div class="controls">
		<span class="unitLabel" style="font-weight:700;"><?php echo $unitsLabel;?></span>
		<span>
			<input type="text" class="input-mini" />
			<span class="dollarsLabel"><?php echo $dollarsLabel;?></span>
		</span>
		.
		<span>
			<input type="text" class="input-mini" />
			<span class="centsLabel"><?php echo $centsLabel;?></span>
		</span>
	</div>

	<?php echo $this->includeTemplate( 'admin/fields/sample.description' ); ?>
</div>
