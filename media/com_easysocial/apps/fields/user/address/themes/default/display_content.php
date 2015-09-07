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
<?php if( !empty( $value->address1 ) ) { ?>
<div><?php echo $value->address1; ?></div>
<?php } ?>

<?php if( !empty( $value->address2 ) ) { ?>
<div><?php echo $value->address2; ?></div>
<?php } ?>

<?php if( !empty( $value->city ) ) { ?>
<div><?php echo $value->city; ?></div>
<?php } ?>

<?php if( !empty( $value->state ) ) { ?>
<div><?php echo $value->state; ?></div>
<?php } ?>

<?php if( !empty( $value->zip ) || !empty( $value->country ) ) { ?>
<div><?php if( !empty( $value->zip ) ) { echo $value->zip; } ?><?php if( !empty( $value->zip ) && !empty( $value->country ) ) { echo ' '; } ?><?php if( !empty( $value->country ) ) { echo $value->country; } ?>
<?php } ?>
