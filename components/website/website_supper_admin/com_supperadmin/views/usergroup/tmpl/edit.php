<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_products
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
$doc=JFactory::getDocument();
$scriptId = "script_view_extension_default";
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {



    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);
$doc->addScript(JUri::root().'/media/system/js/jquery.inputmask-3.x/js/jquery.inputmask.js');
$doc->addScript(JUri::root().'/media/system/js/jquery.inputmask-3.x/js/inputmask.js');
?>
<script type="text/javascript">

</script>
<div class="view-extension-default">

    <form action="<?php echo JRoute::_('index.php?option=com_supperadmin&view=usergroups'); ?>" method="post"  name="adminForm" id="adminForm" class="form-validate">

        <div class="form-horizontal">
            <?php
            $tree_node_xml=function($function_callback,$fields, $key_path = '', $indent = '', $form,$level = 0, $maxLevel = 9999){
                if( $level <= $maxLevel && count($fields)){
                    foreach ($fields as $field) {
                        $key_path1 = $key_path != '' ? ($key_path . '.' . $field->name) : $field->name;

                        if (is_array($field->children) && count($field->children) > 0) {
                            $function_callback($function_callback,$field->children,$key_path1);
                        }else {
                            $group = $key_path!=''?explode('.', $key_path):'';
                            $name = strtolower($field->name);
                            $addfieldpath=JPATH_ROOT."/".$field->addfieldpath;
                            if(file_exists($addfieldpath))
                            {
                                $addfieldpath=dirname($field->addfieldpath);
                                $form->addFieldPath(JPATH_ROOT.'/'.$addfieldpath);
                            }
                            $item_field = $form->getField($name, $group);
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="<?php echo $name ?>" class="col-sm-2 control-label"><?php echo $field->label ?></label>
                                        <div class="col-sm-10">
                                            <?php echo $item_field->renderField(array(), false); ?>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <?php

                        }
                    }
                }
            };
            $tree_node_xml($tree_node_xml,$this->item_fields,'','',$this->form);
            ?>
        </div>
        <?php

        $this->list_control_item = $this->get('ListControlItem');
        foreach($this->list_hidden_field_item as $hidden_field_item){
            ?>
            <input type="hidden" value="<?php echo $hidden_field_item->default ?>" name="<?php echo $hidden_field_item->name ?>">
            <?php
        }


        ?>
        <input type="hidden" value="" name="task">

        <?php echo JHtml::_('form.token'); ?>

    </form>
    <?php echo $this->render_toolbar() ?>
</div>