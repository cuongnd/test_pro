<?php



defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');
jimport('joomla.filesystem.file');
jimport('joomla.html.pagination');

//import needed JoomLIB helpers
AImporter::helper('bookpro', 'image', 'model', 'request');
//import needed assets
AImporter::js('view-images');

AImporter::adminTemplateCss(null, 'general', 'icon');
AImporter::adminTemplateCss('system', 'system');

AHtmlFrontEnd::importIcons();

define('SESSION_PREFIX', 'booking_images_');

class BookproViewImages extends JViewLegacy
{

    function display($tpl = null)
    {
        $task = JRequest::getCmd('task');
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        
        $ipath = BookproHelper::getIPath();
        $this->dir = $mainframe->getUserStateFromRequest('aimages_dir', 'dir', '', 'string');
        
        switch ($task) {
            case 'upload':
                $error = '';
                $uimage = null;
                if (AImage::upload(JPath::clean($ipath . DS . $this->dir . DS), 'image', $uimage, $error))
                    $this->assignRef('uimage', $uimage);
                else
                    $mainframe->enqueueMessage('Unable upload image', 'error');
                break;
            case 'remove':
                $removeImages = &ARequest::getArray('images');
                $count = count($removeImages);
                for ($i = 0; $i < $count; $i ++)
                    JFile::delete(JPath::clean($ipath . DS . $this->dir . DS . $removeImages[$i]));
                break;
            case 'mkdir':
                $newpath = JPath::clean($ipath . DS . $this->dir . DS . JRequest::getString('dirname'));
                if (JFolder::create($newpath, 0775) === false)
                    $mainframe->enqueueMessage(JText::sprintf('Unable create directory', $newpath), 'error');
                break;
        }
        
        parent::display($tpl);
    }
}

?>