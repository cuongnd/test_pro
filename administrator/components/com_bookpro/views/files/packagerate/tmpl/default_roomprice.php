<?php
 
        AImporter::model('roomtypes');
        $modelRoomTypes = new BookProModelRoomTypes();  
        //$model->init($param);
        //$item=$model->getData();
        $this->itemsmodelRoomTypes = &$modelRoomTypes->getData();
        $itemsCountmodelRoomTypes = count($this->itemsmodelRoomTypes);
        //echo "<pre>"; var_dump($itemsCount);exit();
        
  
    

?>


<label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_FRENIGHT'); ?></strong> </label><br />
<table>
    <thead>
        <tr>
            <td></td>
            
            <td><strong>Pre Price</strong></td>
            <td><strong>Post Price</strong></td>
        </tr>
    </thead>
    
    <tbody>
      <?php
            for($i=0;$i<$itemsCountmodelRoomTypes;$i++){
               $subjectRoomTypes = &$this->itemsmodelRoomTypes[$i];
      ?>
      <tr>
               <td>
                    <strong><?php echo $subjectRoomTypes->title; ?></strong>
               </td>
               <input class="text_area input-mini" type="hidden" value="<?php echo $subjectRoomTypes->id; ?>" name="roomtype[]">
               <td><input class="text_area input-mini" type="text" value="" name="prenight[]"></td>
               <td><input class="text_area input-mini" type="text" value="" name="postnight[]"></td>

      </tr>
      <?php
            }
        ?>  
    </tbody>
</table>
<div class="form-inline" >
<label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_PROMOTION'); ?></strong> </label>
<strong><?php echo JText::_('Yes')?></strong><input type="radio" name="type" id="type1" value="1">
<strong><?php echo JText::_('No')?></strong><input type="radio" name="type" id="type0" value="0">
</div>
