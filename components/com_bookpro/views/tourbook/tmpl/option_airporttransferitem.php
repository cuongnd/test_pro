<?php
$person = $this->cart->person;
$airport_transfer = $this->cart->{$this->class_airport_transfer};


$a_key_persons = array(
    'adult' => 'adult',
    'teenner' => 'teenner',
    'children' => 'children'
);
?>
<div class="form-horizontal airpost_transfer row">
    <div class="control-group col-md-3">

    </div>
    <div class="control-group col-md-3"></div>
    <div class="col-md-3"><?php echo JText::_('COM_BOOKPRO_FLIGHT_NO') ?></div>
    <div class="col-md-3"><?php echo JText::_('COM_BOOKPRO_ARRIVAL_DATE') ?></div>
    <div class="col-md-3"><?php echo JText::_('COM_BOOKPRO_ARRIVAL_TIME') ?></div>
</div>
<?php
foreach ($person as $key_person => $person_value) {
    if (!in_array($key_person, $a_key_persons, true)) {

        continue;
    }
    for ($i = 0; $i < count($person_value); $i++) {
        $fullname = $person_value[$i]->firstname . ' ' . $person_value[$i]->lastname;
        $person = $person_value[$i];
        ?>
        <div class="form-horizontal airpost_transfer row-fluid">
            <div class="control-group span3">

            </div>
            <div class="control-group span3">
                <div class="controls">
                    <?php
                    $checked = $airport_transfer->{$key_person . ':' . $i}->sec_person_id == ($key_person . ':' . $i) ? 'checked=""' : '';
                    $data = $key_person . ':' . $i;
                    $value = $airport_transfer->{$key_person . ':' . $i}->sec_person_id == ($key_person . ':' . $i) ? $key_person . ':' . $i : '';
                    $name = $this->class_airport_transfer . '[' . $key_person . ':' . $i . '][sec_person_id]';
                    ?>


                    <label class="control-label" ><input class="inputbox transferitem" <?php echo $checked ?>  data="<?php echo $data ?>" type="checkbox" value="<?php echo $value; ?>" name="<?php echo $name; ?>"  /><?php echo $fullname ?></label>
                </div>
            </div>
            <div class="span2">
                <div class="controls">
                    <?php
                    $disabled = $airport_transfer->{$key_person . ':' . $i}->sec_person_id == ($key_person . ':' . $i) ? '' : 'disabled="disabled"';
                    $value = $airport_transfer->{$key_person . ':' . $i}->flight_number;
                    $name = $this->class_airport_transfer . '[' . $key_person . ':' . $i . '][flight_number]';
                    ?>
                    <input class="inputbox  flight_number" type="text" value="<?php echo $value; ?>" <?php echo $disabled; ?>  name="<?php echo $name; ?>" />
                </div>
            </div>
            <div class="span2">
                <div class="controls">
                    <?php
                    $disabled = $airport_transfer->{$key_person . ':' . $i}->sec_person_id == ($key_person . ':' . $i) ? '' : 'disabled="disabled"';
                    $name = $this->class_airport_transfer . '[' . $key_person . ':' . $i . '][flight_arrival_date]';
                    $value = $airport_transfer->{$key_person . ':' . $i}->flight_arrival_date;
                    ?>
                    <input readonly="" class="inputbox  <?php echo $this->class_airport_transfer ?> flight_arrival_date" <?php echo $disabled; ?> value="<?php echo $value ?>" type="text" name="<?php echo $name; ?>"  />
                </div>
            </div>
            <div class="span2">
                <div class="controls">
                    <?php
                    $value = $airport_transfer->{$key_person . ':' . $i}->flight_arrival_time;

                    $disabled = $airport_transfer->{$key_person . ':' . $i}->sec_person_id == ($key_person . ':' . $i) ? '' : 'disabled="disabled"';
                    $name = $this->class_airport_transfer . '[' . $key_person . ':' . $i . '][flight_arrival_time]';
                    ?>
                    <input class="inputbox  <?php echo $this->class_airport_transfer ?> flight_arrival_time" value="<?php echo $value; ?>" type="text" <?php echo $disabled; ?> name="<?php echo $name; ?>"  />
                </div>
            </div>
        </div>
        <?php
    }
}
?>

