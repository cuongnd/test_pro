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
<div class="row-fluid">
	<?php if( !$actor->isBlock() ) { ?>
	<a href="<?php echo $actor->getPermalink();?>" alt="<?php echo $this->html( 'string.escape' , $actor->getName() );?>"><?php echo $actor->getName(); ?></a>
	<?php } else { ?>
	<?php echo $actor->getName(); ?>
	<?php } ?>

	<?php if( $shareWith ) { ?>
		<i class="ies-arrow-right"></i>
		<?php if( !$shareWith->isBlock() ) { ?>
		<a href="<?php echo $shareWith->getPermalink();?>" alt="<?php echo $this->html( 'string.escape' , $shareWith->getName() );?>"><?php echo $shareWith->getName(); ?></a>
		<?php } else { ?>
		<?php echo $shareWith->getName(); ?>
		<?php } ?>
	<?php } ?>

</div>
