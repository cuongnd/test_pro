<?php


/**
 * Simple PHP upload class
 *
 * @author Aivis Silins
 */
class productHelper {


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
