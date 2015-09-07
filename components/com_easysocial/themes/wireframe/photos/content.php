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
<div class="es-photo-content scrollbar-wrap" data-photo-content>
	<div class="tabbable tabs-right">
		<ul class="es-nav nav-tabs">
			<li data-popup-close-button>
				<a href="#">
					<i class="ies-cancel-2"></i>
				</a>
			</li>
			<li class="active">
				<a data-foundry-toggle="tab" href="#photo-overview-<?php echo $photo->id; ?>">
					<i class="ies-pictures-2"></i>
				</a>
			</li>
			<li>
				<a data-foundry-toggle="tab" href="#photo-tags-<?php echo $photo->id; ?>">
					<i class="ies-tag"></i>
				</a>
			</li>
			<li>
				<a data-foundry-toggle="tab" href="#photo-comments-<?php echo $photo->id; ?>">
					<i class="ies-comments-4 "></i>
				</a>
			</li>
			<li>
				<a data-foundry-toggle="tab" href="#photo-actions-<?php echo $photo->id; ?>">
					<i class="ies-cog-3 "></i>
				</a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="photo-overview-<?php echo $photo->id; ?>" class="tab-pane active es-photo-overview">
				<?php echo $this->includeTemplate('site/photos/overview'); ?>
			</div>
			<div id="photo-tags-<?php echo $photo->id; ?>" class="tab-pane es-photo-tags">
				<?php echo $this->includeTemplate('site/photos/tags'); ?>
			</div>
			<div id="photo-comments-<?php echo $photo->id; ?>" class="tab-pane es-photo-comments">
				<?php echo $this->includeTemplate('site/photos/comments'); ?>
			</div>
			<div id="photo-actions-<?php echo $photo->id; ?>" class="tab-pane es-photo-actions">
				<?php echo $this->includeTemplate('site/photos/actions'); ?>
			</div>
		</div>
	</div>

</div>
