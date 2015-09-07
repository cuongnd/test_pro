<?php



defined('_JEXEC') or die('Restricted access');

class TableAdmin extends JTable
{
    
    /**
     * Primary key, reference jos_users
     * 
     * @var int
     */
    var $id;

    /**
     * Construct object.
     * 
     * @param JDatabaseMySQL $db database connector
     */
    function __construct(& $db)
    {
        parent::__construct('#__' . PREFIX . '_admin', 'id', $db);
    }
}

?>