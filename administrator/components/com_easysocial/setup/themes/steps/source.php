<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Let's try to detect if there are any files in the /packages/ folder.
jimport( 'joomla.filesystem.folder' );
$packages 	= JFolder::files( ES_PACKAGES , '.' , false , false , array('.svn', 'CVS', '.DS_Store', '__MACOSX' ,'index.html') );

$db 		= JFactory::getDBO();
$tables		= $db->getTableList();
$jConfig 	= JFactory::getConfig();
$table		= $jConfig->get( 'dbprefix' ) . 'social_config';
$key 		= '';
if( in_array( $table , $tables ) )
{
	$query 	= 'SELECT ' . $db->quoteName( 'value' ) . ' FROM ' . $db->quoteName( '#__social_config' );
	$query	.= ' WHERE ' . $db->quoteName( 'type' ) . '=' . $db->Quote( 'site' );

	$db->setQuery( $query );
	$raw 	= $db->loadResult();

	$registry	= new JRegistry( $raw );
	$key 		= $registry->get( 'general.key' , '' );
}

?>
<script type="text/javascript">
$( document ).ready( function(){

	$( '[data-source-type]' ).bind( 'change' , function()
	{
		var type 	= $( this ).val();

		// Show API key form.
		$( '[data-source-' + type + ']' ).show();

		$( '[data-source-method]' ).removeClass( 'active' );
		$( this ).parents( '[data-source-method]' ).addClass( 'active' );

		if( type == 'network' )
		{
			$( '[data-source-directory]' ).hide();
		}
		else
		{
			$( '[data-source-network]' ).hide();
		}
	});

	$( '[data-installation-submit]' ).bind( 'click' , function(){

		var selected 	= $( 'input[name=method]:checked' ).val();

		if( selected == 'network' )
		{
			// Show loading
			$( '[data-installation-submit]' ).hide();
			$( '[data-installation-loading]' ).show();

			// Validate api key
			$.ajax({
				type 	: 'POST',
				url 	: '<?php echo JURI::root();?>administrator/index.php?option=com_easysocial&ajax=1&controller=source&task=validate<?php echo $update ? '&update=1' : '';?><?php echo $reinstall ? '&reinstall=1' : '';?>',
				data 	:
				{
					key 	: $( '[data-api-key]' ).val()
				}
			})
			.done( function( result )
			{
				if( result.state == 400 )
				{
					$( '[data-installation-submit]' ).show();
					$( '[data-installation-loading]' ).hide();
					$( '[data-api-errors-message]' ).html( result.message );
					$( '[data-api-errors]' ).show();

					return false;
				}

				if( result.state == 201 )
				{
					// Hide error messages if there are shown
					$('[data-api-errors]' ).hide();

					// Display multiple key result
					$( '[data-api-multiple]' ).show();
					$( '[data-api-multiple-output]' ).html( result.html );
					
					// Display the button again.
					$( '[data-installation-submit]' ).show();

					// Change the submit buttons behavior.
					$( '[data-installation-submit]' ).bind( 'click' , function()
					{
						$( '[data-installation-form]' ).submit();
					});

					$( '[data-installation-loading]' ).hide();

					return false;
				}

				if( result.state == 200 )
				{
					// Append the license.
					$( '[data-installation-form]' ).append( result.html );

					$( '[data-installation-loading-hide]' );
					$( '[data-installation-form]' ).submit();					
				}

			});
		}

		if( selected == 'directory' )
		{
			$( '[data-installation-form]' ).submit();
		}
	});
});
</script>
<form action="index.php?option=com_easysocial" method="post" name="installation" data-installation-form>
<div class="row-fluid">
	<div class="span12">

		<p class="section-desc">
			<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_METHOD_DESC' );?>
		</p>

		<div class="alert alert-error" data-source-errors data-api-errors style="display: none;">
			<div data-api-errors-message><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_METHOD_API_KEY_INVALID', true ); ?></div>

			<div class="mt-10">
				<a href="http://stackideas.com/forums" class="btn btn-es-inverse" target="_blank"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_CONTACT_SUPPORT' );?></a>
			</div>
		</div>


		<div class="installation-methods">
			
			<div class="mb-15 installation-method active" data-source-method>
				<div class="row-fluid">
					<div class="span1">
						<input type="radio" name="method" value="network" id="network" data-source-type checked="checked"/>
					</div>
					<div class="span11">
						<label for="network">
							<h4 class="label-title"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_METHOD_VIA_NETWORK' );?> <span class="label label-info small"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_RECOMMENDED' );?></span></h4>
							<p class="mt-5">
								<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_METHOD_VIA_NETWORK_DESC' );?>
							</p>
						</label>
					</div>
				</div>
				<div data-source-network>
					<div class="row-fluid mt-15 mb-20">
						<div class="span11 offset1">
							<div class="form-inline">
								<label for="apikey"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_METHOD_API_KEY' );?></label>:
								<input type="text" value="<?php echo $key;?>" name="apikey" class="input input-xlarge" data-api-key />
							</div>
							<div class="mt-5" style="margin-left: 75px;">
								<a href="http://docs.stackideas.com/administrators/welcome/obtaining_api_key" target="_blank"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_METHOD_RETRIEVE_API_KEY' );?></a>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="row-fluid installation-method" data-source-method>
				<div class="span1">
					<input type="radio" name="method" value="directory" id="directory" data-source-type />
				</div>
				<div class="span11">
					<label for="directory">
						<h4 class="label-title"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_METHOD_VIA_DIRECTORY' );?></h4>

						<p class="mt-5">
							<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_METHOD_VIA_DIRECTORY_DESC' );?>:
						</p>

					</label>


					<div data-source-directory style="display: none;" class="form-inline">
						<div class="mt-20 mb-2">

							<div class="mb-10 installation-directory-path">
								<?php echo ES_PACKAGES; ?>/
							</div>

							<?php if( empty( $packages ) ){ ?>
							<div class="text-error">
								<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_METHOD_NO_PACKAGES' );?>
							</div>
							<?php } else { ?>
							<div>
								<span><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_METHOD_SELECT_PACKAGE' ); ?></span>:
								<select name="package" autocompleted="off">
									<option value="" selected="selected"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_METHOD_SELECT_A_PACKAGE' );?></option>
									<?php foreach( $packages as $package ){ ?>
									<option value="<?php echo $package; ?>"><?php echo $package; ?></option>
									<?php } ?>
								</select>
							</div>
							<?php } ?>
						</div>
					</div>

				</div>
			</div>
		</div>

		<div class="alert alert-warning" data-api-multiple style="display: none;">
			<div><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_MULTIPLE_LICENSE_FOUND' ); ?></div>

			<div class="mt-10" data-api-multiple-output>
			</div>
		</div>

	</div>
</div>
<input type="hidden" name="option" value="com_easysocial" />
<input type="hidden" name="active" value="<?php echo $active; ?>" />

<?php if( $reinstall ){ ?>
<input type="hidden" name="reinstall" value="1" />
<?php } ?>

<?php if( $update ){ ?>
<input type="hidden" name="update" value="1" />
<?php } ?>
</form>
