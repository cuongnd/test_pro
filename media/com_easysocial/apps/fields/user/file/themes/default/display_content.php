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
<ul>
	<?php foreach( $value as $file ) { ?>
	<li><?php if( $params->get( 'allow_download' ) ) { ?><a href="<?php echo FRoute::fields( array( 'group' => $group, 'element' => $element, 'task' => 'download', 'id' => $field->id, 'uid' => $file->id ) ); ?>"><?php } ?><?php echo $file->name; ?><?php if( $params->get( 'allow_download' ) ) { ?></a><?php } ?></li>
	<?php } ?>
</ul>
