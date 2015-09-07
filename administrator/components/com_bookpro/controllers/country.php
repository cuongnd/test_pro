<?php


defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class BookProControllerCountry extends JControllerForm
{
    public function updateItem()
    {
        $app=JFactory::getApplication();
        $models=$app->input->get('models',array(),'array');
        $models=$models[0];
        $models=json_decode($models);
        $models=$models[0];

        $modalCountry=$this->getModel();
        $tableCountry=$modalCountry->getTable();
        $tableCountry->load($models->id);
        $tableCountry->bind($models);

        if(!$tableCountry->store())
        {
            echo $tableCountry->getError();
        }
        die;
    }
    public function DeleteItem()
    {
        $app=JFactory::getApplication();
        $models=$app->input->get('models',array(),'array');
        $id=$app->input->get('id',0,'int');
        $models=$models[0];
        $models=json_decode($models);
        $models=$models[0];

        $modalCountry=$this->getModel();
        $tableCountry=$modalCountry->getTable();
        $tableCountry->load($models->id);

        if($tableCountry->delete($id)){
            echo 'ok';
        }else{
            echo $tableCountry->getError();
        }
        die;
    }

}