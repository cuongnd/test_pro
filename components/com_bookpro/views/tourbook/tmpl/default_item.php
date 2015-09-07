<?php

$count_passengers = count($this->passengers);
$count_passengers = $count_passengers ? $count_passengers : 1;

?>
<?php for ($i = 0; $i < $count_passengers; $i++) { ?>
<?php 
$passenger=$this->passengers[$i];

?>
    <div class="form-horizontal passenger_item">
        <div class="row">
            <div class="col-md-10">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="control-group col-md-4">
                            <label class="control-label" for="firstname"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_FIRSTNAME'); ?>
                            </label>
                            <div class="controls">
                                <input class="inputbox required firstname" type="text" 
                                       name="person[<?php echo $this->person ?>][<?php echo $i ?>][firstname]" id="firstname" 
                                       value="<?php echo $passenger->firstname ?>"  />
                            </div>
                        </div>
                        <div class="control-group col-md-4">
                            <label class="control-label" for="lastname"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_LASTNAME'); ?>
                            </label>
                            <div class="controls">
                                <input class="inputbox required lastname" type="text" 
                                       name="person[<?php echo $this->person ?>][<?php echo $i ?>][lastname]" 
                                       value="<?php echo $passenger->lastname ?>" />
                            </div>
                        </div>
                        <div class="control-group col-md-4">
                            <label class="control-label" for="birthday"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_BIRTHDAY'); ?>
                            </label>
                            <div class="controls">
                                <input readonly="" class="inputbox required birthday" type="text" 
                                       name="person[<?php echo $this->person ?>][<?php echo $i ?>][birthday]" 
                                       value="<?php echo $passenger->birthday ?>"  />
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-2 passenger_control">
                <input class="btn" name="add" type="button" value="+"/>
                <input class="btn" name="remove" type="button" value="X"/>
            </div>
        </div>

    </div>
<?php } ?>

