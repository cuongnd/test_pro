<?php


/**
 * Simple PHP upload class
 *
 * @author Aivis Silins
 */
class utilitiesHelper {


    public static function treeReCurseCategories($id,$oldId,  &$listCatId, &$treeCategory, $maxLevel = 9999, $level = 0)
    {

        if (@$treeCategory[$id] && $level <= $maxLevel)
        {
            foreach ($treeCategory[$id] as $key=> $v)
            {
                $listCatId[$oldId][$v->category_child_id]=$v->category_child_id;
            }
            foreach ($treeCategory[$id] as $key=> $v)
            {
                $new_id = $v->category_child_id;
                utilitiesHelper::treeReCurseCategories($new_id,$oldId, $list, $treeCategory, $maxLevel, $level + 1);
            }
        }
    }


    public static function wrirechildcategory()
    {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('category_parent_id,category_child_id');
        $query->from('#__virtuemart_category_categories');
        $db->setQuery($query);
        $categories = $db->loadObjectList();
        $treeCategory = array();
        if (!empty($categories)) {

            $treeCategory = array();

            // First pass - collect children
            foreach ($categories as $v) {
                $pt = $v->category_parent_id;
                $list = @$treeCategory[$pt] ? $treeCategory[$pt] : array();
                array_push($list, $v);
                $treeCategory[$pt] = $list;
            }

        }

        $query = $db->getQuery(true);
        $query->select('virtuemart_category_id,category_name');
        $query->from('#__virtuemart_categories_en_gb');
        $db->setQuery($query);
        $categories = $db->loadObjectList();
        foreach($categories as $category) {
            $listCatId = array();
            utilitiesHelper::treeReCurseCategories($category->virtuemart_category_id, $category->virtuemart_category_id, $listCatId, $treeCategory);
            $listChildren=implode(',',$listCatId[$category->virtuemart_category_id]);
            $query=$db->getQuery(true);
            $query->update('#__virtuemart_categories_en_gb');
            $query->set('child_categories='.$db->q($listChildren));
            $query->where('virtuemart_category_id='.(int)$category->virtuemart_category_id);
            $db->setQuery($query);
            $db->execute();
        }
        return true;

    }

    function rebuildCategory()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('virtuemart_category_id,category_name,slug');
        $query->from('#__virtuemart_categories_en_gb');
        $query->order('category_name');
        $db->setQuery($query);
        $categories = $db->loadObjectList();
        $listDupCat=array();
        foreach ($categories as $category) {
            $listDupCat[$category->slug][]=$category;

        }
        foreach($listDupCat as $cats)
        {
            if(count($cats)>1)
            {
                for($i=0;$i<count($cats);$i++)
                {
                    if($i>0)
                    {
                        $itemcat=$cats[$i];
                        $query=$db->getQuery(true) ;
                        $query->update('#__virtuemart_categories_en_gb');
                        $query->set('slug='.$db->q($itemcat->slug."_$itemcat->virtuemart_category_id"));
                        $query->where('virtuemart_category_id='.(int)$itemcat->virtuemart_category_id);
                        $db->setQuery($query);
                        $db->execute();

                    }

                }
            }
        }
        echo "<pre>";
        print_r($listDupCat);
        die;
        foreach ($categories as $category) {
            $child_categories=$category->child_categories;
            $child_categories=explode(',',$child_categories);
            $child_categories[]=$category->virtuemart_category_id;
            foreach($child_categories as $key=>$itemCategory)
            {
                if($itemCategory=='')
                    unset($child_categories[$key]);
            }
            $child_categories=implode(',',$child_categories);
            $query=$db->getQuery(true);
            $query->select('virtuemart_category_id, COUNT( 1 ) AS total');
            $query->from('#__virtuemart_product_categories AS pc');
            $query->where('virtuemart_category_id IN('.$child_categories.')');
            $query->group('virtuemart_product_id');
            $db->setQuery($query);
            $listCategoryProduct=$db->loadObjectList();
            $total=0;
            foreach ($listCategoryProduct as $totalProduct) {
                $total+=$totalProduct;
            }
            echo $query->dump();
            echo $category->slug."($category->virtuemart_category_id)-----$total";
            echo "<br/>";
        }


    }

    public function getFilesUploadByProductId($virtuemart_product_id)
  {
      $db=JFactory::getDbo();
      $query=$db->getQuery(true);
      $query->from('#__virtuemart_fileupload as media');
      $query->select('media.*');
      $query->leftJoin('#__virtuemart_product_fileupload AS pm USING(virtuemart_media_id)');
      $query->where('pm.virtuemart_product_id='.$virtuemart_product_id);
      $query->where('media.type="product-sale"');
      $db->setQuery($query);
      $listMedia=$db->loadObjectList();
      return $listMedia;
  }
    public function checkExistsFileTemplate($virtuemart_product_id)
    {
        $modelProduct=VmModel::getModel('product');
        $product=$modelProduct->getProduct($virtuemart_product_id);
        $slug=$product->$product;

        $link_download=$product->link_download;
        $fileName=basename($link_download);
        $storeFileDownload='/images/stories/virtuemart/forSale/filestore/';

        $arrayExtension = array(
            'zip'
        , 'rar'
        , 'doc'
        , 'tgz'
        , 'txt'
        );
        $extension = strtolower ( str_replace ( ".", "", strrchr ( $fileName, "." ) ) );
        if($product->download_free==0&& filter_var($link_download, FILTER_VALIDATE_URL)&&in_array($extension,$arrayExtension))
        {
            if (!class_exists('JUserHelper'))  require_once JPATH_BASE.'/libraries/joomla/user/helper.php';
            $textrandom=JUserHelper::genRandomPassword(8);
            $fileName=$textrandom.$fileName;
            $saveFilenamePath=JPATH_BASE.$storeFileDownload.$fileName;
            $ch = curl_init($link_download);
            $fp = fopen($saveFilenamePath, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
            $db=JFactory::getDbo();
            $query=$db->getQuery(true);
            $query->update('#__virtuemart_products_en_gb');
            $query->set('link_download='.$db->quote($storeFileDownload.$fileName));
            $query->set('download_free=1');
            $query->where('virtuemart_product_id='.$product->virtuemart_product_id);
            $db->setQuery($query);
            $db->execute();
            $link_download=$storeFileDownload.$fileName;
            $product->download_free=1;
            $product->link_download=$link_download;
        }
        return $product;
    }

    public function saveFileUser($virtuemart_product_id)
  {
      $modelProduct=VmModel::getModel('product');
      $product=$modelProduct->getProduct($virtuemart_product_id);
      $link_download=$product->link_download;
      $fileName=basename($link_download);
      $storeFileDownload='/images/stories/virtuemart/forSale/filestore/';

      $arrayExtension = array(
          'zip'
      , 'rar'
      , 'doc'
      , 'tgz'
      , 'txt'
      );
      $extension = strtolower ( str_replace ( ".", "", strrchr ( $fileName, "." ) ) );
      if($product->download_free==0&& filter_var($link_download, FILTER_VALIDATE_URL)&&in_array($extension,$arrayExtension))
      {
          if (!class_exists('JUserHelper'))  require_once JPATH_BASE.'/libraries/joomla/user/helper.php';
          $textrandom=JUserHelper::genRandomPassword(8);
          $fileName=$textrandom.$fileName;
          $saveFilenamePath=JPATH_BASE.$storeFileDownload.$fileName;
          $ch = curl_init($link_download);
          $fp = fopen($saveFilenamePath, 'wb');
          curl_setopt($ch, CURLOPT_FILE, $fp);
          curl_setopt($ch, CURLOPT_HEADER, 0);
          curl_exec($ch);
          curl_close($ch);
          fclose($fp);
          $db=JFactory::getDbo();
          $query=$db->getQuery(true);
          $query->update('#__virtuemart_products_en_gb');
          $query->set('link_download='.$db->quote($storeFileDownload.$fileName));
          $query->set('download_free=1');
          $query->where('virtuemart_product_id='.$product->virtuemart_product_id);
          $db->setQuery($query);
          $db->execute();
          $link_download=$storeFileDownload.$fileName;
          $product->download_free=1;
          $product->link_download=$link_download;
      }
      return $product;
  }

} // end of Upload
