<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );


##########################################
## Paths
##########################################
$files 			= array();

$files['admin']	= new stdClass();
$files['admin']->path 	= JPATH_ROOT . '/administrator/components';

$files['site']	= new stdClass();
$files['site']->path 	= JPATH_ROOT . '/components';

$files['tmp']	= new stdClass();
$files['tmp']->path 	= JPATH_ROOT . '/tmp';

$files['media']	= new stdClass();
$files['media']->path 	= JPATH_ROOT . '/media';

$files['user']	= new stdClass();
$files['user']->path 	= JPATH_ROOT . '/plugins/user';

$files['system']	= new stdClass();
$files['system']->path 	= JPATH_ROOT . '/plugins/system';

$files['user']	= new stdClass();
$files['user']->path 	= JPATH_ROOT . '/plugins/user';

$files['auth']	= new stdClass();
$files['auth']->path 	= JPATH_ROOT . '/plugins/authentication';

##########################################
## Debugging
##########################################
$posixExists 		= function_exists( 'posix_getpwuid' );

if( $posixExists )
{
	$owners 			= array();
}


##########################################
## Determine states
##########################################
$hasErrors	= false;

foreach( $files as $file )
{
	$file->writable 	= is_writable( $file->path );

	if( !$file->writable )
	{
		$hasErrors 		= true;
	}

	if( $posixExists )
	{
		$owner 			= posix_getpwuid( fileowner( $file->path ) );
		$group 			= posix_getpwuid( filegroup( $file->path ) );

		$file->owner 		= $owner[ 'name' ];
		$file->group 		= $group[ 'name' ];
		$file->permissions	= substr( decoct( fileperms( $file->path ) ) , 1 );
	}
}
?>
<script type="text/javascript">
jQuery( document ).ready( function(){

	jQuery( '[data-permissions-info]' ).bind( 'click' , function(){
		jQuery( this ).parents( 'td' ).find( '.permissions-info' ).toggle();
	});

	jQuery( '[data-installation-submit]' ).bind( 'click' , function(){

		<?php if( $hasErrors ){ ?>
			$( '[data-permissions-error]' ).show();
		<?php } else { ?>
			$( '[data-installation-form]' ).submit();
		<?php } ?>
	});

	jQuery( '[data-installation-reload]' ).bind( 'click' , function()
	{
		jQuery( '[data-installation-form]' ).submit();
	});

});
</script>

<form name="installation" method="post" data-installation-form>
<div class="row-fluid">
	<div class="span12">
		<p class="section-desc">
			<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PERMISSIONS_DESC' ); ?>
		</p>

		<?php if( $hasErrors ){ ?>
		<div class="alert alert-error" data-permissions-error style="display: none;">
			<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PERMISSIONS_ERROR' );?>
			<div class="mt-10">
				<a href="javascript:void(0);" class="btn btn-es-inverse" data-installation-reload><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_RELOAD' );?></a>
			</div>
		</div>
		<?php } ?>

		<table class="table table-striped mt-20 stats">
			<thead>
				<tr>
					<td width="75%">
						<?php echo JText::_( 'Directory' ); ?>
					</td>
					<td class="center" width="25%">
						<?php echo JText::_( 'State' ); ?>
					</td>
				</tr>
			</thead>

			<tbody>
				<?php foreach( $files as $file ){ ?>
				<tr class="<?php echo !$file->writable ? 'error' : '';?>">
					<td>
						<div class="row-fluid">
							<span><?php echo $file->path;?></span>

							<?php if( !$file->writable ){ ?>
							<a href="javascript:void(0);" class="btn btn-es-inverse btn-mini pull-right" data-permissions-info><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_INFO' ); ?></a>
							<a href="javascript:void(0);" class="btn btn-es-danger btn-mini pull-right mr-5"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_HOW_TO_FIX' ); ?></a>
							<?php } ?>
						</div>

						<ul class="permissions-info unstyled mt-10">
							<?php if( $posixExists ){ ?>
							<li>
								<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PERMISSIONS_CURRENT_OWNER' ); ?>: <strong><?php echo $file->owner; ?></strong>
							</li>
							<li>
								<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PERMISSIONS_CURRENT_GROUP' ); ?>: <strong><?php echo $file->group; ?></strong>
							</li>
							<li>
								<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PERMISSIONS_CURRENT_MODE' );?>: <strong><?php echo $file->permissions; ?></strong>
							</li>
							<?php } else { ?>
							<li>
								<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PERMISSIONS_POSIX' );?> 
							</li>
							<?php } ?>
						</ul>

					</td>
					<?php if( $file->writable ){ ?>
					<td class="center text-success">
						<i class="ies-checkmark ies-small mr-5"></i>
						<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PERMISSIONS_WRITABLE' );?>
					</td>
					<?php } else { ?>
					<td class="center text-error">
						<i class="ies-cancel-2 ies-small mr-5"></i>
						<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PERMISSIONS_UNWRITABLE' );?>
					</td>
					<?php } ?>
				</tr>
				<?php } ?>

			</tbody>
		</table>
	</div>
</div>

<?php if( !$hasErrors ){ ?>
<div class="alert alert-success">
	<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_PERMISSIONS_SUCCESS' );?>
</div>
<?php } ?>

<input type="hidden" name="option" value="com_easysocial" />
<input type="hidden" name="active" value="<?php echo $active; ?>" />

<?php if( $reinstall ){ ?>
<input type="hidden" name="reinstall" value="1" />
<?php } ?>

<?php if( $update ){ ?>
<input type="hidden" name="update" value="1" />
<?php } ?>
</form>