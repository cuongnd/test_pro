<?php

/**
 * Files upload and select browse window.
 * 
 * @version		$Id$
 * @package		ARTIO Booking
 * @subpackage  views
 * @copyright	Copyright (C) 2010 ARTIO s.r.o.. All rights reserved.
 * @author 		ARTIO s.r.o., http://www.artio.net
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link        http://www.artio.net Official website
 */

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');
jimport('joomla.filesystem.file');
jimport('joomla.html.pagination');

//import needed JoomLIB helpers
AImporter::helper('bookpro', 'file', 'model', 'request');
//import needed assets
AImporter::js('view-files');

AImporter::adminTemplateCss(null, 'general', 'icon');
AImporter::adminTemplateCss('system', 'system');

AHtml::importIcons();

define('SESSION_PREFIX', 'booking_files_');

class BookProViewFiles extends BookproJViewLegacy
{
    function display($tpl = null)
    {
        $task = JRequest::getCmd('task');
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        
        $fpath = AFile::getFPath();
        $this->dir = $mainframe->getUserStateFromRequest('afiles_dir', 'dir', '', 'string');
        
        ADocument::addScriptPropertyDeclaration('langDisplayFrontend', JText::_('DISPLAY_ON_FRONTEND'));
		ADocument::addScriptPropertyDeclaration('langSendWithReservation', JText::_('SEND_WITH_RESERVATION'));
	
        switch ($task) {
            case 'upload':
                $error = '';
                $ufile = null;
                if (AFile::upload(JPath::clean($fpath . DS . $this->dir . DS), 'file', $ufile, $error))
                    $this->assignRef('ufile', $ufile);
                else
                    $mainframe->enqueueMessage('Unable upload file', 'error');
                break;
            case 'remove':
                $removeFiles = &ARequest::getArray('files');
                $count = count($removeFiles);
                for ($i = 0; $i < $count; $i ++)
                    JFile::delete(JPath::clean($fpath .$removeFiles[$i]));
                break;
            case 'mkdir':
                $newpath = JPath::clean($fpath . DS . $this->dir . DS . JRequest::getString('dirname'));
                if (JFolder::create($newpath, 0775) === false)
                    $mainframe->enqueueMessage(JText::sprintf('Unable create directory', $newpath), 'error');
                break;
        }
        
        parent::display($tpl);
    }
}

?>