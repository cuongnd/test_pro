<?php
JHtml::_('behavior.framework');
JHtml::_('behavior.calendar');
AImporter::helper('bookpro');
$config = AFactory::getConfig();
$adult = JRequest::getInt('adult');
?>
<fieldset>
    <legend>
        <span><?php echo JText::_('COM_BOOKPRO_PASSENGER_INFO') ?> </span>
    </legend>
    <div class="passenger_list">
        <div id="passenger" >				
            <div class="form-inline">
                <?php echo JHtml::_('select.genericlist', BookProHelper::getGender(), 'pGender[]', 'class="input-small"', 'value', 'text', 1) ?>
                <?php if ($config->psFirstname) { ?>
                    <input  type="text" class="input-medium" name="pFirstname[]" placeholder="<?php echo JText::_('COM_BOOKPRO_PASSENGER_FIRSTNAME') ?>" />
                <?php } ?>

                <?php if ($config->psLastname) { ?>
                    <input   type="text" class="input-medium"  name="pMiddlename[]" placeholder="<?php echo JText::_('COM_BOOKPRO_PASSENGER_LASTNAME') ?>" />
                <?php } ?>

                <?php if ($config->psBirthday) { ?>

                    <?php //echo JHtml::_('calendar','', 'pBirthday[]','pBirthday', '%d-%m-%Y' ,'class="brithday input-medium" placeholder="'.JText::_('COM_BOOKPRO_PASSENGER_BIRTHDAY').'"') ; ?> 
                <?php } ?>

                <?php if ($config->psPassport) { ?>
                    <input  type="text" name="pPassport[]" size="12" placeholder="<?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT') ?>" />
                <?php } ?>
                <?php if ($config->psPassportValid) { ?>

                    <?php //echo JHtml::_('calendar','', 'pPassportValid[]', 'pPassportValid[]', '%d-%m-%Y' , array('readonly'=>'true','class'=>'date','placeholder'=>JText::_('COM_BOOKPRO_PASSENGER_PPVALID'))); ?> 

                <?php } ?>
                <?php
                if ($config->psCountry) {
                    echo BookProHelper::getCountryList('pCountry[]', 'placeholder="' . JText::_('COM_BOOKPRO_PASSENGER_COUNTRY') . '"');
                }
                ?>
                <?php if ($config->psGroup) { ?>
                    <?php echo BookProHelper::getPassengerGroup('age[]') ?>
                <?php } ?>

            </div>
        </div>

    </div>

</fieldset>




