<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class JFBConnectModelChannel extends JModelLegacy
{
    public function getChannels()
    {
        $query = $this->_db->getQuery(true);
        $query->select('*')
            ->from($this->_db->qn('#__jfbconnect_channel'))
            ->where($this->_db->qn('published') . '=' . $this->_db->q('1'));
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }
}