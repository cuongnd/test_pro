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

require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'model.php' );

class EasyBlogModelMetas extends EasyBlogModel
{
	/**
	 * Category total
	 *
	 * @var integer
	 */
	var $_total = null;

	/**
	 * Pagination object
	 *
	 * @var object
	 */
	var $_pagination = null;

	/**
	 * Category data array
	 *
	 * @var array
	 */
	var $_data = null;
	
	function __construct()
	{
		parent::__construct();

		
		$mainframe	= JFactory::getApplication();

		$limit		= $mainframe->getUserStateFromRequest( 'com_easyblog.meta.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		
		$limitstart = JRequest::getInt( 'limitstart' , 0 );

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	/**
	 * Method to get the total nr of the categories
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal( $type = '' )
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery( $type = '' );
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}
	
	/**
	 * Method to get a pagination object for the categories
	 *
	 * @access public
	 * @return integer
	 */
	function getPagination( $type = '' )
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination	= EasyBlogHelper::getPagination( $this->getTotal( $type ), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	/**
	 * Method to build the query for the tags
	 *
	 * @access private
	 * @return string
	 */
	function _buildQuery( $type = '' )
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildQueryWhere( $type );
		$orderby	= $this->_buildQueryOrderBy();
		$db			= EasyBlogHelper::db();
				
		$query	= 'SELECT m.*, p.title AS title FROM ' . EasyBlogHelper::getHelper( 'SQL' )->nameQuote( '#__easyblog_meta' ) . ' AS m ' .
				  'LEFT JOIN ' . EasyBlogHelper::getHelper( 'SQL' )->nameQuote( '#__easyblog_post' ) . ' AS p ' .
				  'ON m.content_id = p.id ' . 
				  $where . ' ' .
				  $orderby;

		return $query;
	}

	function _buildQueryWhere( $type = '' )
	{
		$mainframe			= JFactory::getApplication();
		$db					= EasyBlogHelper::db();
		
		$filter_state 		= $mainframe->getUserStateFromRequest( 'com_easyblog.meta.filter_state', 'filter_state', '', 'word' );
		$search 			= $mainframe->getUserStateFromRequest( 'com_easyblog.meta.search', 'search', '', 'string' );
		$search 			= $db->getEscaped( trim(JString::strtolower( $search ) ) );

		$where = array();

		if ($search)
		{
			$where[] = ' LOWER( p.title ) LIKE \'%' . $search . '%\' ';
		}
		
		if ( !empty( $type ) )
		{
			if ( $type == 'view' )
			{
				//$where[] = 'm.`id` = ' . $db->quote($cid);
				$where[] = 'm.`type` = '.$db->quote($type);
			}
			else if( $type == 'post' )
			{
				//$where[] = 'm.`id` = '.$db->quote($cid);
				$where[] = 'm.`type` = '.$db->quote($type);
			}
			else if( $type == 'blogger' )
			{
				//$where[] = 'm.`id` = '.$db->quote($cid);
				$where[] = 'm.`type` = '.$db->quote($type);
			}
		}
		
		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		return $where;
	}

	function _buildQueryOrderBy()
	{
		$mainframe			= JFactory::getApplication();

		$filter_order		= $mainframe->getUserStateFromRequest( 'com_easyblog.meta.filter_order', 		'filter_order', 	'm.id', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( 'com_easyblog.meta.filter_order_Dir',	'filter_order_Dir',	'', 'word' );

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.'';

		return $orderby;
	}

	/**
	 * Method to get categories item data
	 *
	 * @access public
	 * @return array
	 */
	function getData( $type = '', $usePagination = true )
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery( $type );
			if($usePagination)
				$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
			else
			    $this->_data = $this->_getList($query);
		}
		
		return $this->_data;
	}
	
	function getPostMeta( $id )
	{
		return $this->getMetaInfo(META_TYPE_POST, $id);
	}
	
	function getMetaInfo( $type, $id )
	{
		$db	= EasyBlogHelper::db();
		$query 	= 'SELECT id, keywords, description FROM #__easyblog_meta';
		//$query	.= ' WHERE content_id = ' . $id . ' AND type = ' . $db->Quote($type);
		$query	.= ' WHERE content_id = ' . $db->Quote($id);
		$query	.= ' AND type = ' . $db->Quote($type);
		
		
		
		$db->setQuery($query);
		$result = $db->loadObject();

		if ( empty($result) )
		{
			$obj	= new stdClass();
			$obj->id			= '';
			$obj->keywords		= '';
			$obj->description 	= '';

			return $obj;
		}
		return $result;
	}
}