<?php
/**
* @package      EasyBlog
* @copyright    Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$document->addStyleSheet( EASYBLOG_SCRIPTS_URI . '/media/style.css' );
?>

<div id="EasyBlogMediaManagerUI">
	<div class="mediaModalGroup">
		<div class="mediaModal loaderModal">
			<div class="modalHeader">
				<div class="modalTitle"><?php echo JText::_( 'COM_EASYBLOG_LOADING' );?></div>
				<div class="modalButtons">
					<button class="dashboardButton modalButton"><i></i></button>
				</div>
			</div>
			<div class="modalContent">
				<div class="assetItemGroup">
					<div class="assetItem asset-type-configuration loading"><i></i><?php echo JText::_( 'COM_EASYBLOG_MM_CONFIGURATION' ); ?></div>
					<div class="assetItem asset-type-common loading"><i></i><?php echo JText::_( 'COM_EASYBLOG_MM_COMMON_LIBRARY' ); ?></div>
					<div class="assetItem asset-type-uploader loading"><i></i><?php echo JText::_( 'COM_EASYBLOG_MM_MEDIA_UPLOADER' ); ?></div>
					<div class="assetItem asset-type-browser loading"><i></i><?php echo JText::_( 'COM_EASYBLOG_MM_MEDIA_BROWSER' ); ?></div>
					<div class="assetItem asset-type-editor loading"><i></i><?php echo JText::_( 'COM_EASYBLOG_MM_MEDIA_EDITOR' ); ?></div>
				</div>
			</div>
		</div>

		<div class="modalPrompt recentActivities">
			<div class="modalPromptDialog">
				<div class="promptTitle"><?php echo JText::_( 'COM_EASYBLOG_MM_RECENT_INSERTS' );?></div>
				<div class="promptContent">
					<div class="recentItemGroup"></div>
				</div>
				<div class="promptActions">
					<button class="button dashboardButton"><i></i><?php echo JText::_( 'COM_EASYBLOG_MM_BACK_TO_DASHBOARD' ); ?></button>
					<button class="button green-button promptHideButton"><i></i><?php echo JText::_( 'COM_EASYBLOG_HIDE' ); ?></button>
				</div>
			</div>
		</div>

		<div class="media-overlay"></div>
	</div>
</div>

<script type="text/javascript">
EasyBlog.module("media/configuration", function($) {

	this.resolveWith(
		{
			directorySeparator: '\<?php echo DIRECTORY_SEPARATOR; ?>',

			uploader: {
				settings: {
					runtimes: (document.documentMode==10) ? "html4" : "html5, html4",
					url: $.indexUrl + '?option=com_easyblog&controller=media&task=upload&tmpl=component&format=json&sessionid=<?php echo $session->getId(); ?>&<?php echo EasyBlogHelper::getToken();?>=1&bloggger_id=<?php echo $blogger_id; ?>&lang=en',
					max_file_size: '<?php echo $system->config->get( 'main_upload_image_size' );?>mb',
					filters: [{title: "Media files", extensions: "<?php echo $system->config->get( 'main_media_extensions' );?>"}]
				}
			},

			browser: {

				initialPlace: "user:<?php echo $system->my->id; ?>",

				acl: {
					canUpload: <?php echo ($this->acl->rules->upload_image) ? 'true' : 'false' ?>
				},				

				layout: {

					iconMaxLoadThread: <?php echo (EasyBlogHelper::getJConfig()->get( 'debug' )) ? 1 : 8; ?>,
					maxIconPerPage: <?php echo $system->config->get( 'main_media_manager_items_per_page' ); ?>
				}
			},

			exporter: {
				image: {

					<?php if ($system->config->get( 'main_media_manager_image_panel_enable_lightbox' )) { ?>
					zoom: "original",
					<?php } ?>

					lightbox: <?php echo $system->config->get( 'main_media_manager_image_panel_enable_lightbox' ) ? 'true' : 'false'; ?>,
					enforceDimension: <?php echo $system->config->get( 'main_media_manager_image_panel_enforce_image_dimension' ) ? 'true' : 'false'; ?>,
					enforceWidth: '<?php echo $system->config->get( 'main_media_manager_image_panel_enforce_image_width' );?>',
					enforceHeight: '<?php echo $system->config->get( 'main_media_manager_image_panel_enforce_image_height' );?>',
					maxVariationWidth: '<?php echo $system->config->get( 'main_media_manager_image_panel_max_variation_image_width' );?>',
					maxVariationHeight: '<?php echo $system->config->get( 'main_media_manager_image_panel_max_variation_image_height' ); ?>'
				},
				video: {
					width: <?php echo $system->config->get( 'dashboard_video_width' , 400 ); ?>,
					height: <?php echo $system->config->get( 'dashboard_video_height' , 225 ); ?>
				}
			},

			library: {

				places: [
					{
						id: "user:<?php echo $system->my->id; ?>",
						title: "<?php echo JText::_( 'COM_EASYBLOG_MM_MY_MEDIA' , true );?>",
						files: <?php echo $userFiles; ?>,
						acl: {
							canCreateFolder: true,
							canUploadItem: true,
							canRemoveItem: true,
							canCreateVariation: true,
							canDeleteVariation: true
						},
						populateImmediately: true
					}

					<?php if( $system->config->get( 'main_media_manager_place_shared_media' ) && isset($this->acl->rules->media_places_shared) && $this->acl->rules->media_places_shared ){ ?>
					,
					{
						id: "shared",
						title: "<?php echo JText::_( 'COM_EASYBLOG_MM_SHARED_MEDIA' );?>",
						files: <?php echo $sharedFiles; ?>,
						acl: {
							canCreateFolder: true,
							canUploadItem: true,
							canRemoveItem: true,
							canCreateVariation: true,
							canDeleteVariation: true
						},
						populateImmediately: true
					}
					<?php } ?>


					<?php if( $system->config->get( 'layout_media_flickr' ) && $system->config->get( 'integrations_flickr_api_key' ) != '' && $system->config->get( 'integrations_flickr_secret_key' ) != '' && $this->acl->rules->media_places_flickr ){ ?>
					,
					{
						id: "flickr",
						title: "<?php echo JText::_( 'COM_EASYBLOG_MM_FLICKR' );?>",
						options: {
							associated: <?php echo $flickrAssociated ? 'true' : 'false'; ?>,
							<?php
								$flickr_callback = 'flickr' . rand();
								$flickr_redirect = base64_encode(rtrim(JURI::root(), '/') . '/index.php?option=com_easyblog&view=media&layout=flickrLogin&tmpl=component&callback=' . $flickr_callback);
								
								$app = JFactory::getApplication();
								if ($app->isAdmin()) {
									$flickr_login = rtrim(JURI::root(), '/') . '/administrator/index.php?option=com_easyblog&c=oauth&task=request&type=' . EBLOG_OAUTH_FLICKR . '&tmpl=component&redirect=' . $flickr_redirect . '&id=' . $system->my->id;
								} else {
									$flickr_login = rtrim(JURI::root(), '/') . '/index.php?option=com_easyblog&controller=oauth&task=request&type=' . EBLOG_OAUTH_FLICKR . '&tmpl=component&redirect=' . $flickr_redirect;
								}
							?>
							callback: '<?php echo $flickr_callback; ?>',
							login: '<?php echo $flickr_login; ?>'
						}
					}
					<?php } ?>

					<?php if( $system->config->get( 'integrations_jomsocial_album' ) && JFile::exists( JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_community' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'core.php' ) && $this->acl->rules->media_places_album ){ ?>
					,
					{
						id: "jomsocial",
						title: "<?php echo JText::_( 'COM_EASYBLOG_MM_MY_ALBUMS' );?>"
					}
					<?php } ?>

					<?php if( $system->config->get( 'integrations_easysocial_album' ) && JFile::exists( JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php' ) && $this->acl->rules->media_places_album ){ ?>
					,
					{
						id: "easysocial",
						title: "<?php echo JText::_( 'COM_EASYBLOG_MM_MY_ALBUMS' );?>"
					}
					<?php } ?>
				]
			}
		}
	);
});
</script>
