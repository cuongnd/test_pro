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
abstract class JHtmlButton
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
	public static function add($task='add',$alt='Add',$attr=array('class'=>'btn btn-primary'),$icon_class="im-plus",$more='')
	{
		$doc=JFactory::getDocument();
		if($attr=='')
		{
			$attr=array('class'=>'btn btn-primary');
		}
		if(is_array($attr)&&empty($attr))
		{
			$attr=array('class'=>'btn btn-primary');
		}
		if(is_array($attr)&&!$attr['class'])
		{
			$attr['class']='btn btn-primary';
		}
		$attr = implode(', ', array_map(
			function ($v, $k) { return "$k=\"$v\""; },
			$attr,
			array_keys($attr)
		));
		ob_start();
		?>
		<button type="button" data-jtask="<?php echo $task ?>" <?php echo $attr ?>  ><i class="<?php echo $icon_class ?>"></i><?php echo JText::_($alt) ?></button>
		<?php
		$html=ob_get_clean();
		return $html;
	}
	public static function remove($task='remove',$alt='Remove',$attr=array('class'=>'btn btn-warning'),$icon_class="im-remove",$more='')
	{
		$doc=JFactory::getDocument();
		if($attr=='')
		{
			$attr=array('class'=>'btn btn-warning');
		}
		if(is_array($attr)&&empty($attr))
		{
			$attr=array('class'=>'btn btn-warning');
		}
		if(is_array($attr)&&!$attr['class'])
		{
			$attr['class']='btn btn-warning';
		}
		$attr = implode(', ', array_map(
			function ($v, $k) { return "$k=\"$v\""; },
			$attr,
			array_keys($attr)
		));
		ob_start();
		?>
		<button type="button" data-jtask="<?php echo $task ?>" <?php echo $attr ?>  ><i class="<?php echo $icon_class ?>"></i><?php echo JText::_($alt) ?></button>
		<?php
		$html=ob_get_clean();
		return $html;
	}
	public static function publish($task='publish',$alt='Publish',$attr=array('class'=>'btn btn-info'),$icon_class="en-publish",$more='')
	{
		$doc=JFactory::getDocument();
		if($attr=='')
		{
			$attr=array('class'=>'btn btn-info');
		}
		if(is_array($attr)&&empty($attr))
		{
			$attr=array('class'=>'btn btn-info');
		}
		if(is_array($attr)&&!$attr['class'])
		{
			$attr['class']='btn btn-info';
		}
		$attr = implode(', ', array_map(
			function ($v, $k) { return "$k=\"$v\""; },
			$attr,
			array_keys($attr)
		));
		ob_start();
		?>
		<button type="button" data-jtask="<?php echo $task ?>" <?php echo $attr ?>  ><i class="<?php echo $icon_class ?>"></i><?php echo JText::_($alt) ?></button>
		<?php
		$html=ob_get_clean();
		return $html;
	}
	public static function un_publish($task='un_publish',$alt='Un publish',$attr=array('class'=>'btn btn-info'),$icon_class="br-blocked",$more='')
	{
		$doc=JFactory::getDocument();
		if($attr=='')
		{
			$attr=array('class'=>'btn btn-info');
		}
		if(is_array($attr)&&empty($attr))
		{
			$attr=array('class'=>'btn btn-info');
		}
		if(is_array($attr)&&!$attr['class'])
		{
			$attr['class']='btn btn-info';
		}

		$attr = implode(', ', array_map(
			function ($v, $k) { return "$k=\"$v\""; },
			$attr,
			array_keys($attr)
		));
		ob_start();
		?>
		<button type="button" data-jtask="<?php echo $task ?>" <?php echo $attr ?>  ><i class="<?php echo $icon_class ?>"></i><?php echo JText::_($alt) ?></button>
		<?php
		$html=ob_get_clean();
		return $html;
	}
	public static function custom($task='custom',$alt='custom',$attr=array('class'=>'btn btn-primary'),$icon_class="br-inbox",$more='')
	{

		$doc=JFactory::getDocument();
		if($attr=='')
		{
			$attr=array('class'=>'btn btn-primary');
		}
		if(is_array($attr)&&empty($attr))
		{
			$attr=array('class'=>'btn btn-primary');
		}
		if(is_array($attr)&&!$attr['class'])
		{
			$attr['class']='btn btn-primary';
		}
		$attr = implode(', ', array_map(
			function ($v, $k) { return "$k=\"$v\""; },
			$attr,
			array_keys($attr)
		));
		ob_start();
		?>
		<button type="button" data-jtask="<?php echo $task ?>" <?php echo $attr ?>  ><i class="<?php echo $icon_class ?>"></i><?php echo JText::_($alt) ?></button>
		<?php
		$html=ob_get_clean();
		return $html;
	}




}
