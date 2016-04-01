<?php
defined('_JEXEC') or  die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/*
* Module Helper
*
* @package VirtueMart
* @copyright (C) 2010 - Patrick Kohl
* @ Email: cyber__fr|at|hotmail.com
*
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* VirtueMart is Free Software.
* VirtueMart comes with absolute no warranty.
*
* www.virtuemart.net
*/
if (!class_exists( 'VmConfig' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'config.php');
VmConfig::loadConfig();
// Load the language file of com_virtuemart.
JFactory::getLanguage()->load('com_virtuemart');
if (!class_exists( 'calculationHelper' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'calculationh.php');
if (!class_exists( 'CurrencyDisplay' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'currencydisplay.php');
if (!class_exists( 'VirtueMartModelVendor' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'models'.DS.'vendor.php');
if (!class_exists( 'VmImage' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'image.php');
if (!class_exists( 'shopFunctionsF' )) require(JPATH_SITE.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'shopfunctionsf.php');
if (!class_exists( 'calculationHelper' )) require(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'cart.php');
if (!class_exists( 'VirtueMartModelProduct' )){
   JLoader::import( 'product', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'models' );
}

class mod_virtuemart_product {

	function addtocart($product) {
            if (!VmConfig::get('use_as_catalog',0)) { ?>
                <div class="addtocart-area">

		<form method="post" class="product" action="index.php">
                    <?php
                    // Product custom_fieldsribbon-3
                    if (!empty($product->customfieldsCart)) {  ?>
                        <div class="product-fields">
                        <?php foreach ($product->customfieldsCart as $field) { ?>

                            <div style="display:inline-block;" class="product-field product-field-type-<?php echo $field->field_type ?>">
                            <span class="product-fields-title" ><b><?php echo $field->custom_title ?></b></span>
                            <?php echo JHTML::tooltip($field->custom_tip, $field->custom_title, 'tooltip.png'); ?>
                            <span class="product-field-display"><?php echo $field->display ?></span>
                            <span class="product-field-desc"><?php echo $field->custom_field_desc ?></span>
                            </div>

                        <?php } ?>
                        </div>
                    <?php } ?>

                    <div class="addtocart-bar">

			<?php
                        // Display the quantity box
                        ?>
			<!-- <label for="quantity<?php echo $product->virtuemart_product_id;?>" class="quantity_box"><?php echo JText::_('COM_VIRTUEMART_CART_QUANTITY'); ?>: </label> -->
			<span class="quantity-box">
			<input type="text" class="quantity-input" name="quantity[]" value="1" />
			</span>
			<span class="quantity-controls">
			<input type="button" class="quantity-controls quantity-plus" />
			<input type="button" class="quantity-controls quantity-minus" />
			</span>


			<?php
                        // Add the button
			$button_lbl = JText::_('COM_VIRTUEMART_CART_ADD_TO');
			$button_cls = ''; //$button_cls = 'addtocart_button';
/*			if (VmConfig::get('show_products_out_of_stock') == '1' && !$product->product_in_stock) {
				$button_lbl = JText::_('COM_VIRTUEMART_CART_NOTIFY');
				$button_cls = 'notify-button';
			} */
// Display the add to cart button
			$stockhandle = VmConfig::get('stockhandle','none');
			if(($stockhandle=='disableit' or $stockhandle=='disableadd') and ($product->product_in_stock - $product->product_ordered)<1){
				$button_lbl = JText::_('COM_VIRTUEMART_CART_NOTIFY');
				$button_cls = 'notify-button';
				$button_name = 'notifycustomer';
			}
			?>
			<?php // Display the add to cart button ?>
			<span class="addtocart-button">
				<input type="submit" name="addtocart"  class="addtocart-button btn btn-primary" value="<?php echo $button_lbl ?>" title="<?php echo $button_lbl ?>" />
			</span>

                        <div class="clear"></div>
                    </div>

                    <input type="hidden" class="pname" value="<?php echo $product->product_name ?>"/>
                    <input type="hidden" name="option" value="com_virtuemart" />
                    <input type="hidden" name="view" value="cart" />
                    <noscript><input type="hidden" name="task" value="add" /></noscript>
                    <input type="hidden" name="virtuemart_product_id[]" value="<?php echo $product->virtuemart_product_id ?>" />
                    <input type="hidden" name="virtuemart_category_id[]" value="<?php echo $product->virtuemart_category_id ?>" />
                </form>
		<div class="clear"></div>
            </div>
        <?php }
     }
    function getimg($url) {
        $headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
        $headers[] = 'Connection: Keep-Alive';
        $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
        $user_agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)';
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        $return = curl_exec($process);
        curl_close($process);
        return $return;
    }
    function downloadImageFromEvanto(&$products)
    {
        $db=JFactory::getDbo();
        $perNumber=10;
        for($i=0;$i<count($products);$i=$i+$perNumber)
        {
            $query=$db->getQuery(true);
            //$query->insert('#__a')->columns('id, title')->values('1,2')->values('3,4');
            $query->insert('#__virtuemart_product_medias');
            $query->columns('virtuemart_product_id,virtuemart_media_id');
            $execute=false;
            for($j=0;$j<$perNumber;$j++)
            {
                $product=$products[$i+$j];
                if($product)
                {
                    $param=$product->param;
                    if(!$param)
                        continue;
                    if($product->virtuemart_media_id)
                        continue;

                    $param=json_decode($param);
                    $data_preview_url=$param->{'data-preview-url'};
                    if(!$data_preview_url)
                        continue;
                    $uri=JUri::getInstance($data_preview_url);
                    $uri->setScheme('http');
                    $data_preview_url=$uri->toString();
                    $fileName=basename($data_preview_url);
                    $extendfile=pathinfo ($fileName);
                    $extendfile=$extendfile['extension'];
                    $listExtendImageSupport=array('jpg','gif','png');

                    if(!in_array($extendfile,$listExtendImageSupport))
                        continue;
                    $savePath='images/stories/virtuemart/product/big_image_product/'.$fileName;
                    $imgurl = $data_preview_url;
                    $imagename= basename($imgurl);
                    if(file_exists(JPATH_ROOT.'/'.$savePath)){continue;}
                    $image = mod_virtuemart_product::getimg($imgurl);
                    file_put_contents(JPATH_ROOT.'/'.$savePath,$image);
                    $query1=$db->getQuery(true);
                    $query1->insert('#__virtuemart_medias');
                    $query1->set('file_url='.$db->q($savePath));
                    $db->setQuery($query1);
                    $db->execute();
                    $media_id=$db->insertid();
                    $query->values($product->virtuemart_product_id.','.$media_id);

                    $execute=true;
                    $products[$i+$j]->file_url=$savePath;
                    $products[$i+$j]->virtuemart_media_id=$media_id;
                }
            }
            if($execute)
            {
                $db->setQuery($query);
                $db->execute();
            }
        }

    }
    public function getProductListing ($group = FALSE, $nbrReturnProducts = FALSE, $withCalc = TRUE, $onlyPublished = TRUE, $single = FALSE, $filterCategory = TRUE, $category_id = 0,$vendor = null)
    {

        $db=JFactory::getDbo();

        $query=$db->getQuery(true);
        $query->select('virtuemart_product_id');
        $query->from('#__virtuemart_products');
        $query->where('published=1');
        $query->order('RAND()');
        $query->group('virtuemart_product_id');
        $db->setQuery($query,0,$nbrReturnProducts);
        $array_virtuemart_product_id=$db->loadColumn();
        $virtuemart_product_ids='138566,'.implode(',',$array_virtuemart_product_id);


        $query=$db->getQuery(true);
        $query->from('#__virtuemart_products_'.VMLANG .' as pl');
        $query->select('pl.virtuemart_product_id,pl.product_name,pl.slug,pl.params');


        $query->leftJoin('#__virtuemart_product_prices as pp using(virtuemart_product_id)');
        $query->select('pp.product_price as prices');


        $query->leftJoin('#__virtuemart_product_medias as pm using(virtuemart_product_id)');
        $query->select('pm.virtuemart_media_id as virtuemart_media_id');


        $query->leftJoin('#__virtuemart_medias m ON m.virtuemart_media_id=pm.virtuemart_media_id');
        $query->select("m.file_url as file_url");
        $query->where('virtuemart_product_id IN('.$virtuemart_product_ids.') ');
        $db->setQuery($query);
        $listProduct=$db->loadObjectList('virtuemart_product_id');
        //static::downloadImageFromEvanto($listProduct);
        $query=$db->getQuery(true);

        $query->select('m.virtuemart_media_id,m.file_url,m.file_url_thumb');
        $query->from('#__virtuemart_medias AS m');
        $query->leftJoin('#__virtuemart_product_medias AS pm USING(virtuemart_media_id)');
        $query->select('pm.virtuemart_product_id as virtuemart_product_id');
        $query->where('pm.virtuemart_product_id IN('.$virtuemart_product_ids.')');
        $db->setQuery($query);
        $listMedia=$db->loadObjectList();
        if(count($listMedia))foreach($listMedia as $media)
        {
            $model_media=new VmMediaHandler($media->virtuemart_media_id);
            if($media->virtuemart_product_id)
                $listProduct[$media->virtuemart_product_id]->images[]=$model_media;
        }
        return $listProduct;

    }
    function addImageFromEnvato(&$products)
    {
        $db=JFactory::getDbo();
        foreach($products as $key=> $product)
        {

            $param=$product->param;
            if(!$param)
            {
                $products[$key]->layout='theme';
            }
            else
            {
                $param=json_decode($param);
                $products[$key]->param=$param;
                $link=$param->link;
                $uri=JUri::getInstance($link);
                $products[$key]->data_preview_url=$param->{'data-preview-url'};
                
                switch ($uri->getHost()) :
                    case 'codecanyon.net':
                        $products[$key]->layout='code';
                        break;
                    case 'videohive.net':
                        $products[$key]->layout='video';
                        $products[$key]->data_video_file_url=$param->{'data-video-file-url'};
                        break;
                    case 'audiojungle.net':
                        $products[$key]->layout='audio';
                        $products[$key]->audio_player=$param->{'audio-player'};
                        break;
                    case 'graphicriver.net':
                        $products[$key]->layout='graphic';
                        break;
                    case 'photodune.net':
                        $products[$key]->layout='photo';
                        break;
                    case '3docean.net':
                        $products[$key]->layout='3d';
                        break;
                    case 'activeden.net':
                        $products[$key]->layout='active';
                        break;
                    case 'themeforest.net':
                    default:
                        $products[$key]->layout='theme';
                        break;
                endswitch;
            }
            $query=$db->getQuery(true);
            //$query->update('#__foo')->set(...);

            $layout=$products[$key]->layout;
            $layout=$layout?$layout:'theme';
            $virtuemart_product_id=$products[$key]->virtuemart_product_id;
            $query->update('#__virtuemart_products');
            $query->set('layout='.$db->q($layout));
            $query->where('virtuemart_product_id='.(int)$virtuemart_product_id);
            $db->setQuery($query);
            $db->execute();

        }
    }
}
