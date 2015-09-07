<?php
/**
 *
 * Main product information
 *
 * @package    VirtueMart
 * @subpackage Product
 * @author Max Milbers
 * @todo Price update calculations
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2012 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product_edit_price.php 6669 2012-11-14 12:16:55Z alatak $
 * http://www.seomoves.org/blog/web-design-development/dynotable-a-jquery-plugin-by-bob-tantlinger-2683/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die(); ?>


<?php
$rowColor = 0;
?>
<table class="table table-striped productPriceTable ">

    <tr class="row<?php echo $rowColor ?> form-horizontal">
        <td>

            <label class="control-label hasTip"
                   title="<?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_COST_TIP'); ?>"
                   style="font-weight: bold;"> <?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_COST') ?> </label>

            <input type="text" class="inputbox input-mini" name="product_price[]" size="5" style="text-align:right;" value="<?php echo $this->calculatedPrices['costPrice']; ?>"/>

            <input type="hidden" name="virtuemart_product_price_id[]" class="virtuemart_product_price_id" value="<?php echo $this->tempProduct->virtuemart_product_price_id; ?>"/>
        </td>
        <td>
            <label style="font-weight: bold;">
				<span class="hasTip" title="<?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_BASE_TIP'); ?>"> <?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_BASE') ?> </span>
                <div class="input-append">
                    <input type="text" readonly class="inputbox readonly input-mini" name="basePrice[]" size="5" value="<?php echo $this->calculatedPrices['basePrice']; ?>"/>
                    <span class="add-on"><?php echo $this->vendor_currency; ?></span>
                </div>
            </label>
        </td>
        <td>
            <?php echo $this->lists['currencies']; ?>
        </td>
    </tr>

    <tr style="display: none">
        <td>
            <?php echo $this->lists['taxrates']; ?>
        </td>
        <td>
			<span class="hasTip" title="<?php echo JText::_('COM_VIRTUEMART_RULES_EFFECTING_TIP') ?>">
				<?php echo JText::_('COM_VIRTUEMART_TAX_EFFECTING') ?>
	         </span></td>

        <td>
            <?php echo $this->taxRules ?>
        </td>

    </tr>
    <tr style="display: none">
        <td>
            <?php echo $this->lists['discounts']; ?>
        </td>
        <td>
			<span class="hasTip" title="<?php echo JText::_('COM_VIRTUEMART_RULES_EFFECTING_TIP') ?>">
				<?php echo JText::_('COM_VIRTUEMART_RULES_EFFECTING') ?>
			</span></td>
        <td>
            <?php echo $this->DBTaxRules; ?>
        </td>
    </tr>
    <?php $rowColor = 1 - $rowColor; ?>
    <tr style="display: none" class="row<?php echo $rowColor ?> form-horizontal">
        <td>
            <?php echo vmJsApi::jDate($this->tempProduct->product_price_publish_up, 'product_price_publish_up[]'); ?>
            <?php echo vmJsApi::jDate($this->tempProduct->product_price_publish_down, 'product_price_publish_down[]'); ?>
        </td>
        <td>
            <?php echo JText::_('COM_VIRTUEMART_PRODUCT_PRICE_QUANTITY_RANGE') ?>
            <span style="white-space : nowrap">
            <input type="text" size="5"
                   style="text-align:right;width:30px" name="price_quantity_start[]"
                   value="<?php echo $this->tempProduct->price_quantity_start ?>"/>

            <input type="text" size="5"
                   style="text-align:right;width:30px" name="price_quantity_end[]"
                   value="<?php echo $this->tempProduct->price_quantity_end ?>"/>
			</span></td>
        <td>
			<span class="hasTip" style="font-weight: bold;"
                  title="<?php echo JText::_('COM_VIRTUEMART_SHOPPER_FORM_GROUP_PRICE_TIP'); ?>">
				<?php echo JText::_('COM_VIRTUEMART_SHOPPER_FORM_GROUP') ?>
			</span>
            <?php echo $this->lists['shoppergroups']; ?>
        </td>
    </tr>
    <?php $rowColor = 1 - $rowColor; ?>
    <tr style="display: none" class="row<?php echo $rowColor ?> form-horizontal">
        <td><label style="font-weight: bold;">
				<span
                    class="hasTip"
                    title="<?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_FINAL_TIP'); ?>">
					<?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_FINAL') ?>
				</span>

                <div class="input-append">
                    <input type="text" class="input-mini" name="salesPrice[]" size="5" style="text-align:right;"
                           value="<?php echo $this->calculatedPrices['salesPriceTemp']; ?>"/>
                    <span class="add-on"><?php echo $this->vendor_currency; ?></span>
                </div>
            </label> <strong>
				<span class="hasTip"
                      title="<?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_CALCULATE_PRICE_FINAL_TIP'); ?>">
					<?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_CALCULATE_PRICE_FINAL'); ?>
				</span> </strong> <label class="btn toggle-hiden btn-small"><i class="icon-unpublish"></i>

                <input type="hidden" name="use_desired_price[]" value="0"/>
            </label>

        </td>
        <td><label style="font-weight: bold;">
				
				<span
                    class="hasTip"
                    title="<?php echo JText::_('COM_VIRTUEMART_PRODUCT_DISCOUNT_OVERRIDE_TIP'); ?>">
					<?php echo JText::_('COM_VIRTUEMART_PRODUCT_DISCOUNT_OVERRIDE') ?>
				</span>

                <div class="input-append">
                    <input type="text" size="5" class="input-mini" style="text-align:right;"
                           name="product_override_price[]"
                           value="<?php echo $this->tempProduct->product_override_price ?>"/>

                    <span class="add-on"><?php echo $this->vendor_currency; ?></span>
                </div>
            </label></td>
        <td>
            <?php
            $options = array(0 => JText::_('COM_VIRTUEMART_DISABLED'), 1 => JText::_('COM_VIRTUEMART_OVERWRITE_FINAL'), -1 => JText::_('COM_VIRTUEMART_OVERWRITE_PRICE_TAX'));

            echo JHTML::_('Select.genericlist', $options, 'override[]', '', 'value', 'text', $this->tempProduct->override, '');
            ?>
        </td>
    </tr>
</table>



