<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

/**
 * Utility class for jQuery JavaScript behaviors
 *
 * @package     Joomla.Libraries
 * @subpackage  HTML
 * @since       3.0
 */
abstract class JHtmlJquery
{
	/**
	 * @var    array  Array containing information for loaded files
	 * @since  3.0
	 */
	protected static $loaded = array();

	/**
	 * Method to load the jQuery JavaScript framework into the document head
	 *
	 * If debugging mode is on an uncompressed version of jQuery is included for easier debugging.
	 *
	 * @param   boolean  $noConflict  True to load jQuery in noConflict mode [optional]
	 * @param   mixed    $debug       Is debugging mode on? [optional]
	 * @param   boolean  $migrate     True to enable the jQuery Migrate plugin
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function framework($noConflict = true, $debug = null, $migrate = true)
	{
		$app=JFactory::getApplication();
		$client_id=$app->getClientId();
		$jui=$client_id?'jui':'jui_front_end';
		// Only load once
		if (!empty(static::$loaded[__METHOD__]))
		{
			return;
		}

		// If no debugging value is set, use the configuration setting
		if ($debug === null)
		{
			$config = JFactory::getConfig();
			$debug  = (boolean) $config->get('debug');
		}
		$doc=JFactory::getDocument();
		$doc->addScript(JUri::root().'media/'.$jui.'/js/jquery.js');
		//JHtml::_('script', $jui.'/jquery.js', false, true, false, false, $debug);

		// Check if we are loading in noConflict
		if ($noConflict)
		{
			$doc->addScript(JUri::root().'media/'.$jui.'/js/jquery-noconflict.js');
			//JHtml::_('script', $jui.'/jquery-noconflict.js', false, true, false, false, false);
		}

		// Check if we are loading Migrate
		if ($migrate)
		{
			$doc->addScript(JUri::root().'media/'.$jui.'/js/jquery-migrate.js');
			//JHtml::_('script', $jui.'/jquery-migrate.js', false, true, false, false, $debug);
		}

		static::$loaded[__METHOD__] = true;

		return;
	}

	/**
	 * Method to load the jQuery UI JavaScript framework into the document head
	 *
	 * If debugging mode is on an uncompressed version of jQuery UI is included for easier debugging.
	 *
	 * @param   array  $components  The jQuery UI components to load [optional]
	 * @param   mixed  $debug       Is debugging mode on? [optional]
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function ui(array $components = array('core','widget'), $debug = null)
	{
		$app=JFactory::getApplication();
		$client_id=$app->getClientId();
		$jui=$client_id?'jui':'jui_front_end';
		// Set an array containing the supported jQuery UI components handled by this method
		$supported = array('core', 'sortable','draggable');

		// Include jQuery
		static::framework();

		// If no debugging value is set, use the configuration setting
		if ($debug === null)
		{
			$config = JFactory::getConfig();
			$debug  = (boolean) $config->get('debug');
		}
		if($client_id==1) {
			// Load each of the requested components
			foreach ($components as $component) {
				// Only attempt to load the component if it's supported in core and hasn't already been loaded
				if (in_array($component, $supported) && empty(static::$loaded[__METHOD__][$component])) {
					JHtml::_('script', $jui . '/jquery.ui.' . $component . '.min.js', false, true, false, false, $debug);
					static::$loaded[__METHOD__][$component] = true;
				}
			}
		}
		else
		{
			$doc=JFactory::getDocument();
			foreach ($components as $component) {
				// Only attempt to load the component if it's supported in core and hasn't already been loaded
				if (in_array($component, $supported) && empty(static::$loaded[__METHOD__][$component])) {
					$doc->addScript(JUri::root().'/media/'.$jui. '/jquery-ui-1.11.1/ui/'.$component.'.js');
					static::$loaded[__METHOD__][$component] = true;
				}
			}
		}
		return;
	}
    public static function fixedheadertable($selector = '.fixedheadertable', $debug = null, $options = array(),$callAgain=''){
        $app=JFactory::getApplication();
        $client=$app->getClientId();
        if (isset(static::$loaded[__METHOD__][$selector]))
        {
            return;
        }
        $doc=JFactory::getDocument();
        $doc->addStyleSheet(JUri::root().'media/system/js/Fixed-Header-Table-master/css/defaultTheme.css');
        // Include jQuery
        JHtml::_('jquery.framework');
        JHtml::_('script', 'js/Fixed-Header-Table-master/jquery.fixedheadertable.js', false, true, false, false, $debug);
        $doc=JFactory::getDocument();
        $scriptId='script_lib_cms_html_jquery_fixedheadertable';
        $scriptId=$callAgain!=''?$scriptId.'_'.$callAgain:$scriptId;
        $options_str = json_encode($options, ($debug && defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : false));
        ob_start();
        ?>
        <script type="text/javascript" id="<?php echo $scriptId ?>">
            <?php
            ob_get_clean();
            ob_start();
            ?>
            jQuery(document).ready(function ($){
                $('<?php echo $selector ?>').fixedHeaderTable(
                    <?php echo $options_str ?>
                );
            });
            <?php
            $script=ob_get_clean();
            ob_start();
            ?>
        </script>
        <?php
        ob_get_clean();
        $doc->addScriptDeclaration($script,"text/javascript",$scriptId);
        static::$loaded[__METHOD__][$selector] = true;
        return;
    }
}
