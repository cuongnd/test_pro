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

jimport( 'joomla.application.component.view');

class EasyBlogViewTags extends EasyBlogView
{
	function display( $tmpl = null )
	{
		$config	= EasyBlogHelper::getConfig();

		$id		= JRequest::getCmd('id','0');
		$tag 	= EasyBlogHelper::getTable( 'Tag' );
		$tag->load($id);

		$model		= $this->getModel( 'Blog' );
		$posts		= $model->getTaggedBlogs( $id );

		$weever	= EasyBlogHelper::getHelper( 'Weever' )->getMainFeed();

		$weever->set( 'description'	, JText::sprintf( 'COM_EASYBLOG_FEEDS_TAGS_DESC' , htmlentities($tag->title) ) );
		$weever->set( 'url'			, EasyBlogRouter::getRoutedUrl( 'index.php?option=com_easyblog&view=categories&id=' . $id . '&format=weever' , false , true ) );
		$weever->set( 'thisPage'	, 1 );
		$weever->set( 'lastPage'	, 1 );

		if( $posts )
		{
			foreach( $posts as $post )
			{
				$blog 	= EasyBlogHelper::getTable( 'Blog' );
				$blog->load( $post->id );

				$weever->addChild( $blog );
			}
		}

		$weever->toJSON( true , JRequest::getVar( 'callback') );
	}
}