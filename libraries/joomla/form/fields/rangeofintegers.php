<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Platform.
 * Provides a select list of integers with specified first, last and step values.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldRangeofintegers extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'rangeofintegers';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	public function get_attribute_config()
	{
		return array(
			first=>0,
			last=>100,
			step=>1
		);
	}
	protected function getInput()
	{
		$doc=JFactory::getDocument();
		$doc->addStyleSheet(JUri::root() . "/media/system/js/ion.rangeSlider-master/css/ion.rangeSlider.css");

		$doc->addStyleSheet(JUri::root() . "/media/system/js/ion.rangeSlider-master/css/ion.rangeSlider.skinHTML5.css");
		$doc->addScript(JUri::root().'/media/system/js/ion.rangeSlider-master/js/ion.rangeSlider.js');

		$scriptId = "libraries_joomla_form_fields_rangeofintegers" . '_' . JUserHelper::genRandomPassword();
// Initialize some field attributes.
		$first = (int) $this->element['first'];
		$last = (int) $this->element['last'];
		$step = (int) $this->element['step'];
		ob_start();
		?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {

				$('input[name="<?php echo $this->name ?>"]').ionRangeSlider({
					type: "single",
					min: <?php echo $first ?>,
					max: <?php echo $last ?>,
					from: <?php echo $this->value?$this->value:$first ?>,
					keyboard: true,
					keyboard_step:1,
					grid: true,
					grid_num: 10,
					onChange: function (data) {
						<?php echo $this->onchange ?>
					},
					onFinish: function (data) {
						<?php echo $this->element['on_finish'] ?>
					}
				});

			});
		</script>
		<?php
		$script=ob_get_clean();
		$script=JUtility::remove_string_javascript($script);
		$doc->addScriptDeclaration($script, "text/javascript", $scriptId);



		// Initialize some field attributes.
		$max      = !empty($this->max) ? ' max="' . $this->max . '"' : '';
		$min      = !empty($this->min) ? ' min="' . $this->min . '"' : '';
		$step     = !empty($this->step) ? ' step="' . $this->step . '"' : '';
		$class    = !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$readonly = $this->readonly ? ' readonly' : '';
		$disabled = $this->disabled ? ' disabled' : '';

		$autofocus = $this->autofocus ? ' autofocus' : '';

		$value = (float) $this->value;
		$value = empty($value) ? $this->min : $value;

		// Initialize JavaScript field attributes.
		$onchange = !empty($this->onchange) ? ' onchange="' . $this->onchange . '"' : '';

		// Including fallback code for HTML5 non supported browsers.
		JHtml::_('jquery.framework');
		JHtml::_('script', 'system/html5fallback.js', false, true);
		$html='';
		ob_start();
		?>
		<input type="hidden" <?php echo $class ?>  value="<?php echo $this->value ?>" name="<?php echo $this->name ?>" id="<?php echo  $this->id  ?>" data-value="<?php echo  htmlspecialchars($value, ENT_COMPAT, 'UTF-8') ?>" />
		<?php
		$html=ob_get_clean();
		return $html;
	}

}
