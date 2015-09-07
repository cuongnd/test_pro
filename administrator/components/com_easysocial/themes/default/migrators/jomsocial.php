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
<form name="adminForm" id="adminForm" class="migratorsForm" method="post" enctype="multipart/form-data">
<div class="row-fluid">
	<div class="span6">
		<div class="widget-box" data-start-widget>
			<h3><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_JS_READ_FIRST_TITLE' );?></h3>

			<p><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_JOMSOCIAL_INSTRUCTION' ); ?></p>

			<ul>
				<li>
					<?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_JS_CUSTOM_PROFILES' );?>
				</li>
				<li>
					<?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_JS_CUSTOM_FIELDS' );?>
				</li>
				<li>
					<?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_JS_PHOTOS' );?>
				</li>
				<li>
					<?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_JS_PRIVACY' );?>
				</li>
				<li>
					<?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_JS_FRIENDS' );?>
				</li>
				<li>
					<?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_JS_CONVERSATIONS' );?>
				</li>
				<li>
					<?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_JS_USER_AVATARS' );?>
				</li>
			</ul>

			<p class="mt-20"><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_JS_ENSURE' ); ?></p>

			<ul class="mb-20">
				<li><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_BACKUP_EXISTING_DB' );?></li>
				<li><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_SET_OFFLINE' );?></li>
			</ul>

			<?php if( $installed ){ ?>
				<?php if( $version < 2.6 ) { ?>
					<div class="text-error">
						<strong><?php echo JText::sprintf( 'COM_EASYSOCIAL_MIGRATOR_JOMSOCIAL_VERSION_NOT_SUPPORTED', $version, $supportedVersion ); ?></strong>
					</div>
				<?php } else { ?>
					<a href="javascript:void(0);" class="btn btn-large btn-es-primary" data-initiate-migration><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_RUN_NOW' );?></a>
				<?php } ?>
			<?php } else { ?>
				<div class="text-error">
					<strong><i class="icon-es-delete mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_JS_NOT_FOUND' ); ?></strong>
				</div>
			<?php } ?>

			<div class="mt-20 small">
				<span class="label label-important small"><?php echo JText::_( 'COM_EASYSOCIAL_FOOTPRINT_NOTE' );?>:</span>

				<ol>
					<li>
						<?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_JOMSOCIAL_WARNING_TEXT' );?>
					</li>
					<li>
						<?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_JOMSOCIAL_FOOTNOTE' );?>
					</li>
				</ol>

			</div>
		</div>


		<?php if( $installed ){ ?>

		<div class="widget-box hide" data-custom-fields-map>
			<h3><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_JS_BIND_FIELDS' );?></h3>
			<p>
				<?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_JS_BIND_FIELDS_DESC' ); ?>
			</p>

			<table class="mt-20 table table-bordered table-hover table-striped">
				<thead>
				<tr>
					<td>
						JomSocial Custom Field
					</td>
					<td>
						EasySocial Field Type
					</td>
				</tr>
				</thead>

				<tbody>
					<?php foreach( $jsFields as $jsField ){ ?>
					<tr data-row-item>
						<td>
							<div>
								<strong><?php echo $jsField->name;?></strong>
							</div>
							<div>
								<span class="label label-info"><?php echo ucfirst( $jsField->type );?></span>
							</div>

						</td>
						<td>
							<select name="field_<?php echo $jsField->id;?>" data-field-item autocomplete="off">
								<option value=""><?php echo JText::_( '-- Select Custom Field --' ); ?></option>
								<?php

									$mappedId  	= '';
									$mapped 	= $fieldsMap[ $jsField->type ];

									if( $mapped && $mapped == 'dropdown' && strpos( strtolower( $jsField->fieldcode ) , 'gender' ) !== false )
									{
										$mapped = 'gender';
									}

									if( $mapped && $mapped == 'textbox' && ( strpos( strtolower( $jsField->fieldcode ) , 'givenname' ) !== false || strpos( strtolower( $jsField->fieldcode ) , 'familyname' ) !== false ) )
									{
										$mapped = 'joomla_fullname';
									}

									if( $mapped && $mapped == 'datetime' && ( strpos( strtolower( $jsField->fieldcode ) , 'birthday' ) !== false || strpos( strtolower( $jsField->fieldcode ) , 'birthdate' ) !== false ) )
									{
										$mapped = 'birthday';
									}

									$esMapped = isset( $fields[ $mapped ] ) ? $fields[ $mapped ] : '';
									if( $esMapped )
									{
										$mappedId = $esMapped->id;
									}

									foreach( $fields as $element => $field ){
								?>
								<option value="<?php echo $field->id;?>" <?php echo ( $mappedId && $mappedId == $field->id ) ? ' selected="selected"' : ''; ?> >
									<?php echo $field->get( 'title' );?> <?php echo JText::_( 'Field' );?>
								</option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<?php } ?>
				</tbody>

			</table>

			<div class="row-fluid">
				<a href="<?php echo JRoute::_( 'index.php?option=com_easysocial&view=migrators&layout=jomsocial' ); ?>" class="btn btn-large btn-es-danger pull-left"><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_CANCEL' );?></a>
				<a href="javascript:void(0);" class="btn btn-large btn-es-primary pull-right" data-start-migration><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_NEXT' );?></a>
			</div>
		</div>

		<?php } ?>

	</div>
</div>

<div class="row-fluid">
	<div class="span6">

		<div class="widget-box hide" data-migration-result>
			<h3><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_RESULT' );?></h3>


			<div class="es-progress-wrap">
				<div class="discoverProgress" stlye="display: none;">
					<div style="width: 0%;text-align:left;padding-left: 5px;" class="bar"></div>
					<div class="progress-result"></div>
				</div>
			</div>

			<a href="javascript:void(0);" class="viewLog btn btn-es-inverse btn-medium" style="display: none;">
				<?php echo JText::_( 'COM_EASYSOCIAL_VIEW_LOGS_BUTTON' );?>
			</a>

			<ul class="scannedResult unstyled">
				<li class="empty">
					<?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_JOMSOCIAL_NO_ITEM' ); ?>
				</li>
			</ul>
		</div>

		<a href="<?php echo JRoute::_( 'index.php?option=com_easysocial&view=migrators&layout=jomsocial' ); ?>" style="display: none;" data-jomsocial-back-button >
			<?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_BACK_TO_JOMSOCIAL_PAGE' );?>
		</a>
	</div>
</div>
<input type="hidden" name="option" value="com_easysocial" />
<input type="hidden" name="controller" value="migrators" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_( 'form.token' );?>

</form>
