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
class VirtuemartControllerUtilities extends VmController {

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
    public function importproductvatgia()
    {
        $website=JFactory::getWebsite();
        $model_product=VmModel::getModel('product');
        jimport('joomla.filesystem.file');
        $file_config_path=JPATH_ROOT.'/administrator/components/com_virtuemart/views/utilities/tmpl/importcategoryvatgia.txt';
        $file_config=JFile::read($file_config_path);
        $app=JFactory::getApplication();
        $input=$app->input;
        $params = new JRegistry;

        if($file_config!='')
        {
            $params->loadString($file_config);
        }
        $cat_id=$params->get('vatgia.cat_id',1);
        $cat_id++;
        //$cat_id=433;
        $params->set('vatgia.cat_id',$cat_id);
        $vatgia_category_id=$input->get('vatgia_category_id',0,'int');
        JFile::write($file_config_path,$params->toString());
        $link_get_product='http://vatgia.com/ajax_v4/load_type_product_up.php?ajax=1&iCat='.$vatgia_category_id.'&page=1';
        $data=JUtility::getCurl($link_get_product);
        if(strlen($data)>100)
        {
            //parse products
            require_once JPATH_ROOT.'/libraries/simplehtmldom_1_5/simple_html_dom.php';
            $html = str_get_html($data);
            $html_product=array();
            foreach($html->find('div.wrapper') as $wrapper) {

                $html_product[]=$wrapper->outertext;
                $name=$wrapper->find('div.name a',0)->plaintext;
                $image=$wrapper->find('div.picture_main img.picture',0)->src;
                $data=new stdClass();
                $data->virtuemart_product_id=0;
                $data->product_name=$name;
                $data->image_url=$image;
                $params->set('vatgia.html',$wrapper->outertext);
                $data->params=$params->toString();
                $data=(array)$data;
                $model_product->store($data,false);
            }
            ob_start();
            ?>
            <div><b>Link product:</b><?php echo $link_get_product ?></div>
            <?php echo implode('',$html_product) ?>
            <?php
            $html=ob_get_clean();
            echo $html;
            die;
        }else{
            ob_start();
            ?>
            <div><b>Link product:</b><?php echo $link_get_product ?></div>
            <div><?php echo $data ?></div>
            <?php
            $html=ob_get_clean();
            echo $html;
            die;
        }

    }
    public function importcategoryvatgia()
    {


        jimport('joomla.filesystem.file');
        $website=JFactory::getWebsite();
        $categories_file_path=JPATH_ROOT.'/administrator/components/com_virtuemart/views/utilities/tmpl/categories_vatgia.txt';
        $content=JFile::read($categories_file_path);
        $categories=explode("\r",$content);
        $list_category=array();
        $model_category=VmModel::getModel('category');
        for($i=1;$i<=count($categories);$i++)
        {
            $last_item_list_category=end($list_category);
            if(!$last_item_list_category)
            {
                $last_item_list_category=new stdClass();
            }
            $category=$categories[$i-1];
            $item_category=new stdClass();
            $item_category->virtuemart_category_id=0;
            $item_category->category_name=trim($category);
            $item_category->virtuemart_vendor_id=1;
            $item_category->slug=trim($category);
            $item_category->website_id=$website->website_id;

            $category1=explode("\t",$category);
            $total_tab=0;
            foreach($category1 as $key=>$value)
            {
                if(trim($value)=="")
                {
                    $total_tab++;
                }
            }
            $item_category->total_tab=$total_tab;
            if($total_tab>$last_item_list_category->total_tab)
            {
                $item_category->category_parent_id=$last_item_list_category->virtuemart_category_id;
                $item_category->list_parent_id=$last_item_list_category->list_parent_id;
                if(!is_array($item_category->list_parent_id))
                {
                    $item_category->list_parent_id=array();
                }
                $item_category->list_parent_id[]=$item_category->category_parent_id;
            }elseif($total_tab==$last_item_list_category->total_tab){
                $item_category->category_parent_id=$last_item_list_category->category_parent_id;
                $item_category->list_parent_id=$last_item_list_category->list_parent_id;
                if(!is_array($item_category->list_parent_id))
                {
                    $item_category->list_parent_id=$item_category->list_parent_id!=0?array($item_category->list_parent_id):array();
                }
            }elseif($total_tab<$last_item_list_category->total_tab){
                $list_parent_id=$last_item_list_category->list_parent_id;
                //for($j=$last_item_list_category->total_tab-;$j>$total_tab;$j--)
                for($j=$total_tab;$j<$last_item_list_category->total_tab;$j++)
                {
                    array_pop($list_parent_id);
                }

                $item_category->list_parent_id=$list_parent_id;
                $item_category->category_parent_id=end($item_category->list_parent_id);
            }

            $array_item_category=(array)$item_category;
            $model_category->store($array_item_category);
            $item_category=(object)$array_item_category;
            $list_category[$i]=$item_category;
        }

        foreach($list_category as $category)
        {
            echo str_repeat("|----",$category->total_tab)."$category->category_name(id:$category->id,parent_id:$category->parent_id)".'('.implode(',',$category->list_parent_id).')';
            echo "<br/>";
        }

        die;
    }


    function wrirechildcategory()
    {

       require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/utilities.php';
       utilitiesHelper::wrirechildcategory();


       die;
    }
    function rebuildCategory()
    {

       require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/utilities.php';
       utilitiesHelper::rebuildCategory();



       die;
    }


    function dowloadImageFromEnvato($virtuemart_product_id=0)
    {
        if($virtuemart_product_id<72686)
            return;
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('virtuemart_media_id,root_image');
        $query->from('#__virtuemart_medias AS m');
        $query->leftJoin('#__virtuemart_product_medias AS pm USING(virtuemart_media_id)');
        $query->where('pm.virtuemart_product_id='.$virtuemart_product_id);
        $db->setQuery($query);
        $listMedia=$db->loadObjectList();
        if($listMedia)
        {
            foreach($listMedia as $media)
            {
                $virtuemart_media_id=$media->virtuemart_media_id;
                $root_image=$media->root_media;


                $savePath='images/stories/virtuemart/product/big_image_product/';
                $uri=JUri::getInstance('https://0.s3.envato.com/files/101393267/00_banner%20590x300.png');
                $uri->setScheme('http');
                echo "<pre>";
                print_r($root_image);
                $url=$uri->toString();
                $fileName=basename($url);
                echo $fileName;
                echo "<br>";

                //JUtility::saveImageFromUrl($url,JPATH_ROOT.'/'. $savePath);

                $query=$db->getQuery(true);
                $query->update('#__virtuemart_medias');
                $query->set('file_url='.$db->q($savePath));
                $query->where('virtuemart_media_id='.$virtuemart_media_id);
                $db->setQuery($query);
                $db->execute();
            }
        }

    }
    private function  downloadContentFromEnvatoForThisProduct($product)
    {
        //stop if not envato
        $virtuemart_product_id=$product->virtuemart_product_id;
        if($virtuemart_product_id<72686)
            return
                //stop if setbaiviet=1
                $setbaiviet=$product->setbaiviet;
        if((int)$setbaiviet==1)
            return;


        //stop if has content
        $product_desc=$product->product_desc;
        $product_desc=JString::trim($product_desc);
        if($product_desc!='')
            return;

        $param=$product->param;
        $param=json_decode($param);
        $link=$param->link;


        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->update('#__virtuemart_products_en_gb');
        $query->set('setbaiviet=1');
        $query->where('virtuemart_product_id='.$virtuemart_product_id);
        $db->setQuery($query);
        $db->execute();


        //get content
        $html=JUtility::getCurl($link);

        require_once JPATH_ROOT.'/libraries/simplehtmldom_1_5/simple_html_dom.php';
        $html = str_get_html($html);
        $user_html=$html->find('.user-html',0);
        $uri=JUri::getInstance($link);
        //get link demo
        $live_preview=$html->find('.live-preview',0)->href;
        if($live_preview!='')
        {

            $live_preview=$uri->getScheme().'://'.$uri->getHost().$live_preview;
            $live_preview_html=JUtility::getCurl($live_preview);
            $live_preview_html=str_get_html($live_preview_html);
            if($live_preview_html!='')
                $live_preview=$live_preview_html->find('iframe',0)->src;
        }
        //get Images
        $screenshots=$html->find('.screenshots',0)->href;

        if($screenshots!='')
        {
            $screenshots=$uri->getScheme().'://'.$uri->getHost().$screenshots;
            $screenshots=JUtility::getCurl($screenshots);

            $screenshots=str_get_html($screenshots);
            $screenshots=$screenshots->find('a',0)->href;
            $screenshots=JUtility::getCurl($screenshots);
            $screenshots=str_get_html($screenshots);

            if($screenshots!='')
            {
                $screenshots__list=$screenshots->find('.screenshots__list',0);
                if($screenshots__list!='')
                {
                    foreach($screenshots__list->find('a.screenshots__thumbnail img') as $img)
                    {

                        $src_image=$img->src;

                        $src_image=str_replace('.__thumbnail','',$src_image);
                        echo $src_image;
                        echo "<br/>";
                        $fileName=basename($src_image);
                        $savePath='images/stories/virtuemart/product/big_image_product/'.$fileName;
                        ///save image to database
                        $db=JFactory::getDbo();
                        $query=$db->getQuery(true);
                        $query->insert('#__virtuemart_medias');
                        $query->columns('file_url,root_image');
                        $query->values($db->q($savePath).','.$db->q($src_image));
                        $db->setQuery($query);
                        $db->execute();
                        $virtuemart_media_id=$db->insertid();
                        if($virtuemart_media_id)
                        {
                            $query=$db->getQuery(true);
                            $query->insert('#__virtuemart_product_medias');
                            $query->columns('virtuemart_product_id,virtuemart_media_id');
                            $query->values($virtuemart_product_id.','.$virtuemart_media_id);
                            $db->setQuery($query);
                            $db->execute();
                            //$product->virtuemart_media_id[]=$virtuemart_media_id;
                        }

                    }
                }
            }
        }
        //save data
        if($user_html!='')
        {
            $db=JFactory::getDbo();
            $query=$db->getQuery(true);
            $query->update('#__virtuemart_products_en_gb');
            $query->set('product_desc='.$db->q($user_html));
            $query->set('link_demo='.$db->q($live_preview));
            $query->set('html_root='.$db->q($html));
            $query->set('setbaiviet=1');
            $query->where('virtuemart_product_id='.$virtuemart_product_id);
            $db->setQuery($query);
            $db->execute();
            //$product->product_desc=$user_html;
            //$product->link_demo=$live_preview;
            //$product->linkdetail=$link;
        }

    }

    function getcontentevanto()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('virtuemart_product_id,product_desc,param');
        $query->from('#__virtuemart_products_en_gb');
        $query->where('virtuemart_product_id>72686');
        $query->where('setbaiviet=0');
        $query->where('checkin=0');

        $db->setQuery($query,0,4);
        $products=$db->loadObjectList();
        if(count($products))foreach($products as $product)
        {
            $listProductProcess[]=$product->virtuemart_product_id;
        }
        else
        {
            echo "da xong";
            die;
        }
        $listProductProcess=implode(',',$listProductProcess);
        $query=$db->getQuery(true);
        $query->update('#__virtuemart_products_en_gb');
        $query->set('checkin=1');
        $query->where('virtuemart_product_id IN('.$listProductProcess.')');
        $db->setQuery($query);
        $db->execute();
        if(count($products))foreach($products as $product)
        {
            $this->downloadContentFromEnvatoForThisProduct($product);
        }
        echo json_encode($products);
        die;
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
        $html['14464']['html']=<<<HTML
<ul class="first"></ul>


HTML;
        $html['14464']['link']='http://themeforest.net/';
        //------------------------------------------
        //http://codecanyon.net
        $html['21550']['html']=<<<HTML
<ul class="first"></ul>
HTML;
        $html['21550']['link']='http://codecanyon.net';
//------------------------------------------
        //http://videohive.net
        $html['21551']['html']=<<<HTML
<ul class="first"></ul>
HTML;
        $html['21551']['link']='http://videohive.net';
//------------------------------------------
        //http://audiojungle.net
        $html['21553']['html']=<<<HTML
<ul class="first"></ul>
HTML;
        $html['21553']['link']='http://audiojungle.net';
//------------------------------------------
        //http://graphicriver.net
        $html['21555']['html']=<<<HTML
<ul class="first"></ul>
HTML;
        $html['21555']['link']='http://graphicriver.net';
//------------------------------------------
        //http://3docean.net/
        $html['21557']['html']=<<<HTML
<ul class="first"></ul>
HTML;
        $html['21557']['link']='http://3docean.net';
//------------------------------------------
        $html['21558']['html']=<<<HTML
<ul class="first"></ul>
HTML;
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
