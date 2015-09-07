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

<div id="formStep_<?php echo $step->id; ?>" class="form-horizontal custom-fields tab-pane<?php if( $pageNumber == 1 ) { echo ' active'; } ?>" data-fields-editor-page data-fields-editor-page-<?php echo $step->id; ?> data-id="<?php echo $step->id; ?>">

	<div class="fields-editor-page-info-action widget" data-fields-editor-page-header>
		<div class="wbody wbody-padding">
			<h3>
				<span data-fields-editor-page-title><?php echo $step->get( 'title' ); ?></span>
				<a href="javascript:void(0);" class="pull-right btn btn-small btn-es-danger" data-fields-editor-page-delete><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_PAGE' ); ?></a>
				<a href="javascript:void(0);" class="pull-right btn btn-small btn-es-inverse mr-5" data-fields-editor-page-edit><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_EDIT_PAGE' ); ?></a>
			</h3>
			<hr />
			<span data-fields-editor-page-description><?php echo $step->get( 'description' ); ?></span>
		</div>
	</div>

	<!-- toggle .active -->

	<div class="widget">
		<div class="wbody wbody-padding">
		<fieldset data-fields-editor-page-items data-fields-editor-page-items-<?php echo $step->id; ?> class="fields-editor-page-items">
			<?php foreach( $step->fields as $field ) {
				echo $this->loadTemplate( 'admin/profiles/form.fields.editor.item', array( 'fieldid' => $field->id, 'appid' => $field->app_id, 'app' => $field->getApp(), 'output' => $field->output ) );
			} ?>
		</fieldset>
		</div>
	</div>
</div>
