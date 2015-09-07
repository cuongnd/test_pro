<div class="right_level">
    <span class="right_level1" style="text-transform: uppercase"><?php echo JText::_('PASSENGER') ?>:&nbsp;<?php echo (count($this->cart->person->adult) + count($this->cart->person->teenner)+count($this->cart->person->children)) ?></span> <span style="color:#9A0000"><?php echo JText::_('Pers') ?></span>
    <ul class="passengers">
        <?php
        $strsold = array(
            "adult" => JText::_('COM_BOOKPRO_ADULT'),
            "teenner" => JText::_('COM_BOOKPRO_TEENNER'),
            "children1" => JText::_('COM_BOOKPRO_CHILDREN1'),
            "children2" => JText::_('COM_BOOKPRO_CHILDREN2'),
            "children3" => JText::_('COM_BOOKPRO_CHILDREN3')
        );

        $person = $this->cart->person;
        $a_key_persons = array(
            'adult' => 'adult',
            'teenner' => 'teenner',
            'children' => 'children'
        );
        $next = 0;
        $total = 0;
        foreach ($person as $key_person => $a_person) {

            if (!in_array($key_person, $a_key_persons, true)) {
                continue;
            }

            for ($i = 0; $i < count($a_person); $i++) {
                $next++;
                $passenger = $a_person[$i];
                $yearold = BookProHelper::getyearold(JFactory::getDate($passenger->birthday)->format('Y/m/d'));
                if ($yearold < 2) {
                    $persondata = 'children1';
                } else if ($yearold >= 2 && $yearold <= 5) {
                    $persondata = 'children2';
                } else if ($yearold >= 6 && $yearold <= 11) {
                    $persondata = 'children3';
                } else if ($yearold >= 12 && $yearold <= 17) {
                    $persondata = 'teenner';
                } else {
                    $persondata = 'adult';
                }

                $price = $passenger->priceroomselect + $passenger->setchildrenacommodation->price;
                $total+=$price;
                $fullname = $passenger->firstname . ' ' . $passenger->lastname . ' (' . $strsold[$persondata] . ')';
                ?>

                <li class=" right_level11">
                    <?php echo $next . '. ' . $fullname ?>

                </li>
                <?php
            };
        }
        ?>
    </ul>
    <div style="text-transform: uppercase; color: #9A0000; text-align: right">
        <?php echo JText::_('COM_BOOKPRO_SERVICE_FEE') ?>: <?php echo CurrencyHelper::formatprice($total); ?>
    </div>
</div>
<div class="border-bottom1"></div>   