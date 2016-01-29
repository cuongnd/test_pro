<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-2014 by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v6.2.4
 * @build-date      2014/12/15
 */

jimport('joomla.application.component.modellist');
class JFBConnectModelChannels extends JModelList
{
    function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select('*')
                ->from('#__jfbconnect_channel');
        return $query;
    }

    function getChannels($where = array())
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select('*')
                ->from('#__jfbconnect_channel');
        foreach ($where as $key => $value)
            $query->where($db->qn($key) . '=' . $db->q($value));

        $db->setQuery($query);
        return $db->loadObjectList();
    }
}