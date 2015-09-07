<?php
                if($this->tour){
            ?>
              <h3><?php echo JText::_('COM_BOOKPRO_TOUR_')."  ".$this->tour->title; ?></h3>  
            <?php
                }
              ?>
                <table class="table">
                            <thead>
                                <tr>
                                        <th><?php echo JText::_("COM_BOOKPRO_PACKAGE_TYPE_NAME");?></th>
                                        <th><?php echo JText::_("COM_BOOKPRO_DATE__END_DATE");?></th>
                                        <?php if (! $this->selectable) { ?>
                                            <th>
                                                <label class="checkbox">
                                                <input type="checkbox" class="inputCheckbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
                                                 <?php echo JText::_("COM_BOOKPRO_DELETE");?>
                                                </label>                             
                                                
                                            </th>
                                        <?php } ?>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td colspan="9">
                                        <?php echo $this->pagination->getListFooter(); ?>
                                    </td>
                                </tr>
                            </tfoot>     
 
                         
                            <?php if (! is_array($this->items) || ! $itemsCount && $this->tableTotal) { ?>
                            <tbody>
                                <tr><td colspan="5" class="emptyListInfo"><?php echo JText::_('COM_BOOKPRO_NO_ITEMS_FOUND'); ?></td></tr>
                            </tbody>    
                            <?php 
                            
                                } else {
                                                            
                                     for ($i = 0; $i < $itemsCount; $i++) { 
                                         $subject = &$this->items[$i]; 
                            ?>   <tbody>
                                    <tr class="record">
             
                                            <td><?php echo $subject->tourpackage_id ?></td>
                                            <td style="font-weight:normal;"><?php echo $subject->startdate.' '.JText::_('COM_BOOKPRO_TO').' '.$subject->enddate; ?></td>
                                            
                                            
                                                <?php if (! $this->selectable) { ?>
                                                    <td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
                                                <?php } ?>         
                                    </tr>
                                 </tbody>   
                                <?php 
                                    }
                                } 
                                ?>              
                     </table>