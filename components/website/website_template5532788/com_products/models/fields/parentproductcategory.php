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
class JFormFieldParentProductCategory extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'parentproductcategory';

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
        $query = $db->getQuery(true);
        $query->clear()
            ->select('product_category.id,product_category.parent_id,product_category.title as text,product_category.website_id')
            ->from('#__ecommerce_product_category AS product_category')
        ;
        $list_all_product_category=$db->setQuery($query)->loadObjectList();
        $children_product_category = array();
        foreach ($list_all_product_category as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $list = @$children_product_category[$pt] ? $children_product_category[$pt] : array();
            array_push($list, $v);
            $children_product_category[$pt] = $list;
        }
        $list_root_product_category = $children_product_category['list_root'];
        $list_product_category=array();
        $option=new stdClass();
        $option->id='';
        $option->text=JText::_('root');
        //$list_product_category[]=$option;
        unset($children_product_category['list_root']);

        $get_list_product_category=function($function_call_back, $product_category_id=0, $current_product_category_id,&$list_product_category, $children_product_category, $level=1, $max_level=999){
            $level1=$level+1;
            foreach($children_product_category[$product_category_id] as $product_category)
            {

                $product_category_id1=$product_category->id;
                if($product_category_id1==$current_product_category_id)
                {
                    continue;
                }
                $product_category->text=str_repeat('---',$level).$product_category->text;
                $list_product_category[]=$product_category;

                $function_call_back($function_call_back,$product_category_id1, $current_product_category_id,$list_product_category,$children_product_category,$level1,$max_level);
            }
        };
        $current_product_category_id=$this->form->getValue('id');
        foreach($list_root_product_category as $root_category)
        {
            if($root_category->website_id==$website->website_id)
            {
                if($current_product_category_id!=$root_category->id) {
                    $list_product_category[] = $root_category;
                    $get_list_product_category($get_list_product_category, $root_category->id, $current_product_category_id, $list_product_category, $children_product_category);
                }
            }

        }
        $doc=JFactory::getDocument();
        $doc->addLessStyleSheet(JUri::root().'media/system/js/select2-4.0.0/dist/css/select2.css');
        $doc->addScript(JUri::root().'/media/system/js/select2-4.0.0/dist/js/select2.full.js');
        $doc->addScript(JUri::root().'/components/website/website_template5532788/com_products/models/fields/jquery.parentproductcategory.js');
        $script_id = "script_field_parent_product_category_" . $this->id;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $this->id; ?>').field_parentproductcategory({
                    list_product_category:<?php echo json_encode($list_product_category)?>,
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
            <input class="form-control input-xxlarge input-large-text" type="text" value="<?php echo $this->value ?>" name="<?php echo $this->name ?>" >
        </div>
        <?php
        $html=ob_get_clean();
        return $html;
    }
}
