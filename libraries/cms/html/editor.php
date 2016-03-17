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
abstract class JHtmlEditor
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
	public static function basic($class_right,$name,$value,$attr,$size='100%',$height='300',$hide = array('pagebreak', 'readmore'))
	{
		$class=$attr['class'].' form-control';
		unset($attr['class']);
		$str_attr=array();
		foreach($attr as $key=>$value){
			$str_attr[]="$key=\"$value\"";
		}
		$str_attr=implode(' ',$str_attr);
		$editor =JFactory::getEditor();
		ob_start();
		?>
		<div class="<?php echo $class_right ?>">
			<?php echo $editor->display($name, $value, $size, $height, null, null ,$hide ) ?>
		</div>
		<?php
		$html=ob_get_clean();
		return $html;
	}
}
