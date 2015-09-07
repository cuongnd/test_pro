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
<span class="file-name"><?php if( $params->get( 'allow_download' ) && empty( $tmp ) ) { ?><a href="<?php echo FRoute::fields( array( 'group' => $group, 'element' => $element, 'task' => 'download', 'id' => $field->id, 'uid' => $file->id ) ); ?>"><?php } ?><?php echo $file->name; ?><?php if( $params->get( 'allow_download' ) && empty( $tmp ) ) { ?></a><?php } ?><button class="close" type="button" data-field-file-delete>×</button></span>

<input type="hidden" name="<?php echo $inputName; ?>[<?php echo $key; ?>][tmp]" value="<?php echo empty( $tmp ) ? 0 : 1; ?>" data-field-file-tmp />
<input type="hidden" name="<?php echo $inputName; ?>[<?php echo $key; ?>][id]" value="<?php echo $file->id; ?>" data-field-file-id />
