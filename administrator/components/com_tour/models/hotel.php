<?php
/**
 * Created by PhpStorm.
 * User: Son
 * Date: 5/20/2015
 * Time: 9:25 AM
 */

class TourModelHotel extends JModelAdmin
{

    public function getTable($type = 'hotel', $prefix = 'TourTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_tour.hotel', 'hotel', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form))
        {
            return false;
        }

        // Modify the form based on access controls.
        if ($this->canEditState((object) $data) != true)
        {
            // Disable fields for display.
            $form->setFieldAttribute('published', 'disabled', 'true');

            // Disable fields while saving.
            // The controller has already verified this is a record you can edit.
            $form->setFieldAttribute('published', 'filter', 'unset');
        }

        // If in advanced mode then we make sure the new url field is not compulsory and the header
        // field compulsory in case people select non-3xx redirects
        if (JComponentHelper::getParams('com_tour')->get('mode', 0) == true)
        {
            $form->setFieldAttribute('new_url', 'required', 'false');
            $form->setFieldAttribute('header', 'required', 'true');
        }

        return $form;
    }
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_tour.edit.link.data', array());

        if (empty($data))
        {
            $data = $this->getItem();
        }

        $this->preprocessData('com_tour.link', $data);

        return $data;
    }




}