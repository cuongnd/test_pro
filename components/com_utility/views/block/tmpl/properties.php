<?php

JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');
$app=JFactory::getApplication();
$block_id=$app->input->get('block_id',0,'int');
$app->input->set('id',$block_id);
$modelPosition=JModelLegacy::getInstance('Position','UtilityModel');
$item=$modelPosition->getItem();
$form=$modelPosition->getForm();
$xml=$form->getXml();
$config=$xml->config;

function stree_node_xml($xml,$config,$block_id=0,$form,$indent='',$key='',$xpath='',$maxLevel = 9999, $level = 0)
{
    if($indent=='')
    {
        $indent='_'.(string)$block_id;
    }else {
        $indent .= "_{$level}";
    }
    if($level<=$maxLevel)
    {
        ?>
        <div class="panel-group" id="accordion<?php echo $indent ?>" role="tablist" aria-multiselectable="true">
        <?php
        $i=0;
        for($j=0;$j<=1;$j++) {
            if($j==0)
            {
                $list_item=$xml->fieldset;
            }else if($j==1)
            {
                $list_item= $xml->fields;
            }

            foreach ($list_item as $fields) {

                $fieldset = (object)array('name' => '', 'label' => '', 'description' => '');
                foreach ($fields->attributes() as $name => $value) {
                    $fieldset->$name = (string)$value;
                }
                if (is_object($config)) {
                    $config1 = reset($config->xpath('fields[@name="' . $fieldset->name . '"]'));
                }
                if (trim($key) == '') {
                    $key1 = $fieldset->name;
                } else {
                    $key1 = $key . '.' . $fieldset->name;
                }
                $level1 = $level + 1;
                $indent1 = $indent . '_' . $i;
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading<?php echo $indent1 ?>">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion<?php echo $indent1 ?>" href="#collapse<?php echo $indent1 ?>" aria-expanded="true" aria-controls="collapse<?php echo $indent1 ?>">
                                <?php echo $fieldset->label ?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse<?php echo $indent1 ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading<?php echo $indent1 ?>">
                        <div class="panel-body">

                            <?php
                            foreach ($fields as $field) {
                                ?>
                                <div class="form-horizontal property-item">
                                    <?php
                                    $fieldset1 = (object)array(
                                        'name' => '',
                                        'label' => '',
                                        'type' => '',
                                        'description' => ''
                                    );
                                    foreach ($field->attributes() as $name => $value) {
                                        $fieldset->$name = (string)$value;
                                    }
                                    //$field= reset($config->xpath('fields[@name="'.$fieldset->name.'"] field[@name="'.$fieldset1->name.'"]'));
                                    $item = $form->getField($fieldset->name, $key1);
                                    ?>
                                    <div class="form-horizontal property-item">
                                        <?php echo is_object($item) ? $item->renderField(array(), true) : ''; ?>
                                    </div>
                                </div>
                            <?php
                            }
                            if (is_object($config)) {
                                foreach ($config1 as $field) {
                                    ?>
                                    <div class="form-horizontal property-item">
                                        <?php
                                        $fieldset1 = (object)array(
                                            'name' => '',
                                            'label' => '',
                                            'type' => '',
                                            'description' => ''
                                        );
                                        foreach ($field->attributes() as $name => $value) {
                                            $fieldset->$name = (string)$value;
                                        }

                                        $item = $form->getField($fieldset->name, $key1);
                                        ?>
                                        <div class="form-horizontal property-item">
                                            <?php echo is_object($item) ? $item->renderField(array(), true) : ''; ?>
                                        </div>
                                    </div>
                                <?php
                                }
                            }

                            stree_node_xml($fields, $config1, $block_id, $form, $indent1, $key1, $xpath1, $maxLevel, $level1);
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                $i++;
            }
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
    <?php echo stree_node_xml($xml,$config,$block_id,$form); ?>
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



