<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

$gd 			= function_exists( 'gd_info' );
$curl 			= is_callable( 'curl_init' );

############################################
## MySQL info
############################################
$db 			= JFactory::getDBO();
$mysqlVersion	= $db->getVersion();

############################################
## PHP info
############################################
$phpVersion 	= phpversion();
$zipLibrary 	= function_exists( 'zip_open' );
$uploadLimit	= ini_get( 'upload_max_filesize' );
$memoryLimit 	= ini_get( 'memory_limit' );
$postSize 		= ini_get( 'post_max_size' );
$magicQuotes 	= get_magic_quotes_gpc() && JVERSION > 3;

$postSize = 4;
$hasErrors 		= false;

if( !$gd || !$curl ||  $memoryLimit < 64 || !$zipLibrary  || $magicQuotes )
{
	$hasErrors 	= true;
}
?>
<script type="text/javascript">
jQuery( document ).ready( function(){

	jQuery( '[data-installation-submit]' ).bind( 'click' , function(){

		<?php if( $hasErrors ){ ?>
			$( '[data-requirements-error]' ).show();
		<?php } else { ?>
			$( '[data-installation-form]' ).submit();
		<?php } ?>
	});

	jQuery( '[data-installation-reload]' ).bind( 'click' , function()
	{
		window.location.href 	= window.location;
	});

	jQuery( '[data-requirements-toggle]' ).on( 'click' , function()
	{
		$( '[data-system-requirements]' ).toggleClass( 'hide' );
	});

});
</script>
<form name="installation" method="post" data-installation-form>
<div class="row-fluid">
	<div class="span12">

		<p class="section-desc">
			<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_TECHNICAL_REQUIREMENTS_DESC' ); ?>
		</p>

		<?php if( !$hasErrors ){ ?>
		<div class="text-success">
			<i class="ies-checkmark mr-5"></i> <span><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_TECHNICAL_REQUIREMENTS_MET' );?></span>
		</div>
		<div class="mt-15">
			<a href="javascript:void(0);" class="btn-es btn" data-requirements-toggle><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_VIEW_REQUIREMENTS' ); ?></a>
		</div>
		<?php } ?>

		<div class="alert alert-error" data-requirements-error style="display: none;">
			<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_TECHNICAL_REQUIREMENTS_NOT_MET' );?>
		</div>

		<div class="requirements-table<?php echo $hasErrors ? '' : ' hide';?>" data-system-requirements>
			<table class="table table-striped mt-20 stats">
				<thead>
					<tr>
						<td width="40%">
							<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_TECHNICAL_REQUIREMENTS_SETTINGS' );?>
						</td>
						<td class="center" width="30%">
							<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_TECHNICAL_REQUIREMENTS_RECOMMENDED' );?>
						</td>
						<td class="center" width="30%">
							<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_TECHNICAL_REQUIREMENTS_CURRENT' );?>
						</td>
					</tr>
				</thead>

				<tbody>
					<tr class="<?php echo version_compare( $phpVersion , '5.2.4' ) == -1 ? 'error' : '';?>">
						<td>
							<div class="row-fluid">
								<span class="label label-info"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PHP' );?></span> PHP Version
								<i class="ies-help" data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PHP_VERSION_TIPS' );?>" data-toggle="tooltip" data-placement="bottom"></i>

								<?php if( version_compare( $phpVersion , '5.2.4') == -1 ){ ?>
								<a href="http://docs.stackideas.com/administrators/welcome/getting_started" class="pull-right btn btn-es-danger btn-mini"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_FIX_THIS' );?></a>
								<?php } ?>
							</div>
						</td>
						<td class="center text-success">
							5.2.4 +
						</td>
						<td class="center text-<?php echo version_compare( $phpVersion , '5.2.4' ) == -1 ? 'error' : 'success';?>">
							<?php echo $phpVersion;?>
						</td>
					</tr>
					<tr class="<?php echo !$gd ? 'error' : '';?>">
						<td>
							<div class="row-fluid">
								<span class="label label-info"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PHP' );?></span> GD Library
								<i class="ies-help" data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PHP_GD_TIPS' );?>" data-toggle="tooltip" data-placement="bottom"></i>

								<?php if( !$gd ){ ?>
								<a href="#" class="pull-right btn btn-es-danger btn-mini"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_FIX_THIS' );?></a>
								<?php } ?>
							</div>
						</td>
						<td class="center text-success">
							<i class="ies-checkmark ies-small mr-5"></i>
						</td>
						<?php if( $gd ){ ?>
						<td class="center text-success">
							<i class="ies-checkmark ies-small mr-5"></i>
						</td>
						<?php } else { ?>
						<td class="center text-error">
							<i class="ies-cancel-2 ies-small mr-5"></i>
						</td>
						<?php } ?>
					</tr>

					<tr class="<?php echo !$zipLibrary ? 'error' : '';?>">
						<td>
							<div class="row-fluid">
								<span class="label label-info"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PHP' );?></span> Zip Library
								<i class="ies-help" data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PHP_ZIP_TIPS' );?>" data-toggle="tooltip" data-placement="bottom"></i>

								<?php if( !$zipLibrary ){ ?>
								<a href="http://docs.stackideas.com/administrators/welcome/getting_started" class="pull-right btn btn-es-danger btn-mini"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_FIX_THIS' );?></a>
								<?php } ?>
							</div>
						</td>
						<td class="center text-success">
							<i class="ies-checkmark ies-small mr-5"></i>
						</td>
						<?php if( $zipLibrary ){ ?>
						<td class="center text-success">
							<i class="ies-checkmark ies-small mr-5"></i>
						</td>
						<?php } else { ?>
						<td class="center text-error">
							<i class="ies-cancel-2 ies-small mr-5"></i>
						</td>
						<?php } ?>
					</tr>

					<tr class="<?php echo !$curl ? 'error' : '';?>">
						<td>
							<div class="row-fluid">
								<span class="label label-info"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PHP' );?></span> CURL Library
								<i class="ies-help" data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PHP_CURL_TIPS' );?>" data-toggle="tooltip" data-placement="bottom"></i>
								<?php if( !$curl ){ ?>
								<a href="http://docs.stackideas.com/administrators/welcome/getting_started" class="pull-right btn btn-es-danger btn-mini"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_FIX_THIS' );?></a>
								<?php } ?>
							</div>
						</td>
						<td class="center text-success">
							<i class="ies-checkmark ies-small mr-5"></i>
						</td>
						<?php if( $curl ){ ?>
						<td class="center text-success">
							<i class="ies-checkmark ies-small mr-5"></i>
						</td>
						<?php } else { ?>
						<td class="center text-error">
							<i class="ies-cancel-2 ies-small mr-5"></i>
						</td>
						<?php } ?>
					</tr>
					<tr class="<?php echo $magicQuotes ? 'error' : '';?>">
						<td>
							<div class="row-fluid">
								<span class="label label-info"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PHP' );?></span> Magic Quotes GPC
								<i class="ies-help" data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PHP_MAGICQUOTES_TIPS' );?>" data-toggle="tooltip" data-placement="bottom"></i>

								<?php if( $magicQuotes ){ ?>
								<a href="http://docs.stackideas.com/administrators/welcome/getting_started" class="pull-right btn btn-es-danger btn-mini"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_FIX_THIS' );?></a>
								<?php } ?>
							</div>
						</td>
						<td class="center text-success">
							<i class="ies-cancel-2 ies-small mr-5"></i>
						</td>
						<td class="center text-<?php echo $magicQuotes ? 'error' : 'success';?>">
							<?php if( !$magicQuotes ){ ?>
								<i class="ies-cancel-2 ies-small mr-5"></i>
							<?php } else { ?>
								<i class="ies-checkmark ies-small mr-5"></i>
							<?php } ?>
						</td>
					</tr>
					<tr class="<?php echo $memoryLimit < 64 ? 'error' : '';?>">
						<td>
							<span class="label label-info"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PHP' );?></span> memory_limit
							<i class="ies-help" data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PHP_MEMORYLIMIT_TIPS' );?>" data-toggle="tooltip" data-placement="bottom"></i>
						</td>
						<td class="center text-success">
							64 <?php echo JText::_( 'M' );?>
						</td>
						<td class="center text-<?php echo $memoryLimit < 64 ? 'error' : 'success';?>">
							<?php echo $memoryLimit; ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="label label-inverse"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_MYSQL' );?></span> MySQL Version
							<i class="ies-help" data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_MYSQL_VERSION_TIPS' );?>" data-toggle="tooltip" data-placement="bottom"></i>
						</td>
						<td class="center text-success">
							5.0.4
						</td>
						<td class="center text-<?php echo !$mysqlVersion || version_compare( $mysqlVersion , '5.0.4' ) == -1 ? 'error' : 'success'; ?>">
							<?php echo !$mysqlVersion ? 'N/A' : $mysqlVersion;?>
						</td>
					</tr>
				</tbody>
			</table>
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
