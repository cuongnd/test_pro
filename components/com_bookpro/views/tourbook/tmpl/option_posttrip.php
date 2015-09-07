<?php
$a_trip_acommodaton = $this->cart->{$this->class_trip_acommodaton};
?>
<div class="span12 room">
    <h3 class="title minusimage slidetoggle"><?php echo $this->trip_acommodaton; ?></h3>
    <div class="content row-fluid">
        <div class="description row-fluid">
            <div class="span8 div_description">
                <?php echo $this->trip_acommodaton_description ?>
            </div>
            <div class="span4">
                <div class="price_booknow" >
                    <div><?php echo Jtext::_('COM_BOOKPRO_PRICE') ?>:US$ 40/SGL or TWIN</div>
                    <div class="booknow booknow_item minusimage"><?php echo JText::_('COM_BOOKPRO_BOOKNOW') ?></div>
                </div>
            </div>
        </div>
        <?php for ($m = 0; $m < (count($a_trip_acommodaton) ? count($a_trip_acommodaton) : 1); $m++) { ?>

            <?php $trip_acommodaton = $a_trip_acommodaton[$m] ?>
            <div class="row-fluid form-content form-acommodation <?php echo $this->class_trip_acommodaton; ?>">
                <div class="checkin_checkout col-md-5 form-horizontal">
                    <div class="colse btn_close a_btn_close"></div>
                    <div class="form-group ">
                        <label class="control-label" for="checkin"><?php echo JText::_('COM_BOOKPRO_CHECKIN'); ?>
                        </label>
                        <div class="">
                            <input readonly="" class="form-control inputbox <?php echo $this->class_trip_acommodaton ?> checkin" type="text" name="<?php echo $this->class_trip_acommodaton ?>[<?php echo $m ?>][checkin]" value="<?php echo $trip_acommodaton->checkin ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CHECKIN'); ?>" />
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="control-label" for="checkout"><?php echo JText::_('COM_BOOKPRO_CHECKOUT'); ?>
                        </label>
                        <div class="">
                            <input readonly="" class="form-control inputbox  <?php echo $this->class_trip_acommodaton ?> checkout" type="text" name="<?php echo $this->class_trip_acommodaton ?>[<?php echo $m ?>][checkout]" value="<?php echo $trip_acommodaton->checkout ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CHECKOUT'); ?>" />
                        </div>
                    </div>
                </div>
                <div class="col-md-7 room_and_passenger">
                    <div class="select_room_title"><?php echo JText::_('COM_BOOKPRO_SELECT_ROOM'); ?></div>
                    <div class="listroom <?php echo $this->class_trip_acommodaton ?> row">
                        <?php for ($i = 0; $i < count($this->listroomtype); $i++) { ?>
                            <?php
                            $roomtype = $this->listroomtype[$i];
                            $date_attr = "room_type_id_" . $roomtype->id;
                            ?>

                            <div class="control-group col-md-<?php echo 12 / count($this->listroomtype) ?>">
                                <label class="control-label" for="<?php echo $date_attr ?>"><?php echo $roomtype->title; ?></label>
                                <div class="controls">
                                    <select data_trip_acommodaton="<?php echo $this->class_trip_acommodaton ?>" data="<?php echo $date_attr ?>" class="input-small room_select <?php echo $date_attr ?>" name="<?php echo $this->class_trip_acommodaton . '[' . $m . '][aaatrip_acommodaton][' . $i . '][roomtype_ids]' ?>">
                                        <option value="0">0</option>
                                        <?php for ($j = 1; $j <= count($this->a_listadultandteennerandchildren); $j++) { ?>
                                            <?php $a_value = $roomtype->id . ':' . $roomtype->max_person . ':' . $j; ?>
                                            <option <?php echo $trip_acommodaton->trip_acommodaton[$i]->roomtype_id == $a_value ? 'selected=""' : '' ?>  value="<?php echo $a_value ?>"><?php echo $j ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>


                    </div>
                    <div class="select_passenger row">
                        <?php for ($i = 0; $i < count($this->listroomtype); $i++) { ?>
                            <?php
                            $roomtype = $this->listroomtype[$i];
                            $date_attr = "room_type_id_" . $roomtype->id;
                            $max_person = $roomtype->max_person;
                            ?>
                            <div class="a_room_select <?php echo $this->class_trip_acommodaton ?> <?php echo $date_attr ?> form-horizontal">
                                <?php
                                $a_setroom = $trip_acommodaton->trip_acommodaton[$i]->setroom;
                                $count_setroom = count($a_setroom);
                                $count_setroom = $count_setroom ? $count_setroom : 1;
                                for ($k = 0; $k < $count_setroom; $k++) {
                                    $setroom_item = $a_setroom[$k];
                                    ?>
                                    <div class="control-group control-person <?php echo $this->class_trip_acommodaton ?>">
                                        <label class="control-label span3 passenger" for="passenger"><?php echo $roomtype->title; ?>&nbsp; <span class="<?php echo $date_attr ?>">1</span>
                                        </label>
                                        <div class="controls span9 pull-right">
                                            <?php for ($j = 0; $j < $max_person; $j++) { ?>
                                                <?php echo AHtmlFrontEnd::getFilterSelect($this->class_trip_acommodaton . '[' . $m . '][trip_acommodaton][' . $i . '][setroom][' . $k . '][person_sec_ids][' . $j . ']', JText::_('COM_BOOKPRO_SELECT_PASSENGER'), $this->a_listadultandteenner, $setroom_item->person_sec_ids[$j], false, 'class="passenger ' . $this->class_trip_acommodaton . '" id="" data="' . $date_attr . '"') ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                    </div>
                </div>
                
            </div>
        
        <?php } ?>
       <input style="margin-bottom: 10px; margin-left: 10px" type="button" class="btn" data="<?php echo $this->class_trip_acommodaton; ?>" name="make_other_booking" value="<?php echo JText::_('COM_BOOKING_MAKE_OTHER_BOOKING') ?>"> 
    </div>
</div>




