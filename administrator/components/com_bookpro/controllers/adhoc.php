<?php
defined('_JEXEC') or die;
AImporter::model('adhoc');
class BookProControllerAdhoc extends JControllerForm
{

    function addadhocajax(){
        $data=JRequest::get( 'post' );
        $model= new BookProModelAdhoc();
        $model->save($data['ad_hoc']);
        $id_new_addhoc= $model->getState('adhoc.id');  //get id new result

        if($id_new_addhoc){
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            foreach ($data['adhoc_passenger']['passenger_id'] as $item) {
                $query="insert into #__bookpro_adhoc_passenger value('".$id_new_addhoc."','".$item."')";
                $db->setQuery($query);
                $db->execute();
            }
        }
        die;
    }
}