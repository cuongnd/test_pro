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
class JFormFieldgroupparent extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'groupparent';

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
        require_once JPATH_ROOT.'/components/com_users/helpers/groups.php';
        GroupsHelper::create_root_user_group_for_all_website();
        $query = $db->getQuery(true);
        $query->clear()
            ->select('usergroups.id,usergroups.parent_id,usergroups.title as text,user_group_id_website_id.website_id,website.name AS website_name')
            ->from('#__usergroups AS usergroups')
            ->leftJoin('#__user_group_id_website_id AS user_group_id_website_id ON user_group_id_website_id.user_group_id=usergroups.id')
            ->leftJoin('#__website AS website ON website.id=user_group_id_website_id.website_id')
        ;
        $list_all_group_user=$db->setQuery($query)->loadObjectList();
        $children_group_user = array();
        foreach ($list_all_group_user as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $list = @$children_group_user[$pt] ? $children_group_user[$pt] : array();
            array_push($list, $v);
            $children_group_user[$pt] = $list;
        }
        $list_root_group_user = $children_group_user['list_root'];

        $list_group_user=array();
        $option=new stdClass();
        $option->id='';
        $option->text=JText::_('root');
        //$list_product_category[]=$option;
        unset($children_group_user['list_root']);

        $get_list_group_user=function($function_call_back, $group_user=0, &$list_group_user, $children_group_user, $level=0, $max_level=999){
            $level1=$level+1;
            foreach($children_group_user[$group_user->id] as $group_user_1)
            {
                $group_user_1->text=str_repeat('---',$level+1).$group_user_1->text;
                $group_user_1->website_id=$group_user->website_id;
                $list_group_user[]=$group_user_1;

                $function_call_back($function_call_back,$group_user_1,$list_group_user,$children_group_user,$level1,$max_level);
            }
        };
        $current_product_category_id=$this->form->getValue('id');
        foreach($list_root_group_user as $root_group_user)
        {
            $root_group_user->text="$root_group_user->text ( $root_group_user->website_name ) ";
            $list_group_user[]=$root_group_user;
            $get_list_group_user($get_list_group_user, $root_group_user, $list_group_user, $children_group_user);

        }
        $doc=JFactory::getDocument();
        $doc->addLessStyleSheet(JUri::root().'media/system/js/select2-4.0.0/dist/css/select2.css');
        $doc->addScript(JUri::root().'/media/system/js/select2-4.0.0/dist/js/select2.full.js');
        $doc->addScript(JUri::root().'/components/website/website_supper_admin/com_supperadmin/models/fields/jquery.groupparent.js');


        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('website.id,website.name,user_group_id_website_id.user_group_id AS user_group_id')
            ->from('#__website AS website')
            ->leftJoin('#__user_group_id_website_id AS user_group_id_website_id ON user_group_id_website_id.website_id=website.id')
        ;

        $db->setQuery($query);

        $list_website=$db->loadObjectList();




        $element_id='field_select_user_group_parent_'.$this->id;

        $script_id = "script_field_parent_product_category_" . $this->id;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $element_id; ?>').field_groupparent({
                    list_group_user:<?php echo json_encode($list_group_user)?>,
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
        <div id="<?php echo $element_id ?>">
            <div class="row form-group">
                <div class="col-md-12">
                    <select style="width: 100%"   class="list-website" disableChosen="true" >
                        <option value=""><?php echo JText::_('please select website') ?></option>
                        <?php foreach($list_website AS $website){ ?>
                            <option <?php echo ($this->value&&$website->extension_id==$this->value)?'selected':'' ?>  value="<?php echo $website->id ?>"><?php echo $website->name ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-12">
                    <div id="<?php echo $this->id ?>" class="<?php echo $this->id ?>">
                        <select disableChosen="true" class="form-control input-xxlarge input-large-text" type="text" value="<?php echo $this->value ?>" name="<?php echo $this->name ?>" ></select>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $html=ob_get_clean();
        return $html;
    }
}
