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
<div class="form-uploader filesForm fileUploader">
	<h5>
		<i class="icon-es-attachment"></i> <?php echo JText::_( 'COM_EASYSOCIAL_UPLOADER_TITLE' );?> 
		<?php if( isset( $size ) ){ ?>
		(<span class="small"><?php echo JText::_( 'COM_EASYSOCIAL_UPLOADER_MAX_SIZE' );?>: <?php echo $size;?><?php echo JText::_( 'COM_EASYSOCIAL_UNIT_MEGABYTES' );?></span>)
		<?php } ?>
	</h5>
	<hr />

	<div class="upload-queue" data-uploaderQueue></div>

	<div id="uploaderDragDrop">
		<div class="upload-submit" data-uploader-form>
			<button class="btn btn-es" href="javascript:void(0);" data-uploader-browse>
				<i class="icon-es-upload mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_UPLOADER_UPLOAD_FILES' ); ?>
			</button>

			<span class="inline">
				<?php echo JText::_( 'COM_EASYSOCIAL_UPLOADER_OR' ); ?>

				<span>
					<?php echo JText::_( 'COM_EASYSOCIAL_UPLOADER_DROP_YOUR_FILES' ); ?>
				</span>
			</span>
		</div>
	</div>

</div>
