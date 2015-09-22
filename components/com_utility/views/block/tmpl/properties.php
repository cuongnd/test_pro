<?php

JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');
$app=JFactory::getApplication();
$block_id=$app->input->get('block_id',0,'int');
$app->input->set('id',$block_id);
$modelPosition=JModelLegacy::getInstance('Position','UtilityModel');
$item=$modelPosition->getItem();

$form=$modelPosition->getForm();

$db=JFactory::getDbo();
require_once JPATH_ROOT.'/components/com_phpmyadmin/tables/updatetable.php';
require_once JPATH_ROOT.'/libraries/joomla/form/field.php';
if($item->ui_path[0]=='/')
{
    $item->ui_path = substr($item->ui_path, 1);
}
$table_control=new JTableUpdateTable($db,'control');


$table_control->load(array("element_path"=>$item->ui_path));
$fields=$table_control->fields;
$fields=base64_decode($fields);
require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
$fields = (array)up_json_decode($fields, false, 512, JSON_PARSE_JAVASCRIPT);
$table_control->load(array("element_path"=>"root_element"));
$main_fields=$table_control->fields;
$main_fields=base64_decode($main_fields);
require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
$main_fields = (array)up_json_decode($main_fields, false, 512, JSON_PARSE_JAVASCRIPT);


function stree_node_xml($fields,$block_id=0,$key_path='',$indent='',$form,$maxLevel = 9999, $level = 0)
{
    if($level<=$maxLevel)
    {

        ?>
        <div class="panel-group" id="accordion<?php echo $indent ?>" role="tablist" aria-multiselectable="true">
        <?php
        $i=0;
        foreach ($fields as $item) {
            $indent1= $indent!=''?$block_id.'_'.$indent.'_'.$i:$block_id.'_'.$i;
            $key_path1=$key_path!=''?($key_path.'.'.$item->name):$item->name;
            if(is_array($item->children)&&count($item->children)>0 ) {
                ?>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading<?php echo $indent1 ?>">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion<?php echo $indent1 ?>" href="#collapse<?php echo $indent1 ?>" aria-expanded="true" aria-controls="collapse<?php echo $indent1 ?>">
                            <?php echo $item->name; ?>
                        </a>
                    </h4>
                </div>
                <div id="collapse<?php echo $indent1 ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading<?php echo $indent1 ?>">
                    <div class="panel-body">
                        <?php stree_node_xml($item->children,  $block_id,$key_path1,$indent1,$form,  $maxLevel, $level++); ?>
                    </div>
                </div>
            </div>
                <?php
            }else{
                ?>

                <div class="form-horizontal property-item">
                    <?php
                    $name=explode('.',$key_path1);

                    if(strtolower($name[0])=='option')
                    {
                        $name=array_reverse($name);
                        array_pop($name);
                        $name=array_reverse($name);
                    }
                    $item->type=$item->type?$item->type:'text';
                    $item_field=JFormHelper::loadFieldType($item->type, true);

                    $value=$form->getData()->get(implode('.',$name),$item->default);

                    $name='['.implode('][',$name).']';
                    $name='jform'.$name;
                    $item_field->__set('id',$name);
                    $item_field->__set('name',$name);
                    $item_field->__set('label',$item->label);
                    $item_field->value=$value;
                    require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
                    $item->config_params=base64_decode($item->config_params);
                    $item->config_params = (array)up_json_decode($item->config_params, false, 512, JSON_PARSE_JAVASCRIPT);
                    $item_field->config_params=$item->config_params;
                    $item_field->element=new stdClass();

                    ?>


                    <?php echo $item_field->renderField(array(),true);  ?>




                </div>

            <?php
            }
            ?>
            <?php
            $i++;
        }
        ?>
        </div>
        <?php

    }

}
$doc=JFactory::getDocument();
ob_start();

?>
<div class="form-horizontal ">
    <div class="form-group">
        <div class="col-xs-5 control-label">
            Filter
        </div>
        <div class="col-xs-7">
            <div class="input-group">
                <input class="form-control" value="" name="filter_label">
            </div>
        </div>
    </div>
</div>

<div class="properties block" data-object-id="<?php echo $block_id ?>">
    <?php echo stree_node_xml($main_fields,$block_id,'','',$form); ?>
    <?php echo stree_node_xml($fields,$block_id,'','',$form); ?>
</div>

<?php

$contents=ob_get_clean();
$tmpl=$app->input->get('tmpl','','string');
if($tmpl=='field')
{
    echo $contents;
    return;
}

$response_array[] = array(
    'key' => '.block-properties .panel-body',
    'contents' => $contents
);
$response_array[] = array(
    'key' => '.block-properties > .panel-heading > .panel-title',
    'contents' => "Properties($item->type) $item->title($item->id)"
);
echo  json_encode($response_array);
?>



