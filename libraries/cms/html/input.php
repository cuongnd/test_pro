<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die(__FILE__);

/**
 * Utility class for icons.
 *
 * @package     Joomla.Libraries
 * @subpackage  HTML
 * @since       2.5
 */
abstract class JHtmlInput
{
	/**
	 * Method to generate html code for a list of buttons
	 *
	 * @param   array  $buttons  Array of buttons
	 *
	 * @return  string
	 *
	 * @since   2.5
	 */
	public static function text($class_right,$name,$value,$attr=array(),$readonly='',$size='37',$maxlength='255',$more='')
	{
		$class=$attr['class'].' form-control';
		unset($attr['class']);
		$str_attr=array();
		foreach($attr as $key=>$value){
			$str_attr[]="$key=\"$value\"";
		}
		$str_attr=implode(' ',$str_attr);
		ob_start();
		?>
		<div class="<?php echo $class_right ?>">
			<input type="text" name="<?php echo $name ?>" value="<?php echo $value ?>" class="<?php echo $class ?>" <?php echo $str_attr ?> >
		</div>
		<?php
		$html=ob_get_clean();
		return $html;
	}
	public static function price($class_right,$name,$value,$attr=array(),$min=0,$max=1000,$sign="$",$more='')
	{
		$doc=JFactory::getDocument();
		$doc->addScript(JUri::root().'/media/system/js/BobKnothe-autoNumeric/autoNumeric.js');
		$class=$attr['class'].' form-control';
		unset($attr['class']);
		$str_attr=array();
		foreach($attr as $key=>$value){
			$str_attr[]="$key=\"$value\"";
		}
		$str_attr=implode(' ',$str_attr);
		$scriptId = $name;
		ob_start();
		?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				$('input[name="input_number_<?php echo $name ?>"]').autoNumeric('init', {

				}).change(function(){
					var value=$(this).autoNumeric('get');
					$('input[name="<?php echo $name ?>"]').val(value);
				});
			});
		</script>
		<?php
		$script = ob_get_clean();
		$script = JUtility::remove_string_javascript($script);
		$doc->addScriptDeclaration($script, "text/javascript", $scriptId);
		ob_start();
		?>
		<div class="<?php echo $class_right ?>">
			<input type="text" name="input_number_<?php echo $name ?>" value="<?php echo $value ?>" class="<?php echo $class ?>" <?php echo $str_attr ?> >
			<input type="hidden" name="<?php echo $name ?>" value="<?php echo $value ?>">
		</div>
		<?php
		$html=ob_get_clean();
		return $html;
	}

	/**
	 * Method to generate html code for a list of buttons
	 *
	 * @param   array  $button  Button properties
	 *
	 * @return  string
	 *
	 * @since   2.5
	 */
	public static function button($button)
	{
		if (isset($button['access']))
		{
			if (is_bool($button['access']))
			{
				if ($button['access'] == false)
				{
					return '';
				}
			}
			else
			{
				// Get the user object to verify permissions
				$user = JFactory::getUser();

				// Take each pair of permission, context values.
				for ($i = 0, $n = count($button['access']); $i < $n; $i += 2)
				{
					if (!$user->authorise($button['access'][$i], $button['access'][$i + 1]))
					{
						return '';
					}
				}
			}
		}

		// Instantiate a new JLayoutFile instance and render the layout
		$layout = new JLayoutFile('joomla.quickicons.icon');

		return $layout->render($button);
	}
}
