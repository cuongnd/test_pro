<?php
/**
*
* Category controller
*
* @package	VirtueMart
* @subpackage Category
* @author RickG, jseros
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: category.php 6071 2012-06-06 15:33:04Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the controller framework
jimport('joomla.application.component.controller');

if(!class_exists('VmController'))require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'vmcontroller.php');

/**
 * Category Controller
 *
 * @package    VirtueMart
 * @subpackage Category
 * @author jseros, Max Milbers
 */
class VirtuemartControllerUtilities extends JControllerLegacy {

    public function getImageFromTemplateMonter()
    {
        $rootFolder='/images/stories/virtuemart/product/big_image_product/';
        $db=JFactory::getDbo();
        $input=JFactory::getApplication()->input;
        $query=$db->getQuery(true);
        $query->select('virtuemart_media_id,file_url');
        $query->from('#__virtuemart_medias');
        $query->where('downloadok=0');
        $db->setQuery($query,0,1);
        $media=$db->loadObject();
        $pathFile=$rootFolder.basename($media->file_url);

        $this->downloadFile($media->file_url,JPATH_SITE.$pathFile);
        $query=$db->getQuery(true);
        $query->update('#__virtuemart_medias');
        $query->set('file_url='.$db->quote($pathFile));
        $query->set('downloadok=1');
        $query->where('virtuemart_media_id='.$media->virtuemart_media_id);
        $db->setQuery($query);
        $db->execute();
        echo json_encode($media);
        die;

    }
    public function loadImageProduct()
    {
        $input=JFactory::getApplication()->input;
        $listProduct=$input->get('listProduct',array(),'array');
        $listProduct=implode(',',$listProduct);
        $db=JFactory::getDbo();



        $query=$db->getQuery(true);
        $query->from('#__virtuemart_products_'.VMLANG .' as pl');
        $query->select('pl.virtuemart_product_id,pl.product_name,pl.slug,pl.param');


        $query->leftJoin('#__virtuemart_product_prices as pp using(virtuemart_product_id)');
        $query->select('pp.product_price');


        $query->leftJoin('#__virtuemart_product_medias as pm using(virtuemart_product_id)');
        $query->select('pm.virtuemart_media_id as virtuemart_media_id');


        $query->leftJoin('#__virtuemart_medias m ON m.virtuemart_media_id=pm.virtuemart_media_id');
        $query->select("m.file_url as file_url");
        $query->where('virtuemart_product_id IN('.$listProduct.') ');
        $db->setQuery($query);
        $listProduct=$db->loadObjectList();

        require_once JPATH_ROOT.'/modules/mod_virtuemart_product/helper.php';
        mod_virtuemart_product::downloadImageFromEvanto($listProduct);
        header('Content-Type: application/json');
        echo json_encode($listProduct);
        die;
    }
    function setsitemap()
    {
        //with category
        if (!class_exists('VmConfig')) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');
        $vm_category_type='virtuemart-category';
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->from('#__virtuemart_categories_'.VMLANG.' AS cl');
        $query->select('cl.virtuemart_category_id,cl.category_name,cl.slug');
        $query->where('cl.setsitemap=0');
        $db->setQuery($query,0,1000);
        $categories=$db->loadObjectList();

        if(count($categories)){
            foreach ($categories as $row) {
                $link= 'index.php?option=com_virtuemart&amp;view=category&amp;virtuemart_category_id=' . $row->virtuemart_category_id ;
                $link = JRoute::_($link);
                $link=str_replace('test_pro/','',$link);
                $link='http://websitetemplatepro.com'.$link;
                //insert
                $query=$db->getQuery(true);
                $query->insert('#__xmap_links');
                $query->columns('type,object_id,link');
                $query->values($db->q($vm_category_type).','.$row->virtuemart_category_id.','.$db->q($link));
                $db->setQuery($query);
                $db->execute();

                //update
                $query=$db->getQuery(true);
                $query->update('#__virtuemart_categories_'.VMLANG.' AS cl');
                $query->set('setsitemap=1');
                $query->where('virtuemart_category_id='.$row->virtuemart_category_id);
                $db->setQuery($query);
                $db->execute();
            }
        }
        //with product
        $vm_product_type='virtuemart-product';
        $query=$db->getQuery(true);
        $query->from('#__virtuemart_products_'.VMLANG.' AS pl');
        $query->select('pl.virtuemart_product_id AS virtuemart_product_id,pl.product_name AS name,pl.slug AS slug');
        $query->leftJoin('#__virtuemart_product_categories as pc USING(virtuemart_product_id)');
        $query->select('pc.virtuemart_category_id');
        $query->group('pl.virtuemart_product_id');
        $query->where('pl.setsitemap=0');
        $db->setQuery($query,0,1000);
        $products=$db->loadObjectList();
        if(count($products)){
            foreach ($products as $row)
            {
                $link       = 'index.php?option=com_virtuemart&amp;view=productdetails&amp;virtuemart_product_id=' . $row->virtuemart_product_id . '&amp;virtuemart_category_id=' . $row->virtuemart_category_id .'&amp;slug='.$row->slug;
                $link = JRoute::_($link);
                $link=str_replace('test_pro/','',$link);
                $link='http://websitetemplatepro.com'.$link;

                //insert
                $query=$db->getQuery(true);
                $query->insert('#__xmap_links');
                $query->columns('type,object_id,link');
                $query->values($db->q($vm_product_type).','.$row->virtuemart_product_id.','.$db->q($link));
                $db->setQuery($query);
                $db->execute();
                //update
                $query=$db->getQuery(true);
                $query->update('#__virtuemart_products_'.VMLANG.' AS pl');
                $query->set('setsitemap=1');
                $query->where('virtuemart_product_id='.$row->virtuemart_product_id);
                $db->setQuery($query);
                $db->execute();


            }
        }
        echo json_encode($products);
        die;

    }
    public static function getLastLineFromFile($path,$num_lines=10)
    {
        $tail = ''; // content at end of file
        if ($fp = fopen($path, 'r')) {
            $buf_size = 1024;
            $start_read = $filesize = filesize($path); // where to start reading (end of file)
            $i = 0;
            while($start_read > 0 && count(explode("\n", $tail)) < $num_lines+1) {
                $start_read -= $buf_size; // read from last point minus the size we want to read
                if ($start_read < 0) {
                    $read_size = $buf_size + $start_read;
                    $start_read = 0;
                } else {
                    $read_size = $buf_size;
                }
                fseek($fp, $start_read);
                $tail = fread($fp, $read_size) . $tail;
                $i++;
            }
            fclose($fp);
        }
        $lines = array_slice(explode("\n", $tail), -$num_lines, $num_lines);
        return array_reverse($lines);
    }
    function writeXmlSiteMap()
    {
        $googleEnableTotalUrl=49000;
        //get list file enable write
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('id,filename,writing,total_url');
        $query->from('#__xmap_file_link as file_link');
        $query->where('file_link.full=0');
        $query->where('file_link.total_url<'.$googleEnableTotalUrl);
        $db->setQuery($query);
        $listFile=$db->loadObjectList();
        $objectFileXml=new stdClass();
        //get one file enable write
        if(count($listFile))
        {
             foreach($listFile as $file)
             {
                 if($file->writing)
                 {
                     $objectFileXml=$file;
                     break;
                 }
             }
        }
        else
        {
            echo json_encode(array(
                'there is no file select writing xml'
            ));
            exit();
        }
        if(!$objectFileXml->id)
        {
            $objectFileXml=reset($listFile);
        }


        $fileXml=$objectFileXml->filename;

        //set file is writing
        $query=$db->getQuery(true);
        $query->update('#__xmap_file_link');
        $query->set('writing=1');
        $query->where('id='.$objectFileXml->id);
        $db->setQuery($query);
        $db->execute();

        //writing to file xml

        $vm_category_type='virtuemart-category';
        $vm_product_type='virtuemart-product';

        $query=$db->getQuery(true);
        $query->from('#__xmap_links');
        $query->select('id,link');
        $query->where('writedxml=0');
        $query->where('(type LIKE '.$db->q($vm_category_type).' OR type LIKE '.$db->q($vm_product_type).')');
        $db->setQuery($query,0,5000);
        $links=$db->loadObjectList();
        if(count($links)){
            foreach($links as $link)
            {
                $url=" <url>\n
                    <loc>".$link->link."</loc>\n
                </url>";
                file_put_contents($fileXml,$url."\n",FILE_APPEND);
                $query=$db->getQuery(true);
                $query->update('#__xmap_links');
                $query->set('writedxml=1');
                $query->where('id='.$link->id);
                $db->setQuery($query);
                $db->execute();


                //interval total url
                $query=$db->getQuery(true);
                $query->update('#__xmap_file_link');
                $query->set('total_url=total_url+1');
                $query->where('id='.$objectFileXml->id);
                $db->setQuery($query);
                $db->execute();
                $objectFileXml->total_url++;
                if($objectFileXml->total_url>=$googleEnableTotalUrl)
                {
                    echo json_encode(array(
                        'over total url google get next file'
                    ));
                    exit();
                }



                $fileSizeXml=filesize($fileXml);
                $fileSizeXml=JUtility::byteToOtherUnit($fileSizeXml,'MB');
                $fileSizeXml= (int)$fileSizeXml;
                if($fileSizeXml>30)
                {
                    $query=$db->getQuery(true);
                    $query->update('#__xmap_file_link');
                    $query->set('full=1');
                    $query->set('writing=0');
                    $query->where('id='.$objectFileXml->id);
                    $db->setQuery($query);
                    $db->execute();
                    //add footer for xml
                    $urlset='</urlset>';
                    file_put_contents($fileXml,$urlset."\n",FILE_APPEND);

                    echo json_encode(array(
                       'file is full get other file'
                    ));
                    exit();
                }
            }
            echo json_encode($links);
            exit();
        }
        else
        {
           echo json_encode(array(
              'finish writing xml'
           ));
           die;
        }
        exit();

    }
    function addcategoryfromenvato()
    {
        exit('da xet xong het');
        //die('da xong, kiem tra parent, kiem tra link, truyen html');



        //xoa het cai sai
/*
        $nextStep=100;
        $modelCategory=VmModel::getModel('category');
        for($i=39855;$i<=41830;$i=$i+$nextStep)
        {
            $listDel=array();
            for($j=0;$j<$nextStep;$j++)
            {
                $listDel[]=$i+$j;
            }
            $modelCategory->remove($listDel);
        }
        die;
*/

        //--------------------------------------------------------
        //http://themeforest.net/
        $html['14464']['link']='http://themeforest.net/';
        //------------------------------------------
        //http://codecanyon.net
        $html['21550']['link']='http://codecanyon.net';
//------------------------------------------
        //http://videohive.net
        $html['21551']['link']='http://videohive.net';
//------------------------------------------
        //http://audiojungle.net
        $html['21553']['link']='http://audiojungle.net';
//------------------------------------------
        //http://graphicriver.net
        $html['21555']['link']='http://graphicriver.net';
//------------------------------------------
        //http://3docean.net/
        $html['21557']['link']='http://3docean.net';
//------------------------------------------
        $html['21558']['link']='http://activeden.net';


        require_once JPATH_ROOT.'/libraries/simplehtmldom_1_5/simple_html_dom.php';

        //dang set 21550
        $key=JRequest::getVar('setkey',0,'int');
        $item_html=$html[$key];
        $html = str_get_html($item_html['html']);
        $sitename=$item_html['link'];
        static::treeUlLi($html,$key,$sitename);
        die;

    }
    function addenvatotovm()
    {
        //exit('lat nua lam');
        //cho nay xoa san pham
/*        $model_product=VmModel::getModel('product');
        $nextStep=100;
        for($i=72686;$i<=76950;$i=$i+$nextStep)
        {
            $listdel=array();
            for($j=0;$j<$nextStep;$j++)
            {
                $listdel[]=$i+$j;
            }
            $model_product->remove($listdel);
        }

        exit();*/
        //exit('da xong');
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->from('#__virtuemart_categories_en_gb');
        $query->select('virtuemart_category_id,link,max_page');
        $query->where('da_kt=0');
        $query->where('link!=""');
        $query->where('virtuemart_category_id>=41831');
        $query->order('RAND()');
        $db->setQuery($query,0,1);
        $category=$db->loadObject();
        $link=$category->link;

        $virtuemart_category_id=$category->virtuemart_category_id;
        echo $query->dump();
        die;
        $uri=JFactory::getURI($link);

                $ch = curl_init();
        require_once JPATH_ROOT.'/libraries/simplehtmldom_1_5/simple_html_dom.php';
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $result = curl_exec($ch);

        $html = str_get_html($result);

        foreach($html->find('ul.item-list li') as $li)
        {
            $data=array();
            $data['virtuemart_product_id']=0;
            $data['text']= $li->find('div.item-info h3 a',0)->innertext;
            $data['link']= $li->find('div.item-info h3 a',0)->href;
            $price= $li->find('small.price',0)->innertext;
            $data['price']=str_replace('$','',$price);
            $data['data-preview-url']= $li->find('div.thumbnail__inner a img',0)->{'data-preview-url'};
            $data['data-video-file-url']= $li->find('div.thumbnail__inner a img',0)->{'data-video-file-url'};
            $data['audio-player']= $li->find('a.js-audio-player',0)->href;
            $data['categories']=array($virtuemart_category_id);
            $data['product_name']=$data['text'];
            $data['slug']=$data['text'];
            $data['price_monter']=$data['price'];
            $data['product_currency']=144;
            $data['linkdetail']=$data['link'];
            $data['param']=json_encode($data);
            $token=JSession::getFormToken();
            JRequest::setVar($token,1);
            if($data['price'])
            {
                $model_product=VmModel::getModel('product');
                $virtuemart_product_id=$model_product->store($data);
            }
        }
        $page=$uri->getVar('page');
        $page=$page?$page:1;
        $uri->setVar('page',$page);
        $model_category=VmModel::getModel('category');

        if($page==1)
        {
            $max_page=$html->find('span.pagination__summary',0)->innertext;
            $max_page=explode(' ',$max_page);
            $max_page=end($max_page);
            $max_page=$max_page?$max_page:1;
            $data=array();
            $data['virtuemart_category_id']=$virtuemart_category_id;
            $data['max_page']=$max_page;
            $uri->setVar('page',$page+1);
            $data['link']=(string)$uri->toString();
            $query=$db->getQuery(true);
            $query->update('#__virtuemart_categories_en_gb')->set('max_page='.$data['max_page'].',link='.$db->q($data['link']));
            if($max_page==1)
                $query->set('da_kt=1');
            $query->where('virtuemart_category_id='.$virtuemart_category_id);
            $db->setQuery($query);
            $db->execute();
            echo json_encode($category);
            die;
        }
        $max_page=$category->max_page;
        if($page<$max_page)
        {
            $data=array();
            $data['virtuemart_category_id']=$virtuemart_category_id;
            $uri->setVar('page',$page+1);
            $data['link']=(string)$uri->toString();

            $query=$db->getQuery(true);
            $query->update('#__virtuemart_categories_en_gb')->set('link='.$db->q($data['link']));
            $query->where('virtuemart_category_id='.$virtuemart_category_id);
            $db->setQuery($query);
            $db->execute();
            echo json_encode($category);
            die;
        }
        if($page==$max_page)
        {

            $data=array();
            $data['virtuemart_category_id']=$virtuemart_category_id;
            $data['da_kt']=1;

            $query=$db->getQuery(true);
            $query->update('#__virtuemart_categories_en_gb')->set('da_kt=1');
            $query->where('virtuemart_category_id='.$virtuemart_category_id);
            $db->setQuery($query);
            $db->execute();
            echo json_encode($category);
            die;
        }


        die;

        die;
    }

    static  function treeUlLi($html,$category_parent_id,$sitename)
    {

        $input=JFactory::getApplication()->input;
        $db=JFactory::getDbo();
        $firstUl=$html->find('ul',0)->children();
        foreach($firstUl as $li) {

            $text=$li->find('a',0)->innertext;
            $href=$li->find('a',0)->href;
            echo $text;
            echo "<br/>";
            if( $text!='')
            {
                $token=JSession::getFormToken();
                JRequest::setVar($token,1);
                $modelCategory=VmModel::getModel('category');
                $data=array();
                $data['virtuemart_category_id']=0;
                $data['category_name']=$text;
                $data['link']=$sitename.$href;
                $data['slug']=$text;
                $data['category_parent_id']=$category_parent_id;
                $virtuemart_category_id= $modelCategory->store($data);
                $countUl=count($li->find('ul'));
                if($countUl)
                    static::treeUlLi($li,$virtuemart_category_id,$sitename);
            }


        }
    }
    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
    /* creates a compressed zip file */
    function create_zip($files = array(),$destination = '',$overwrite = false) {
        //if the zip file already exists and overwrite is false, return false
        if(file_exists($destination) && !$overwrite) { return false; }
        //vars
        $valid_files = array();
        //if files were passed in...
        if(is_array($files)) {
            //cycle through each file
            foreach($files as $file) {
                //make sure the file exists
                if(file_exists($file)) {
                    $valid_files[] = $file;
                }
            }
        }
        //if we have good files...
        if(count($valid_files)) {
            //create the archive
            $zip = new ZipArchive();
            if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                return false;
            }
            //add the files
            foreach($valid_files as $file) {
                $zip->addFile($file,$file);
            }
            //debug
            //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

            //close the zip -- done!
            $zip->close();

            //check to make sure the file exists
            return file_exists($destination);
        }
        else
        {
            return false;
        }
    }




    function makevendor()
    {
        exit('xong roi');
        $app=JFactory::getApplication();
        $input=$app->input;
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->from('#__users');
        $query->where('id>42');
        $query->select('*');
        $db->setQuery($query);
        $listUser=$db->loadObjectList();
        $modelVendor=VmModel::getModel('vendor');
        $tableVmuser=$modelVendor->getTable('vmusers');
        foreach($listUser as $user)
        {
            JRequest::setVar(JSession::getFormToken(),1);
            $data=array(
                'virtuemart_vendor_id'=>0,
                'user_id'=>$user->id,
                'user_is_vendor'=>1,
                'vendor_name'=>$user->name,
                'vendor_currency'=>144,
                'vendor_accepted_currencies'=>47,
                'vendor_store_name'=>$user->name,
                'address_type'=>'BT',
                'address_type_name'=>'address'
                ,'perms'=>'shopper'
                ,'customer_number'=>md5($user->name)
                ,'slug'=>$user->username
            );
            $vendor_id = $modelVendor->store($data);

            $query=$db->getQuery(true);
            $query->insert('#__virtuemart_vmusers')->columns('virtuemart_user_id,virtuemart_vendor_id,user_is_vendor,customer_number,perms,created_by,modified_by')
                ->values($user->id.','.$vendor_id.',1,'.$db->q(md5($user->name)).',"shopper",'.$user->id.','.$user->id);
            $db->setQuery($query);
            $db->execute();

        }
        die;

    }
    function asigncategory()
    {
        exit('da thuc hien xong roi vui long khong thuc hien lai');
        $input=JFactory::getApplication()->input;
        $db=JFactory::getDbo();

        $iconTexts=array(
            '12762'=>'icon-wordpress'
            ,'12759'=>'icon-bootstrap'
            ,'14458'=>'icon-corporate'
            ,'14457'=>'icon-powerpoint'
            ,'14456'=>'icon-ae'
            ,'14455'=>'icon-swish'
            ,'14454'=>'icon-flash'
            ,'14453'=>'icon-flash'
            ,'14451'=>'icon-loaded7'
            ,'14450'=>'icon-osc'
            ,'14449'=>'icon-woo'
            ,'14448'=>'icon-shopify'
            ,'14447'=>'icon-opencart'
            ,'14446'=>'icon-jigoshop'
            ,'14445'=>'icon-prestahop'
            ,'14444'=>'icon-zencart'
            ,'14443'=>'icon-virtuemart'
            ,'14442'=>'icon-magento'
            ,'14440'=>'icon-moto'
            ,'14439'=>'icon-monster_dark'
            ,'14438'=>'icon-moto'
            ,'14437'=>'icon-joomla'
            ,'14436'=>'icon-drupal'
            ,'14435'=>'icon-wordpress'
            ,'14433'=>'icon-muse'
            ,'14432'=>'icon-email'
            ,'14431'=>'icon-moto'
            ,'14430'=>'icon-facebook'
            ,'14429'=>'icon-psd'
            ,'14428'=>'icon-html5-2'
            ,'14427'=>'icon-wix'
            ,'14426'=>'icon-wix'

        );
        foreach($iconTexts as $key=> $icontText)
        {
            $query=$db->getQuery(true);
            $query->select('*');
            $query->from('#__virtuemart_products_en_gb');
            $query->where('cmstype LIKE '.$db->q("%$icontText%"));
            $db->setQuery($query);
            $listProduct=$db->loadObjectList();
            foreach($listProduct as $product)
            {
                $query=$db->getQuery(true);
                //$query->insert('#__a')->columns('id, title')->values('1,2')->values('3,4');
                $query->insert('#__virtuemart_product_categories')->columns('virtuemart_product_id,virtuemart_category_id')->values("$product->virtuemart_product_id,$key");
                $db->setQuery($query);
                $db->execute();
            }
        }

    }
    function thaydoisangzip()
    {
        exit('da xong');
        $input=JFactory::getApplication()->input;
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*');
        $query->from('#__virtuemart_fileupload');
        $query->where('dachuyen=0');
        $db->setQuery($query,0,1);
        $files_download=$db->loadObjectList();
        foreach($files_download as $key=> $file_download){
            $virtuemart_media_upload_id=$file_download->virtuemart_media_id;

            if(JFile::exists(JPATH_ROOT.DS.$file_download->file_url))
            {

                $file_url=JPATH_ROOT.DS.$file_download->file_url;

                $file_info=pathinfo($file_download->file_url);

                if($file_info['extension']=='rar')
                {
                    $zip_adapter = & JArchive::getAdapter('zip'); // compression type
                    $filesToZip[] = array();
                    $data = JFile::read(JPATH_ROOT.DS.$file_download->file_url);
                    $filesize=@filesize ( JPATH_ROOT.DS.$file_download->file_url );
                    echo $this->formatSizeUnits($filesize);
                    echo "<br/>";
                    echo JPATH_ROOT.DS.$file_download->file_url ;
                    echo "<br/>";
                    echo $this->formatSizeUnits(415236096);
                    echo "<br/>";
                    echo $this->formatSizeUnits(memory_get_usage());
                    echo "<br/>";
                    echo $this->formatSizeUnits(81116538);
                    echo "<br/>";
                   // die;
                    $files_download[$key]->filesize=$this->formatSizeUnits($filesize);
                    $filesToZip[] = array('name' => basename($file_download->file_url), 'data' => $data);
                    $file_url=JPATH_ROOT.DS.$file_info['dirname'].DS.$file_info['filename'].'.zip';
                    //$zipreturn=$zip_adapter->create( $file_url, $filesToZip, array() );
                    $zipreturn=$this->create_zip( array(JPATH_ROOT.DS.$file_download->file_url), $file_url,true );
                    if ($zipreturn) {
                        $query=$db->getQuery(true);
                        $query->update('#__virtuemart_fileupload');
                        $query->set('file_url='.$db->q($file_info['dirname'].DS.$file_info['filename'].'.zip'));
                        $query->set('file_title='.$db->q($file_info['filename'].'.zip'));
                        $query->set('file_description='.$db->q($file_info['filename'].'.zip'));
                        $query->set('dachuyen=1');
                        $query->where('virtuemart_media_id='.$virtuemart_media_upload_id);
                        $db->setQuery($query);
                        if($db->execute())
                        {
                            JFile::delete(JPATH_SITE.DS.$file_download->file_url);
                        }


                    }
                }elseif($file_info['extension']=='zip')
                {
                    $query=$db->getQuery(true);
                    $query->update('#__virtuemart_fileupload');
                    $query->set('dachuyen=1');
                    $query->where('virtuemart_media_id='.$virtuemart_media_upload_id);
                    $db->setQuery($query);
                    $db->execute();
                }

            }else
            {
                $query=$db->getQuery(true);
                $query->delete('#__virtuemart_fileupload');
                $query->where('virtuemart_media_id='.$virtuemart_media_upload_id);
                $db->setQuery($query);
                $db->execute();

                $query=$db->getQuery(true);
                $query->delete('#__virtuemart_product_fileupload');
                $query->where('virtuemart_media_id='.$virtuemart_media_upload_id);
                $db->setQuery($query);
                $db->execute();

            }

        }
        echo json_encode($files_download);
        die;
    }



    function  setbaiviet()
    {
        exit('da xet xong');
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('virtuemart_product_id,product_name,slug');
        $query->from('#__virtuemart_products_en_gb');
        $query->where('price_monter<>0');
        $query->where('setbaiviet=0');
        $db->setQuery($query,0,30);
        $listProduct = $db->loadObjectList();

        $model_product=VmModel::getModel('product');
        $xrefTable = $model_product->getTable ('product_medias');
        require_once JPATH_SITE.'/libraries/simplehtmldom_1_5/simple_html_dom.php';

        $query=$db->getQuery(true);
        $query->select('virtuemart_product_id,product_name,product_desc,slug');
        $query->from('#__virtuemart_products_en_gb');
        $query->where('slug LIKE "%ja_%"');
        $query->where('virtuemart_product_id >72568');
        $query->where('product_desc !=""');
        $db->setQuery($query);
        $listJa=$db->loadObjectList();

        foreach($listJa as $key=> $itemJa)
        {
            $html = str_get_html($itemJa->product_desc);

            foreach($html->find('img') as $e)
                $e->src = '%s';
            $listJa[$key]->product_desc=$html;

        }

        for($i=0;$i<count($listProduct);$i++)
        {
            $product=$listProduct[$i];
            $product->virtuemart_media_id = $xrefTable->load ((int)$product->virtuemart_product_id);
            $model_product->addImages($product);
            $listImage=array();
            foreach($product->images as $image)
            {
                $listImage[]=$image->file_url;
            }
            $a_listImage=array();
            $interval=0;
            for($j=0;$j<10;$j++)
            {
                if($listImage[$j])
                {
                    $a_listImage[$j]=$listImage[$j];
                }else
                {
                    if($interval==count($listImage))
                        $interval=0;
                    $a_listImage[$j]=$listImage[$interval++];
                }
            }

            $html=$listJa[array_rand($listJa)]->product_desc;
            $html=JText::sprintf($html,$a_listImage[0],$a_listImage[1],$a_listImage[2],$a_listImage[3],$a_listImage[4],$a_listImage[5],$a_listImage[6],$a_listImage[7],$a_listImage[8]);

            $query=$db->getQuery(true);
            //$query->update('#__foo')->set(...);
            $query->update('#__virtuemart_products_en_gb');
            $query->set('product_desc='.$db->q($html));
            $query->set('setbaiviet=1');
            $query->where('virtuemart_product_id='.(int)$product->virtuemart_product_id);
            $db->setQuery($query);
            echo $query->dump();
            $db->execute();

        }
        echo json_encode($listProduct);
        die;
    }

    function updateFileDownload()
    {
        die('completed');
        $array_slugs=array();
        $i=0;
        $folders=JFolder::folders(JPATH_SITE.'/media/stories/templates');
        foreach($folders as $folder)
        {
            $files=JFolder::files(JPATH_SITE.'/media/stories/templates/'.$folder);
            foreach($files as $file)
            {
                if(JFile::getExt($file)=='rar')
                {

                    $slug=pathinfo($file);
                    $slug=$slug['filename'];
                    $firstSpace=strpos($slug,' ');
                    if($firstSpace)
                    {
                        $array_slugs[$i]['slug']=substr($slug,0,$firstSpace);
                    }
                    else
                    {
                        $array_slugs[$i]['slug']=$slug;
                    }
                    $array_slugs[$i]['link']='media/stories/templates/'.$folder.'/'.$file;
                    $i++;
                }
            }
        }
        $db=JFactory::getDbo();
        foreach($array_slugs as $item)
        {
            $query=$db->getQuery(true);
            //$query->insert('#__a')->set('id = 1');
            //$query->insert('#__a')->columns('id, title')->values('1,2')->values('3,4');
            $query->insert('#__virtuemart_fileupload')->columns('type,virtuemart_vendor_id,file_title,file_description,file_mimetype,file_url,created_by');
            $query->values('"product-sale",0,'.$db->q(basename($item['link'])).','.$db->q(basename($item['link'])).',"application/zip",'.$db->q($item['link']).',42');
            $db->setQuery($query);
            $db->execute();
            $virtuemart_media_insertId=$db->insertid();
            $query=$db->getQuery(true);
            $query->select('virtuemart_product_id');
            $query->from('#__virtuemart_products_en_gb');
            $query->where('slug='.$db->q($item['slug']));
            $db->setQuery($query);
            $virtuemart_product_id=$db->loadResult();
            if($virtuemart_product_id)
            {
                $query=$db->getQuery(true);
                $query->insert('#__virtuemart_product_fileupload')->columns('virtuemart_product_id,virtuemart_media_id')->values($virtuemart_product_id.','.$virtuemart_media_insertId);
                $db->setQuery($query);
                $db->execute();
            }
        }
        echo "<pre>";
        print_r($array_slugs);
        die;
    }

    public function setNullField_file_url_thumb()
    {
        die;
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('virtuemart_media_id,file_url_thumb');
        $query->from('#__virtuemart_medias');
        $db->setQuery($query);
        $listMedia=$db->loadObjectList();
        $nextStep=100;
        for($i=0;$i<count($listMedia);$i=$i+$nextStep)
        {
            $listNull_file_url_thumb=array();
            for($j=0;$j<$nextStep;$j++)
            {
                $media=$listMedia[$i+$j];
                if(!JFile::exists(JPATH_SITE.'/'.$media->file_url_thumb))
                {
                    $listNull_file_url_thumb[]=$media->virtuemart_media_id;
                }
            }
            if(count($listNull_file_url_thumb))
            {
                $file_url_thumbs=implode(',',$listNull_file_url_thumb);

                $query=$db->getQuery(true);
                $query->update('#__virtuemart_medias');
                $query->set('file_url_thumb=null');
                $query->where('virtuemart_media_id IN('.$file_url_thumbs.')');
                $db->setQuery($query);
                $db->execute();

            }


        }
        die;
    }
    private function downloadFile ($url, $path) {

        $ch = curl_init($url);
        $fp = fopen($path, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }

}
