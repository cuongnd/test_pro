<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$document=JFactory::getDocument();
JHtml::_('bootstrap.framework');
JHtmlBehavior::formvalidation();
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
$document->addScript(JURI::base() . '/media/system/js/select2.js');
$document->addScript(JURI::base() . '/media/system/js/bootstrap-button.js');
$document->addScript(JURI::base() . '/media/system/js/bootstrap-collapse.js');
$document->addScript(JURI::base() . '/modules/mod_virtuemart_search/assets/js/script.js');
$document->addStyleSheet(JURI::base() . '/media/system/css/select2.css');
$document->addStyleSheet(JURI::base() . '/media/system/css/select2-bootstrap.css');
$document->addScript(JUri::root().'/media/system/js/ion.rangeSlider-1.9.1/js/ion-rangeSlider/ion.rangeSlider.min.js');
$document->addStyleSheet(JUri::root().'/media/system/js/ion.rangeSlider-1.9.1/css/ion.rangeSlider.css');
$document->addStyleSheet(JUri::root().'/modules/mod_virtuemart_search/assets/css/style.css');

$html='';
$category_name='';
$itemid_extension=(int)$params->get('Itemid_extension', 0);
$itemid_template=(int)$params->get('Itemid_template', 0);
$js=<<<script
var itemid_extension={$itemid_extension};
var itemid_template={$itemid_template};
script;
$document->addScriptDeclaration($js);
?>
<h3><?php echo JText::_('Search') ?></h3>
<form class="form-horizontal form-validate"  role="form" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=category&search=true&limitstart=0&virtuemart_category_id='.$category_id ); ?>" method="get">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="category" class="col-sm-5 control-label"><?php echo JText::_('Keyword') ?></label>
                <div class="col-sm-7">
                    <input type="text"  value="<?php echo $cart->array_search->keyword ?>" placeholder="keyword" name="keyword">
                </div>
            </div>

            <div class="form-group">
                <label for="type" class="col-sm-5 control-label"><?php echo JText::_('Type') ?></label>
                <div class="col-sm-7">
                    <div class="btn-group type" data-toggle="buttons">
                        <label class="btn btn-primary <?php echo $cart->array_search->type==1?' active ':'' ?>  type">
                            <input type="radio" value="1" name="type" id="option1" <?php echo $cart->array_search->type==1?' checked ':'' ?>><?php echo JText::_('Extension') ?>
                        </label>
                        <label class="btn btn-primary <?php echo $cart->array_search->type==0?' active ':'' ?> type">
                            <input type="radio" value="0" name="type" id="option2" <?php echo $cart->array_search->type==0?' checked ':'' ?>><?php echo JText::_('Template') ?>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo JText::_('Download type') ?></label>
                <div class="col-sm-7">
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-primary <?php echo $cart->array_search->download_free==1?' active ':'' ?> ">
                            <input type="radio" value="1" name="download_free" id="option1" <?php echo $cart->array_search->download_free==1?' checked ':'' ?>><?php echo JText::_('Free') ?>
                        </label>
                        <label class="btn btn-primary <?php echo $cart->array_search->download_free==2?' active ':'' ?>">
                            <input type="radio" value="2" name="download_free" id="option2" <?php echo $cart->array_search->download_free==2?' checked ':'' ?>><?php echo JText::_('All') ?>
                        </label>
                        <label class="btn btn-primary <?php echo $cart->array_search->download_free==0?' active ':'' ?>">
                            <input type="radio" value="0" name="download_free" id="option3" <?php echo $cart->array_search->download_free==0?' checked ':'' ?>><?php echo JText::_('Paid') ?>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="category" class="col-sm-5 control-label"><?php echo JText::_('Price') ?></label>
                <div class="col-sm-7">
                    <div class="l-become-author__rates-slider">
                        <input id="price-rates-slider" class="is-hidden" type="hidden" value="<?php echo $cart->array_search->price_rates_slider ?>"  name="price_rates_slider" style="display: none;">                </div>
                </div>
            </div>


        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="category" class="col-sm-5 control-label"><?php echo JText::_('Template Category') ?></label>
                <div class="col-sm-7">
                    <select class="required"  id="category_id" name="category_id">
                        <option value="0"><?php echo JText::_('Select template category') ?></option>
                        <?php
                        $html='';
                        $category_id=(int)$params->get('category_id', 0);
                        $category_selected=(int)$cart->array_search->category_id;
                        echo mod_virtuemartSearchHelper::treeReCurseCategories($category_id,$html,$categoryTree,$category_selected);
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="category" class="col-sm-5 control-label"><?php echo JText::_('Extension category') ?></label>
                <div class="col-sm-7">
                    <select class="required"  id="category_extension_id" name="category_extension_id">
                        <option value="0"><?php echo JText::_('Select category extension') ?></option>
                        <?php
                        $html='';
                        $category_id=(int)$params->get('category_extension_id', 0);
                        $category_selected=(int)$cart->array_search->category_extension_id;
                        echo mod_virtuemartSearchHelper::treeReCurseCategories($category_id,$html,$categoryTree,$category_selected);
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="webtemplate" class="col-sm-5 control-label"><?php echo JText::_('Website template') ?></label>
                <div class="col-sm-7">
                    <select  id="website_template_id" name="website_template_id">
                        <option value="0"><?php echo JText::_('Select website template') ?></option>
                        <?php
                        $html='';
                        $category_id=(int)$params->get('website_template_id', 0);
                        $category_selected=$cart->array_search->website_template_id;
                        echo mod_virtuemartSearchHelper::treeReCurseCategories($category_id,$html,$categoryTree,$category_selected);
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="cmstemplate" class="col-sm-5 control-label"><?php echo JText::_('Cms template') ?></label>
                <div class="col-sm-7">
                    <select  id="cms_template_id" name="cms_template_id">
                        <option value="0"><?php echo JText::_('Select cms template') ?></option>
                        <?php
                        $html='';
                        $category_id=(int)$params->get('cms_template_id', 0);
                        $category_selected=$cart->array_search->cms_template_id;
                        echo mod_virtuemartSearchHelper::treeReCurseCategories($category_id,$html,$categoryTree,$category_selected);
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="e_commercetemplates" class="col-sm-5 control-label"><?php echo jtext::_('e-commerce templates') ?></label>
                <div class="col-sm-7">
                    <select  id="e_commerce_templates_id" name="e_commerce_templates_id">
                        <option value="0"><?php echo JText::_('Select e-commerce template') ?></option>
                        <?php
                        $html='';
                        $category_id=(int)$params->get('e_commerce_templates_id', 0);
                        $category_selected=$cart->array_search->e_commerce_templates_id;
                        echo mod_virtuemartSearchHelper::treeReCurseCategories($category_id,$html,$categoryTree,$category_selected);
                        ?>
                    </select>
                </div>
            </div>


        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="flash_media" class="col-sm-5 control-label"><?php echo JText::_('Flash & Media') ?></label>
                <div class="col-sm-7">
                    <select   id="flash_media_id" name="flash_media_id">
                        <option value="0"><?php echo JText::_('Select flash media') ?></option>
                        <?php
                        $html='';
                        $category_id=(int)$params->get('flash_media_id', 0);
                        $category_selected=$cart->array_search->flash_media_id;
                        echo mod_virtuemartSearchHelper::treeReCurseCategories($category_id,$html,$categoryTree,$category_selected);
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="author" class="col-sm-5 control-label"><?php echo JText::_('Author') ?></label>
                <div class="col-sm-7">
                    <?php
                    $vendor_id=(int)$cart->array_search->vendor_id;
                    ?>
                    <select  id="vendor_id" name="vendor_id">
                        <option value="0"><?php echo JText::_('Select author') ?></option>
                        <?php foreach($vendors as $vendor){ ?>
                        <option <?php echo $vendor_id==$vendor->virtuemart_vendor_id?'selected':''; ?> value="<?php echo $vendor->virtuemart_vendor_id ?>"><?php echo $vendor->vendor_name ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="sorting" class="col-sm-5 control-label"><?php echo JText::_('Sorting') ?></label>
                <div class="col-sm-7">
                    <select  id="sorting" name="sorting">
                        <option <?php echo $cart->array_search->sorting=='price_lh'?' selected ':'' ?> value="price_lh"><?php echo JText::_('Price (Low to High)') ?></option>
                        <option <?php echo $cart->array_search->sorting=='price_hl'?' selected ':'' ?> value="price_hl"><?php echo JText::_('Price (High to Low)') ?></option>
                        <option <?php echo $cart->array_search->sorting=='alpha'?' selected ':'' ?> value="alpha"><?php echo JText::_('Alpha') ?></option>
                        <option <?php echo $cart->array_search->sorting=='popular'?' selected ':'' ?> value="popular"><?php echo JText::_('Popular') ?></option>
                        <option <?php echo $cart->array_search->sorting=='stars_lh'?' selected ':'' ?> value="stars_lh"><?php echo JText::_('Star Rating (Low to High)') ?></option>
                        <option <?php echo $cart->array_search->sorting=='stars_hl'?' selected ':'' ?> value="stars_hl"><?php echo JText::_('Star Rating (High to Low)') ?></option>
                    </select>
                </div>
            </div>

        </div>

    </div>

    <div class="row" style="text-align: right">
        <button type="submit" class="btn btn-primary"><?php echo JText::_('Search') ?></button>
    </div>


    <input type="hidden" name="limitstart" value="0" />
    <input type="hidden" name="option" value="com_virtuemart" />
    <input type="hidden" name="view" value="category" />
    <input type="hidden" name="from_search" value="1" />
    <input type="hidden" name="Itemid" value="<?php echo (int)$params->get('Itemid_extension', 0) ?>" />
</form>


<!--BEGIN Search Box -->

<!-- End Search Box -->