<?php
/**
 * @company        :    BriTech Solutions
 * @created by    :    JoomBri Team
 * @contact        :    www.joombri.in, support@joombri.in
 * @created on    :    28 March 2012
 * @file name    :    modules/mod_jblancecategory/tmpl/default.php
 * @copyright   :    Copyright (C) 2012. All rights reserved.
 * @license     :    GNU General Public License version 2 or later
 * @author      :    Faisel
 * @description    :    Entry point for the component (jblance)
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$set_Itemid = intval($params->get('set_itemid', 0));
$Itemid = ($set_Itemid > 0) ? '&Itemid=' . $set_Itemid : '';

$config =& JblanceHelper::getConfig();

$document = &JFactory::getDocument();
$document->addStyleSheet("components/com_jblance/css/$config->theme");
$document->addStyleSheet("components/com_jblance/css/style.css");
$document->addStyleSheet("modules/mod_jblancecategory/css/style.css");
$column=6;
$colun_bootstrap=12/$column;
if (count($rows) > 0) {
    ?>
    <div class="row form-group">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 list-category">
            <?php
            foreach($rows as $row){
                $n = 0;?>
                <div class="row form-group">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <h3><?php echo $row->category; ?></h3>
                        <?php
                        $subs = ModJblanceCategoryHelper::getSubCategories($row->id, 1, '', '');

                        $list_list_item=array_chunk($subs,$column);
                        foreach($list_list_item as $list_item)
                        {
                            ?>
                            <?php foreach($list_item as $item){ ?>
                            <div class="col-xs-6 col-sm-3 col-md-2 col-lg-2 form-group category-item">
                                <?php $link_proj_categ = JRoute::_('index.php?option=com_jblance&view=project&layout=searchproject&id_categ='.$item->id.'&type=category'.$Itemid); ?>
                                <a href="<?php echo $link_proj_categ;?>" class="jbl_subcatlink"><?php echo $item->category; ?><?php if($show_count){ echo '('.$item->thecount.')'; }?></a>
                            </div>
                        <?php } ?>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>

    </div>

<?php } ?>