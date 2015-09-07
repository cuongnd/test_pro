<?php if ($this->cart->needasignchildrenforspecialroom) return; ?>
<?php
//echo rand(1, 5);
$person = $this->cart->person;
$k = 1;
$a_key_persons = array(
    'adult' => 'adult',
    'teenner' => 'teenner',
    'children' => 'children'
);
$a_array_room = array();
foreach ($person as $key_person => $a_person) {
    if (!in_array($key_person, $a_key_persons))
        continue;
    for ($i = 0; $i < count($a_person); $i++) {
        $passenger = $a_person[$i];
        if ($passenger->setchildrenacommodation && $passenger->setchildrenacommodation->oder_room) {
            $a_array_room[$passenger->setchildrenacommodation->oder_room - 1]->total_children++;
            if ($passenger->setchildrenacommodation->needbed)
                $a_array_room[$passenger->setchildrenacommodation->oder_room - 1]->total_extrabed++;
        }
    }
}
//print_r($a_array_room);
?>
<?php for ($i = 0; $i < count($this->cart->person->children); $i++) { ?>
    <?php
    $person = $this->cart->person->children[$i];
    $list_selected = $person->setchildrenacommodation->list_selected;
    $list_selected = explode(',', $list_selected);
    //print_r($list_selected);
    $fullname = $person->firstname . ' ' . $person->lastname;
    ?>
    <div class="setroom_select">
        <input type="hidden" name="setchildrenacommodation[<?php echo $i ?>][person_sec_id]" value="<?php echo 'children:' . $i ?>">
        <input type="hidden" class="hidden_focus" name="setchildrenacommodation[<?php echo $i ?>][focus]" value="">
        <div class="row-fluid">
            <div class="span5">
                <h3><?php echo JText::_('COM_BOOKPRO_CHILD'); ?>:&nbsp;<?php echo $i + 1 ?>&nbsp; <?php echo $fullname ?></h3>
            </div>
            <div class="span7">
                <div class="control-group span6">
                    <label class="control-label" for="passenger"><?php echo JText::_('COM_BOOKPRO_SHARE_ROOM'); ?>
                    </label>
                    <div class="controls">
                        <?php
                        $total_setroom = count($this->cart->setroom);
                        $total_setroom = $total_setroom == 0 ? 1 : $total_setroom;
                        $adata = array();
                        $adata[0]['value'] = 0;
                        $adata[0]['text'] = JText::_('COM_BOOKPRO_SELECT_ROOM');
                       
                        for ($j = 1; $j <= $total_setroom; $j++) {
                            $order_room = $j - 1;
                            if (is_object($this->cart->setchildrenacommodation[$i]->oder_room)&& $this->cart->setchildrenacommodation[$i]->oder_room != $j) {
                                
                                $roomtype = json_decode($this->cart->setroom[$order_room]->roomtype);
                                $roomtype = $this->pivot_listroomtype[$roomtype->id];

                                $maxchildren = $roomtype->max_children;

                                $total_current_children = $a_array_room[$order_room]->total_children;
                                if ($total_current_children >= $maxchildren) {
                                    
                                    continue;
                                }
                            }
                            $adata[$j]['value'] = $j;
                            $adata[$j]['text'] = JText::_('COM_BOOKPRO_ROOM') . ' ' . ($j);
                             
                        }
                        
                        echo JHtmlSelect::genericlist($adata, 'setchildrenacommodation[' . $i . '][oder_room]', 'class="input-small share_room"', 'value', 'text', $this->cart->setchildrenacommodation[$i]->oder_room);
                        ?>
                    </div>
                </div>
                <div class="control-group span6">
                    <label class="control-label" for="passenger"><?php echo JText::_('COM_BOOKPRO_NEED_EXTRA_BED'); ?>
                    </label>
                    <div class="controls">
                        <?php
                        $order_room = $this->cart->setchildrenacommodation[$i]->oder_room;
                        $roomtype = json_decode($this->cart->setroom[$order_room - 1]->roomtype);
                        $roomtype = $this->pivot_listroomtype[$roomtype->id];
                        $allow_maxextrabed = $roomtype->max_extra_bed;
                        
                        $total_extrabed = $a_array_room[$order_room-1]->total_extrabed;

                        if (!$this->cart->setchildrenacommodation[$i]->needbed && $total_extrabed >= $allow_maxextrabed) {
                            $array_attr = array(
                                'class' => 'needbed',
                                "disabled" => "disabled"
                            );
                        } else {
                            $array_attr = array('class' => 'needbed');
                        }
                        ?>
                        <input type="hidden" value="<?php echo $this->cart->setchildrenacommodation[$i]->disable ?>" class="disable" name="<?php echo 'setchildrenacommodation[' . $i . '][disable]' ?>"/>
                        <?php echo $this->booleanyesnolist('setchildrenacommodation[' . $i . '][needbed]', $array_attr, $this->cart->setchildrenacommodation[$i]->needbed) ?>
                    </div>
                </div>
            </div>


        </div>
    </div>




<?php } ?>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        validate_children_acommodation();
        function validate_children_acommodation()
        {
            $b_list_selected = '<?php echo $this->cart->person->children[0]->setchildrenacommodation->list_selected ?>';
            $b_list_selected = $b_list_selected.split(",");
            $('.frontTourForm.children_acommodation select.share_room').each(function() {
                $value_selected = $(this).val();
                $(this).find('option').each(function() {
                    $value_option = $(this).val();

                    if ($value_selected != $value_option && $value_option != 0 & $b_list_selected.indexOf($value_option) != -1)
                    {
                        $(this).attr('disabled', 'disabled');
                    }
                });
            });
        }

    });
</script>