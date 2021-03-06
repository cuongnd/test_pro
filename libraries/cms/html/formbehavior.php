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
 * Utility class for form related behaviors
 *
 * @package     Joomla.Libraries
 * @subpackage  HTML
 * @since       3.0
 */
abstract class JHtmlFormbehavior
{
	/**
	 * @var    array  Array containing information for loaded files
	 * @since  3.0
	 */
	protected static $loaded = array();

	/**
	 * Method to load the Chosen JavaScript framework and supporting CSS into the document head
	 *
	 * If debugging mode is on an uncompressed version of Chosen is included for easier debugging.
	 *
	 * @param   string  $selector  Class for Chosen elements.
	 * @param   mixed   $debug     Is debugging mode on? [optional]
	 * @param   array   $options   the possible Chosen options as name => value [optional]
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function chosen($selector = '.advancedSelect', $debug = null, $options = array(),$callAgain='')
	{
		$selector=$selector.':not([disableChosen="true"])';
		$app=JFactory::getApplication();
		$client=$app->getClientId();
		$jui=$client==0?'jui_front_end':'jui';
		if (isset(static::$loaded[__METHOD__][$selector]))
		{
			return;
		}

		// Include jQuery
		JHtml::_('jquery.framework');

		// If no debugging value is set, use the configuration setting
		if ($debug === null)
		{
			$config = JFactory::getConfig();
			$debug  = (boolean) $config->get('debug');
		}

		// Default settings
		$options['disable_search_threshold']  = isset($options['disable_search_threshold']) ? $options['disable_search_threshold'] : 10;
		$options['allow_single_deselect']     = isset($options['allow_single_deselect']) ? $options['allow_single_deselect'] : true;
		$options['placeholder_text_multiple'] = isset($options['placeholder_text_multiple']) ? $options['placeholder_text_multiple']: JText::_('JGLOBAL_SELECT_SOME_OPTIONS');
		$options['placeholder_text_single']   = isset($options['placeholder_text_single']) ? $options['placeholder_text_single'] : JText::_('JGLOBAL_SELECT_AN_OPTION');
		$options['no_results_text']           = isset($options['no_results_text']) ? $options['no_results_text'] : JText::_('JGLOBAL_SELECT_NO_RESULTS_MATCH');

		// Options array to json options string
		$options_str = json_encode($options, ($debug && defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : false));
		JHtml::_('script', $jui.'/chosen.jquery.min.js', false, true, false, false, $debug);
		JHtml::_('stylesheet', $jui.'/chosen.css', false, true);
		$doc=JFactory::getDocument();
		$scriptId='script_lib_cms_html_form_behavior_chosen';
		$scriptId=$callAgain!=''?$scriptId.'_'.$callAgain:$scriptId;
		ob_start();
		?>
		<script type="text/javascript" id="<?php echo $scriptId ?>">
			<?php
				ob_get_clean();
				ob_start();
			?>
			jQuery(document).ready(function ($){
				$('<?php echo $selector ?>').chosen(<?php echo $options_str ?>);
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
	public static function select2($selector = '.advancedSelect', $debug = null, $options = array(),$callAgain='')
	{
		$selector=$selector.':not([disable_select2="true"])';
		$app=JFactory::getApplication();
		$client=$app->getClientId();
		$jui=$client==0?'jui_front_end':'jui';

		// Include jQuery
		JHtml::_('jquery.framework');

		// If no debugging value is set, use the configuration setting
		if ($debug === null)
		{
			$config = JFactory::getConfig();
			$debug  = (boolean) $config->get('debug');
		}

		// Default settings
		$options['disable_search_threshold']  = isset($options['disable_search_threshold']) ? $options['disable_search_threshold'] : 10;
		$options['allow_single_deselect']     = isset($options['allow_single_deselect']) ? $options['allow_single_deselect'] : true;
		$options['placeholder_text_multiple'] = isset($options['placeholder_text_multiple']) ? $options['placeholder_text_multiple']: JText::_('JGLOBAL_SELECT_SOME_OPTIONS');
		$options['placeholder_text_single']   = isset($options['placeholder_text_single']) ? $options['placeholder_text_single'] : JText::_('JGLOBAL_SELECT_AN_OPTION');
		$options['no_results_text']           = isset($options['no_results_text']) ? $options['no_results_text'] : JText::_('JGLOBAL_SELECT_NO_RESULTS_MATCH');
		$options['width']           = 'resolve';

		// Options array to json options string
		$options_str = json_encode($options, ($debug && defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : false));
		$doc=JFactory::getDocument();
        $doc->addLessStyleSheet(JUri::root().'media/system/js/select2-4.0.0/dist/css/select2.css');
        $doc->addScript(JUri::root().'/media/system/js/select2-4.0.0/dist/js/select2.full.js');
		$scriptId='lib_cms_html_form_behavior_form_select2'.$callAgain;
		$ajaxCallFunction='setSelect2'.$callAgain;
		ob_start();
		?>
		<script type="text/javascript" id="<?php echo $scriptId ?>">
			<?php
				ob_get_clean();
				ob_start();
			?>
			function setSelect2<?php echo $callAgain ?>()
			{
				$('<?php echo $selector ?>')
					.select2(<?php echo $options_str ?>)
					.on("select2-removed", function(e) {
						<?php echo $options['onremoveitem'] ?>;
					}).on("select2-selecting", function(e) {
						<?php echo $options['onselecting'] ?>;
					});
			}
			<?php
			 $scriptContent=ob_get_clean();
			 ob_start();
			  ?>
		</script>
		<?php
		ob_get_clean();
		$doc->addAjaxCallFunction($ajaxCallFunction,$scriptContent,$scriptId);



		return;
	}


	/**
	 * Method to load the AJAX Chosen library
	 *
	 * If debugging mode is on an uncompressed version of AJAX Chosen is included for easier debugging.
	 *
	 * @param   JRegistry  $options  Options in a JRegistry object
	 * @param   mixed      $debug    Is debugging mode on? [optional]
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function ajaxchosen(JRegistry $options, $debug = null)
	{
		$app=JFactory::getApplication();
		$client=$app->getClientId();
		$jui=$client==0?'jui_front_end':'jui';
		// Retrieve options/defaults
		$selector       = $options->get('selector', '.tagfield');
		$type           = $options->get('type', 'GET');
		$url            = $options->get('url', null);
		$dataType       = $options->get('dataType', 'json');
		$jsonTermKey    = $options->get('jsonTermKey', 'term');
		$afterTypeDelay = $options->get('afterTypeDelay', '500');
		$minTermLength  = $options->get('minTermLength', '3');

		JText::script('JGLOBAL_KEEP_TYPING');
		JText::script('JGLOBAL_LOOKING_FOR');

		// Ajax URL is mandatory
		if (!empty($url))
		{
			if (isset(static::$loaded[__METHOD__][$selector]))
			{
				return;
			}

			// Include jQuery
			JHtml::_('jquery.framework');

			// Requires chosen to work
			static::chosen($selector, $debug);

			JHtml::_('script', $jui.'/ajax-chosen.min.js', false, true, false, false, $debug);
			JFactory::getDocument()->addScriptDeclaration("
				(function($){
					$(document).ready(function () {
						$('" . $selector . "').ajaxChosen({
							type: '" . $type . "',
							url: '" . $url . "',
							dataType: '" . $dataType . "',
							jsonTermKey: '" . $jsonTermKey . "',
							afterTypeDelay: '" . $afterTypeDelay . "',
							minTermLength: '" . $minTermLength . "'
						}, function (data) {
							var results = [];

							$.each(data, function (i, val) {
								results.push({ value: val.value, text: val.text });
							});

							return results;
						});
					});
				})(jQuery);
				",'','script_ajaxChosen'
			);

			static::$loaded[__METHOD__][$selector] = true;
		}

		return;
	}
}
