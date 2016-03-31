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
	public static function hide($name,$value,$attr=array())
	{
		$str_attr = implode(', ', array_map(
			function ($v, $k) { return "$k=\"$v\""; },
			$attr,
			array_keys($attr)
		));
		ob_start();
		?>
		<input type="hidden" name="<?php echo $name ?>" value="<?php echo $value ?>"  <?php echo $str_attr ?> >
		<?php
		$html=ob_get_clean();
		return $html;
	}
	public static function button($name,$value,$type="submit",$attr=array())
	{
		$str_attr = implode(', ', array_map(
			function ($v, $k) { return "$k=\"$v\""; },
			$attr,
			array_keys($attr)
		));
		ob_start();
		?>
		<button type="<?php echo $type ?>" class="btn btn-primary" name="<?php echo $name ?>"  <?php echo $str_attr ?> ><?php echo $value ?></button>
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
	public static function radioyesno($class_right,$name,$value,$attr=array(),$more='')
	{
		$doc=JFactory::getDocument();
		$attr = implode(', ', array_map(
			function ($v, $k) { return "$k=\"$v\""; },
			$attr,
			array_keys($attr)
		));
		$scriptId = $name;
		ob_start();
		?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				$('input[name="<?php echo $name ?>"]').change(function(){
					var self=$(this);
					if(self.is(':checked'))
					{
						self.val(1);
					}else{
						self.val(0);
					}
					var onchange=self.data('onchange');
					if(onchange!='')
					{
						eval(onchange);
					}
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
			<input <?php echo $attr ?>   <?php echo $value?'checked':'' ?> type="checkbox"  name="<?php echo $name ?>">
		</div>
		<?php
		$html=ob_get_clean();
		return $html;
	}



}
