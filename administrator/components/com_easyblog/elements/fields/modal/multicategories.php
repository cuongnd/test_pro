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

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldModal_MultiCategories extends JFormField
{
	protected $type = 'Modal_MultiCategories';

	protected function getInput()
	{
		require_once( JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_easyblog' . DIRECTORY_SEPARATOR . 'constants.php' );
		require_once( EBLOG_HELPERS . DIRECTORY_SEPARATOR  . 'helper.php' );

		$mainframe	= JFactory::getApplication();
		$db			= EasyBlogHelper::db();
		$doc 		= JFactory::getDocument();

		JFactory::getLanguage()->load( 'com_easyblog' , JPATH_ROOT );

		require_once( JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_easyblog' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'categories.php' );
		$model		= new EasyBlogModelCategories();
		$categories	= $model->getAllCategories();

		if( !is_array( $this->value ) )
		{
			$this->value	= array( $this->value );
		}

		ob_start();
		?>
		<select name="<?php echo $this->name;?>[]" multiple="multiple" style="width:250px;height:200px;" class="<?php echo $this->element['class'];?>">
		<?php $selected	= in_array( 'all' , $this->value ) ? ' selected="selected"' : ''; ?>
		<option value="all"<?php echo $selected;?>><?php echo JText::_('COM_EASYBLOG_ALL_CATEGORIES'); ?></option>
		<?php		
		foreach($categories as $category)
		{
			$selected	= in_array( $category->id , $this->value ) ? ' selected="selected"' : '';
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
