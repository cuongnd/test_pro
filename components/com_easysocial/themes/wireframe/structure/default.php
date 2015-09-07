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
<div id="es-wrap" class="es-wrapper es-main <?php echo $view . $task . $object . $layout; ?> es-responsive">
	<?php echo $this->render( 'module' , 'es-general-top' ); ?>
	<?php if( $show != 'iframe' ){ ?>
	<?php echo $toolbar; ?>
	<?php } ?>
	<?php echo $this->render( 'module' , 'es-general-after-toolbar' ); ?>

	<?php echo Foundry::info()->toHTML(); ?>

	<?php echo $this->render( 'module' , 'es-general-before-contents' ); ?>

	<?php echo $contents; ?>

	<?php echo Foundry::profiler()->toHTML();?>

	<?php echo $this->render( 'module' , 'es-general-bottom' ); ?>

	

	<div><?php echo $scripts; ?></div>


</div>
