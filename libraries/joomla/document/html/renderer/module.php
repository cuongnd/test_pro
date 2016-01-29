<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Document
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

/**
 * JDocument Module renderer
 *
 * @package     Joomla.Platform
 * @subpackage  Document
 * @since       11.1
 */
class JDocumentRendererModule extends JDocumentRenderer
{
	/**
	 * Renders a module script and returns the results as a string
	 *
	 * @param   string  $module   The name of the module to render
	 * @param   array   $attribs  Associative array of values
	 * @param   string  $content  If present, module information from the buffer will be used
	 *
	 * @return  string  The output of the script
	 *
	 * @since   11.1
	 */
	public function render($module, $attribs = array(), $content = null)
	{
		if (!is_object($module)) {
			$title = isset($attribs['title']) ? $attribs['title'] : null;
			$screensize = isset($attribs['screensize']) ? $attribs['screensize'] : null;

			$module = JModuleHelper::getModule($module, $title,$screensize);

			if (!is_object($module)) {
				if (is_null($content)) {
					return '';
				} else {
					/**
					 * If module isn't found in the database but data has been pushed in the buffer
					 * we want to render it
					 */
					$tmp = $module;
					$module = new stdClass;
					$module->params = null;
					$module->module = $tmp;
					$module->id = 0;
					$module->user = 0;
				}
			}
		}
		// Get the user and configuration object
		// $user = JFactory::getUser();
		$conf = JFactory::getConfig();

		// Set the module content
		if (!is_null($content)) {
			$module->content = $content;
		}

		// Get module parameters
		$params = new JRegistry;
		$params->loadString($module->params);

		// Use parameters from template
		if (isset($attribs['params'])) {
			$template_params = new JRegistry;
			$template_params->loadString(html_entity_decode($attribs['params'], ENT_COMPAT, 'UTF-8'));
			$params->merge($template_params);
			$module = clone $module;
			$module->params = (string)$params;
		}

		// Default for compatibility purposes. Set cachemode parameter or use JModuleHelper::moduleCache from within the
		// module instead
		$cachemode = $params->get('cachemode', 'oldstatic');

		if ($params->get('cache', 'off') == 'on' && $conf->get('caching') >= 1 && $cachemode != 'id' && $cachemode != 'safeuri') {
			// Default to itemid creating method and workarounds on
			$cacheparams = new stdClass;
			$cacheparams->cachemode = $cachemode;
			$cacheparams->class = 'JModuleHelper';
			$cacheparams->method = 'renderModule';
			$cacheparams->methodparams = array($module, $attribs);

			$contents = JModuleHelper::ModuleCache($module, $params, $cacheparams);

		} else {
			$contents = JModuleHelper::renderModule($module, $attribs);
		}
		$app=JFactory::getApplication();
		$client=$app->getClientId();
		require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
		$enableEditWebsite=UtilityHelper::getEnableEditWebsite();
		$position=explode('-',$module->position);
		$blockId=$position[1];
		if ($client==0&& $enableEditWebsite&&$params->get('disable_setting_module',1)) {
			return '<div data-module-id="'.$module->id.'" data-block-id="'.$blockId.'" class="module-content  module-grid-stack-item block-item" element-type="module"  >
					<a href="javascript:void(0)" data-module-id="'.$module->id.'" data-block-id="'.$blockId.'"  class="remove label label-danger remove-module"><i class="glyphicon-remove glyphicon"></i></a>
					<span class="drag label label-default module-move-sub-row " data-block-id="'.$blockId.'"><i class="glyphicon glyphicon-move "></i></span>
					<a data-module-id="'.$module->id.'" data-block-id="'.$blockId.'" class="menu label label-danger menu-list module-config" href="javascript:void(0)"><i class="im-menu2"></i></a>
					<div class="module-grid-stack-item-content" data-block-id="'.$blockId.'">
						<div class="panel panel-setting-module-item panel-primary toggle  ">
                        	<div class="panel-heading" data-block-id="'.$blockId.'" data-module-id="'.$module->id.'">
                        		<h4>'.$module->title.'</h4>
                        		<div style="display:none" class="pull-left panel-controls-left" data-block-id="'.$blockId.'">
									<a href="#" data-block-id="'.$blockId.'" data-module-id="'.$module->id.'" class="panel-setting "><i class="im-settings"></i></a>
								</div>



							</div>
							<div class="panel-body" data-block-id="'.$blockId.'">

								' . $contents . '
							</div>
                    		</div>
                    </div>
				</div>';
		} else
		{
			return $contents;
		}
	}
}
