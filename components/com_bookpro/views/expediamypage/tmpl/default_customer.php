<?php
defined('_JEXEC') or die('Restricted access');
AImporter::js('master');
$config = AFactory::getConfig();
$user = JFactory::getUser();
$groups = $user->getAuthorisedGroups();
if (!$config->anonymous) {
    $this->customer = AFactory::getCustomer();
}

if (in_array($config->agentUsergroup, $groups)) {
    $this->customer = null;
}
$document = JFactory::getDocument();
$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/jquery.ui.datepicker.js');
?>

<div class="row-fluid form-horizontal">


    <h2 class="headline-bar" style="margin-top: 0"><?php echo JText::_('Who\'s traveling?'); ?></h2>
    <div class="control-group">
        <label class="control-label" for="firstname"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_FIRSTNAME'); ?>
        </label>
        <div class="controls">
            <input class="inputbox required" type="text" id="firstname"
                   name="firstname" id="firstname" size="30" maxlength="50"
                   value="<?php echo $this->customer->firstname ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_FIRSTNAME'); ?>" />
        </div>
    </div>

    <?php if ($config->rsLastname) { ?>
        <div class="control-group">
            <label class="control-label" for="lastname"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_LASTNAME'); ?>
            </label>
            <div class="controls">
                <input class="inputbox required" type="text" name="lastname"
                       id="lastname" size="30" maxlength="50"
                       value="<?php echo $this->customer->lastname ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_LASTNAME'); ?>"/>
            </div>
        </div>
    <?php } ?>
    <?php if ($config->rsAddress) { ?>

        <div class="control-group">
            <label class="control-label" for="address"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_ADDRESS'); ?>
            </label>
            <div class="controls">
                        <input class="inputbox required" type="text" name="address"                       id="address" size="30" maxlength="50"
                           value="<?php echo $this->customer->address ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_ADDRESS'); ?>"/>
            </div>
        </div>


    <?php } ?>
    <div class="control-group">
        <label class="control-label" for="email"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?>
        </label>
        <div class="controls">
            <input class="inputbox required" type="email" name="email" id="email"
                   size="30" maxlength="30"
                   value="<?php echo $this->customer->email ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?>" />
        </div>
    </div>
    <h2 class="headline-bar"><?php echo JText::_('How would you like to pay?'); ?></h2>
    <div class="control-group">
        <label class="control-label" for="address"><?php echo JText::_('Debit/Credit Card Number'); ?>
        </label>
        <div class="controls">
            <input class="inputbox required" type="text" name="address"
                   id="address" size="30" maxlength="50"
                   value="<?php echo $this->customer->address ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_ADDRESS'); ?>"/>
        </div>
    </div>



    <div class="control-group">
        <label class="control-label" for="cardtype"><?php echo JText::_('Card Type'); ?>
        </label>
        <div class="controls">
            <?php echo JHTML::_('select.genericlist', $this->payment_methor['PaymentType'], 'payment_methor', 'id="country" ', 'code', 'name', $default); ?>

        </div>
    </div>



    <div class="control-group">
        <label class="control-label" for="expiration_date"><?php echo JText::_('Expiration Date'); ?>
        </label>
        <div class="controls">

            <input class="inputbox required" type="text" name="expiration_date"
                   id="expiration_date" size="30" maxlength="50"
                   value="<?php echo $this->customer->expiration_date ?>" placeholder="<?php echo JText::_('Expiration Date'); ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="card_identification_number"><?php echo JText::_('Card Identification Number'); ?>
        </label>
        <div class="controls">

            <input class="inputbox required" type="text" name="card_identification_number"
                   id="address" size="30" maxlength="50"
                   value="<?php echo $this->customer->card_identification_number ?>" placeholder="<?php echo JText::_('Card Identification Number'); ?>"/>
        </div>
    </div>



    <div class="control-group">
        <label class="control-label" for="biiling_zip_code"><?php echo JText::_('Billing ZIP Code'); ?>
        </label>
        <div class="controls">
            <input class="inputbox required" type="text" name="biiling_zip_code"
                   id="address" size="30" maxlength="50"
                   value="<?php echo $this->customer->biiling_zip_code ?>" placeholder="<?php echo JText::_('Billing ZIP Code'); ?>"/>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="cardholder_name"><?php echo JText::_('Cardholder Name'); ?>
        </label>
        <div class="controls">
            <input class="inputbox required" type="text" name="cardholder_name"
                   id="address" size="30" maxlength="50"
                   value="<?php echo $this->customer->cardholder_name ?>" placeholder="<?php echo JText::_('Cardholder Name'); ?>"/>
        </div>
    </div>
    <h2 class="headline-bar"><?php echo JText::_('Orther infomation?'); ?></h2>

    <?php if ($config->rsCity) { ?>
        <div class="control-group">
            <label class="control-label" for="city"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_CITY'); ?>
            </label>
            <div class="controls">
                <input class="inputbox required" type="text" name="city"
                       id="city" size="30" maxlength="50"
                       value="<?php echo $this->customer->city ?>"
                       placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_CITY'); ?>"/>
            </div>
        </div>


    <?php } ?>
    <?php if ($config->rsState) { ?>
        <div class="control-group">
            <label class="control-label" for="states"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_STATES'); ?>
            </label>
            <div class="controls">
                <input class="inputbox required" type="text" name="states"
                       id="states" size="30" maxlength="50"
                       value="<?php echo $this->customer->states ?>"
                       placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_STATES'); ?>"/>
            </div>
        </div>

    <?php } ?>

    <?php if ($config->rsZip) { ?>
        <div class="control-group">
            <label class="control-label" for="zip"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_ZIP'); ?>
            </label>
            <div class="controls">
                <input class="inputbox required" type="text" name="zip" id="zip"
                       size="30" maxlength="50" value="<?php echo $this->customer->zip ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_ZIP'); ?>"/>
            </div>
        </div>


    <?php } ?>
    <?php if ($config->rsCountry) { ?>
        <div class="control-group">
            <label class="control-label" for="country_id"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_COUNTRY'); ?>
            </label>
            <div class="controls">
                <?php echo BookProHelper::getCountryList('country_id', 'placeholder="' . JText::_('COM_BOOKPRO_CUSTOMER_COUNTRY') . '"', $this->customer->country_id, '') ?>
            </div>
        </div>

    <?php } ?>

    <?php if ($config->rsMobile) { ?>
        <div class="control-group">
            <label class="control-label" for="mobile"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_MOBILE'); ?>
            </label>
            <div class="controls">
                <input class="inputbox required" type="number" name="mobile"
                       id="mobile" size="30" maxlength="50"
                       value="<?php echo $this->customer->mobile ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_MOBILE'); ?>" />
            </div>
        </div>

    <?php } ?>

    <?php if ($config->rsTelephone) { ?>
        <div class="control-group">
            <label class="control-label" for="telephone"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PHONE'); ?>
            </label>
            <div class="controls">
                <input class="inputbox required" type="number" name="telephone"
                       id="telephone" size="30" maxlength="50"
                       value="<?php echo $this->customer->telephone ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_PHONE'); ?>" />
            </div>
        </div>

    <?php } ?>


    <?php if ($this->customer->cgroup_id) { ?>

        <div class="control-group">
            <label class="control-label" for="telephone"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_GROUP'); ?>
            </label>
            <div class="controls">

                <span class="label label-info">
                    <?php
                    JTable::addIncludePath(JPATH_COMPONENT_FRONT_END . '/tables');
                    $cgroup = JTable::getInstance('cgroup', 'table');
                    $cgroup->load($this->customer->cgroup_id);
                    echo $cgroup->title;
                    ?>
                </span>
                <br/>
                <span class="label label-info">
                    <?php echo JText::sprintf('COM_BOOKPRO_CGROUP_DISCOUNT_TXT', $cgroup->discount) ?>
                </span>

            </div>
        </div>
    <?php }
    ?>
     


</div>

<script>
    jQuery(document).ready(function($) {

        $("#expiration_date").datepicker({
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            minDate: new Date(),
            showOn: "button"

        });



    });
</script>
<style type="text/css">
    .headline-bar {
        margin-top: 18px;
    }

    .headline-bar {
        background-color: #003366;
        border-radius: 4px 4px 0 0;
        color: #FFFFFF;
        font-size: 16px;
        padding: 6px 18px;
        line-height: 30px;
    }
</style>