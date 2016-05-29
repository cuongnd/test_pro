<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

JFormHelper::loadFieldClass('text');

/**
 * Form Field class for the Joomla Platform.
 * Provides and input field for e-mail addresses
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/input.email.html#input.email
 * @see         JFormRuleEmail
 * @since       11.1
 */
class JFormFieldChildrenProduct extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'childrenproduct';

    /**
     * Method to get the field input markup for e-mail addresses.
     *
     * @return  string  The field input markup.
     *
     * @since   11.1
     */
    protected function getInput()
    {
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $website=JFactory::getWebsite();
        $product_id=$this->form->getValue('id');
        $query = $db->getQuery(true);
        $query->clear()
            ->select('product.*')
            ->from('#__ecommerce_product AS product')
            ->where('parent_id='.(int)$product_id)

        ;
        $list_product=$db->setQuery($query)->loadObjectList();

        $query = $db->getQuery(true);
        $query->clear()
            ->select('field.*')
            ->from('#__ecommerce_field AS field')
            ->where('type='.$query->q(productsconfig::TYPE_PRODCUT))

        ;
        $list_field=$db->setQuery($query)->loadObjectList();
        $doc=JFactory::getDocument();
        $doc->addLessStyleSheet(JUri::root().'media/system/js/select2-4.0.0/dist/css/select2.css');
        $doc->addScript(JUri::root().'/media/system/js/select2-4.0.0/dist/js/select2.full.js');
        $doc->addScript(JUri::root().'/media/system/js/jquery.utility.js');
        $doc->addScript(JUri::root().'/components/website/website_template5532788/com_products/models/fields/jquery.childrenproduct.js');
        $script_id = "script_field_children_product_" . $this->id;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $this->id; ?>').field_childrenproduct({
                    list_product:<?php echo json_encode($list_product)?>,
                    list_field:<?php echo json_encode($list_field)?>,
                    field:{
                        name:"<?php echo $this->name ?>"
                    }
                });


            });
        </script>
        <?php
        $script = ob_get_clean();
        $script = JUtility::remove_string_javascript($script);
        $doc->addScriptDeclaration($script, "text/javascript", $script_id);
        ob_start();
        ?>
        <div id="<?php echo $this->id ?>" class="<?php echo $this->id ?>">
            <div class="list-children-product">
                <div class="children-product-item">
                    <div class="row">
                        <div class="col-md-12">
                            <h3><?php echo JText::_('Product children') ?> <span class="children-product-item-order">1</span></h3>
                        </div>
                    </div>
                    <div class="base-product-properties form-horizontal ">

                        <?php
                        $class_left='col-md-4';
                        $class_right='col-md-8';
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="children_product_name" class="col-md-4">Children product name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" size="40" value="" data-name="children_product_name" aria-invalid="false">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="children_product_name" class="col-md-4">Children product price</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" size="40" value="" data-name="children_product_name" aria-invalid="false">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="children_product_name" class="col-md-4">Children product name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" size="40" value="" data-name="children_product_name" aria-invalid="false">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="children_product_name" class="col-md-4">Children product name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" size="40" value="" data-name="children_product_name" aria-invalid="false">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="children_product_name" class="col-md-4">Children product name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" size="40" value="" data-name="children_product_name" aria-invalid="false">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="children_product_name" class="col-md-4">Children product name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" size="40" value="" data-name="children_product_name" aria-invalid="false">
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-2">sdfsdfdsfdsfds</div>
                        <div class="col-md-2">sdfsdfdsfdsfds</div>
                        <div class="col-md-2">sdfsdfdsfdsfds</div>
                        <div class="col-md-2">sdfsdfdsfdsfds</div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary pull-right add-new-children-product"><?php echo JText::_('Add new children product') ?></button>
                            <button type="button" class="btn btn-primary pull-right remove-children-product"><?php echo JText::_('Remove children product') ?></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php
        $html=ob_get_clean();
        return $html;
    }
}
