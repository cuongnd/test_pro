<?php
    defined('_JEXEC') or die('Restricted access');

    //import needed JoomLIB helpers
    AImporter::helper('request', 'controller');

    AImporter::model('room', 'roomrate');    

    class BookProControllercountry extends JControllerForm
    {


        var $_model;

        function __construct($config = array())
        {
            parent::__construct($config);
            $this->_model = $this->getModel('country');
            $this->_controllerName = country;
        }
        public  function saveCountry()
        {
            echo "hello saveCountry";
            die;
        }
        /**
        * Display default view - Airport list    
        */
        function display()
        {

            switch ($this->getTask()) {
                case 'publish':
                    $this->publish();
                    break;
                case 'unpublish':
                    $this->unpublish();
                    break;
                case 'trash':
                    $this->trash();
                    break;
                default:
                    JRequest::setVar('view', 'country');
            }

            parent::display();
        }
        function save()
        {     
            JRequest::checkToken() or jexit('Invalid Token');
            $input=JFactory::getApplication()->input;
            
            $mainframe = &JFactory::getApplication();

            $post = JRequest::get('post');
            $data=$post['jform'];
            $id = $this->_model->save($data);
            if($id)
            {
                $mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=country&Itemid='.JRequest::getVar('Itemid'),Jtext::_('COM_BOOKPRO_SAVE_SUCCESS'));            
            }
            else
            {
                $mainframe->enqueueMessage(JText::_('Save failed'), 'error');
            }


        }
        function trash()
        {     
            JRequest::checkToken() or jexit('Invalid Token');

            $cid = JRequest::getVar('cid');

            $mainframe = &JFactory::getApplication();
            $db=JFactory::getDbo();
            foreach($cid as $id)
            {
                $query=$db->getQuery(true);
                $query->delete('#__bookpro_country');
                $query->where($db->qn('id').' = '.$db->q($id));
                $db->setQuery($query);
                $db->execute();
            }
            $mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=country&Itemid='.JRequest::getVar('Itemid'),Jtext::_('COM_BOOKPRO_DALETE_SUCCESS'));            
        }


    }

?>