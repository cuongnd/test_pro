<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access');

class JElementMultiCategoriesParent extends JElement
{
	var	$_name = 'MultiCategoriesParent';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$mainframe	= JFactory::getApplication();
		$db			= JFactory::getDBO();
		$doc 		= JFactory::getDocument();

		JFactory::getLanguage()->load( 'com_easyblog' , JPATH_ROOT );

		require_once( JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_easyblog' . DS . 'models' . DS . 'categories.php' );
		$model		= new EasyBlogModelCategories();
		$categories	= $model->getAllCategories( true );

		if( !is_array( $value ) )
		{
			$value	= array( $value );
		}
		ob_start();
		?>
		<select name="<?php echo $control_name;?>[<?php echo $name;?>][]" multiple="multiple" style="width:300px;height:250px;">
		<?php $selected	= in_array( 'all' , $value ) ? ' selected="selected"' : ''; ?>
		<option value="all"<?php echo $selected;?>><?php echo JText::_('COM_EASYBLOG_ALL_PARENT_CATEGORIES'); ?></option>
		<?php		
		foreach($categories as $category)
		{
			$selected	= in_array( $category->id , $value ) ? ' selected="selected"' : '';
		?>
			<option value="<?php echo $category->id;?>"<?php echo $selected;?>><?php echo $category->title;?></option>
		<?php
		}
		?>
		</select>
		<?php
		$html	= ob_get_contents();
		ob_end_clean();
		return $html;
	}
}
