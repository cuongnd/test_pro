<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<ul class="nav">
	<?php foreach( $steps as $step ){ ?>
	<li class="installation-steps<?php echo $step->className;?>">
		<a data-toggle="tooltip"
			data-original-title="<?php echo JText::_( $step->title );?>"
			data-placement="bottom"
			href="javascript:void(0);"
		>
			<i class="ies-checkmark"></i>
			<span class="step-number"><?php echo $step->index;?></span>
		</a>
	</li>
	<li class="divider-vertical"></li>
	<?php } ?>

	<li class="last<?php echo $active == 'complete' ? ' active' : '';?>">
		<a href="javascript:void(0);"
			data-toggle="tooltip"
			data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_COMPLETE' );?>"
			data-placement="bottom"
		>
			<i class="ies-flag"></i>
			<span class="step-number"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_COMPLETE' );?></span>
		</a>
	</li>

</ul>


