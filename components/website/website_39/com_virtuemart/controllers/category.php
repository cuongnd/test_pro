<?php
/**
*
* Description
*
* @package	VirtueMart
* @subpackage
* @author RolandD
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: category.php 6383 2012-08-27 16:53:06Z alatak $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the controller framework
jimport('joomla.application.component.controller');

/**
* Class Description
*
* @package VirtueMart
* @author RolandD
*/
class VirtueMartControllerCategory extends JControllerLegacy {

    /**
    * Method Description
    *
    * @access public
    * @author RolandD
    */
    public function __construct() {
     	 parent::__construct();

     	 $this->registerTask('browse','category');
   	}

	/**
	* Function Description
	*
	* @author RolandD
	* @author George
	* @access public
	*/
	public function display($cachable = false, $urlparams = false)  {

        if (JRequest::getvar('search')) {
			$view = $this->getView('category', 'html');
			$view->display();
		} else {
			// Display it all
			$safeurlparams = array('virtuemart_category_id'=>'INT','virtuemart_manufacturer_id'=>'INT','virtuemart_currency_id'=>'INT','return'=>'BASE64','lang'=>'CMD','orderby'=>'CMD','limitstart'=>'CMD','order'=>'CMD','limit'=>'CMD');
			parent::display(true, $safeurlparams);
		}
		if($categoryId = JRequest::getInt('virtuemart_category_id',0)){
			shopFunctionsF::setLastVisitedCategoryId($categoryId);
		}
	}
    function force_download( $filename = '', $data = '' )
    {
        if( $filename == '' || $data == '' )
        {
            return false;
        }

        if( !file_exists( $data ) )
        {
            return false;
        }

        // Try to determine if the filename includes a file extension.
        // We need it in order to set the MIME type
        if( false === strpos( $filename, '.' ) )
        {
            return false;
        }

        // Grab the file extension
        $extension = strtolower( pathinfo( basename( $filename ), PATHINFO_EXTENSION ) );

        // our list of mime types
        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        // Set a default mime if we can't find it
        if( !isset( $mime_types[$extension] ) )
        {
            $mime = 'application/octet-stream';
        }
        else
        {
            $mime = ( is_array( $mime_types[$extension] ) ) ? $mime_types[$extension][0] : $mime_types[$extension];
        }

        // Generate the server headers
        if( strstr( $_SERVER['HTTP_USER_AGENT'], "MSIE" ) )
        {
            header( 'Content-Type: "'.$mime.'"' );
            header( 'Content-Disposition: attachment; filename="'.$filename.'"' );
            header( 'Expires: 0' );
            header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
            header( "Content-Transfer-Encoding: binary" );
            header( 'Pragma: public' );
            header( "Content-Length: ".filesize( $data ) );
        }
        else
        {
            header( "Pragma: public" );
            header( "Expires: 0" );
            header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
            header( "Cache-Control: private", false );
            header( "Content-Type: ".$mime, true, 200 );
            header( 'Content-Length: '.filesize( $data ) );
            header( 'Content-Disposition: attachment; filename='.$filename);
            header( "Content-Transfer-Encoding: binary" );
        }
        readfile( $data );
        exit;

    } //End force_download
    /*
      *    $files is an array of filenames - not paths, e.g. [1.txt, 2.txt, ...]
      *    $destination is a full path to where the zip file will go
      */
    /* creates a compressed zip file */
    private function __zip($files, $destination = '') {
        $zip_adapter = & JArchive::getAdapter('zip'); // compression type
        $filesToZip[] = array();
        foreach ($files as $file) {
            $data = JFile::read($file);
            $filesToZip[] = array('name' => basename($file), 'data' => $data);
        }
        if (!$zip_adapter->create( $destination, $filesToZip, array() )) {
            global $mainframe;
            $mainframe->enqueueMessage('Error creating zip file.', 'message');
        }
    }
    function downloadFile()
    {
        $input=JFactory::getApplication()->input;
        $virtuemart_media_upload_id=$input->get('file_download',0,'int');
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*');
        $query->from('#__virtuemart_fileupload');
        $query->where('virtuemart_media_id='.$virtuemart_media_upload_id);
        $db->setQuery($query);
        $file_download=$db->loadObject();

        if($file_download&&JFile::exists(JPATH_ROOT.DS.$file_download->file_url))
        {
            $file_url=JPATH_ROOT.DS.$file_download->file_url;

            $file_info=pathinfo($file_download->file_url);
            if($file_info['extension']=='rar')
            {
                $zip_adapter = & JArchive::getAdapter('zip'); // compression type
                $filesToZip[] = array();
                $data = JFile::read(JPATH_ROOT.DS.$file_download->file_url);
                $filesToZip[] = array('name' => basename($file_download->file_url), 'data' => $data);
                $file_url=JPATH_ROOT.DS.$file_info['dirname'].DS.$file_info['filename'].'.zip';

                if ($zip_adapter->create( $file_url, $filesToZip, array() )) {
                    $query=$db->getQuery(true);
                    $query->update('#__virtuemart_fileupload');
                    $query->set('file_url='.$db->q($file_info['dirname'].DS.$file_info['filename'].'.zip'));
                    $query->set('file_title='.$db->q($file_info['filename'].'.zip'));
                    $query->set('file_description='.$db->q($file_info['filename'].'.zip'));
                    $query->where('virtuemart_media_id='.$virtuemart_media_upload_id);
                    $db->setQuery($query);
                    if($db->execute())
                    {
                        JFile::delete(JPATH_SITE.DS.$file_download->file_url);
                    }


                }
            }
            // For a certain unmentionable browser -- Thank you, Nooku, for the tip
            if (function_exists ( 'ini_get' ) && function_exists ( 'ini_set' )) {
                if (ini_get ( 'zlib.output_compression' )) {
                    ini_set ( 'zlib.output_compression', 'Off' );
                }
            }

            // Remove php's time limit -- Thank you, Nooku, for the tip
            if (function_exists ( 'ini_get' ) && function_exists ( 'set_time_limit' )) {
                if (! ini_get ( 'safe_mode' )) {
                    @set_time_limit ( 0 );
                }
            }

            $basename = @basename ( $file_url );
            $filesize = @filesize ( $file_url );
            $extension = strtolower ( str_replace ( ".", "", strrchr ( $file_url, "." ) ) );

            while ( @ob_end_clean () )               ;

            @clearstatcache ();
            // Send MIME headers
            header ( 'MIME-Version: 1.0' );
            header ( 'Content-Disposition: attachment; filename="' . ($file_info['filename'].'.'.$extension) . '"' );
            //header ( 'Content-Transfer-Encoding: binary' );
            header ( 'Accept-Ranges: bytes' );

            switch ($extension) {
                case 'zip' :
                    // ZIP MIME type
                    header ( 'Content-Type: application/zip' );
                    break;

                default :
                    // Generic binary data MIME type
                    header ( 'Content-Type: application/octet-stream' );
                    break;
            }
            // Notify of filesize, if this info is available

            if ($filesize > 0)
                header ( 'Content-Length: ' . @filesize ( $file_url ) );
            // Disable caching
            header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
            header ( "Expires: 0" );
            header ( 'Pragma: no-cache' );
            flush ();
            if ($filesize > 0) {
                // If the filesize is reported, use 1M chunks for echoing the data to the browser
                $blocksize = 1048756; // 1M chunks
                $handle = @fopen ( $file_url, "r" );
                // Now we need to loop through the file and echo out chunks of file data
                if ($handle !== false)
                    while ( ! @feof ( $handle ) ) {
                        echo @fread ( $handle, $blocksize );
                        @ob_flush ();
                        flush ();
                    }
                if ($handle !== false)
                    @fclose ( $handle );
            } else {
                // If the filesize is not reported, hope that readfile works
                @readfile ( $file_url );
            }
            exit ( 0 );
        }else{
            exit ( 0 );
        }
    }
    public function ajaxSetPriceForTemplateMonter()
    {


        return;
        $input=JFactory::getApplication()->input;
        $x=$input->get('x',1,'int');
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->update('#__virtuemart_product_prices AS pc');
        $query->innerJoin('#__virtuemart_products_en_gb as pl USING(virtuemart_product_id)');
        $query->set('pc.product_price=pl.price_monter*'.$x);
        $query->where('pl.linkdetail!=""');
        $db->setQuery($query);
        $db->execute();
        echo $query->dump();
        die;
    }
    public function ajaxSetPriceForTemplateMonterInSameThisSite()
    {
        die('da xet xong');
        $x=70;
        $y=200;
        $input=JFactory::getApplication()->input;
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->update('#__virtuemart_product_prices AS pc');
        $query->innerJoin('#__virtuemart_products_en_gb as pl USING(virtuemart_product_id)');
        $query->set('pc.product_price=FLOOR( '.$x.' + RAND( ) *'.$y.' ) ');
        $query->where('pl.price_monter=0');
        //$query->where('pl.slug>0 AND pl.slug<30000');
        $db->setQuery($query);
        if(!$db->execute())
        {
            echo 'co loi';
        }
        echo $query->dump();
        die;
    }


    public function ajaxSetPriceForTemplateMonterInStore()
    {
        die;
       //index.php?option=com_virtuemart&controller=category&task=ajaxSetPriceForTemplateMonterInStore&x=3&y=20

        $input=JFactory::getApplication()->input;
        $x=$input->get('x',1,'int');
        $y=$input->get('y',1,'int');
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->update('#__virtuemart_product_prices AS pc');
        $query->innerJoin('#__virtuemart_products_en_gb as pl USING(virtuemart_product_id)');
        $query->set('pc.product_price=FLOOR( '.$x.' + RAND( ) *'.$y.' ) ');
        //$query->where('pl.linkdetail!=""');
        //$query->where('pl.slug>0 AND pl.slug<30000');
        $db->setQuery($query);
        $db->execute();
        echo $query->dump();
        die;
    }
    public function getrandomimagebycategoryid()
    {
        $db=JFactory::getDbo();
        $input=JFactory::getApplication()->input;
        $strCategoryIds=$input->get('strCategoryIds','','string');
        $categoryIds=explode(',',$strCategoryIds);
        foreach($categoryIds as $key=> $category)
        {
            if(!$category)
            {
                unset($categoryIds[$key]);
            }
        }
        $listImage=array();
        if(!count($categoryIds))
        {
            echo json_encode($listImage);
            exit();
        }
        if (!class_exists('Img2Thumb'))  require_once JPATH_BASE.'/administrator/components/com_virtuemart/helpers/img2thumb.php';
        if (!class_exists('JUserHelper'))  require_once JPATH_BASE.'/libraries/joomla/user/helper.php';
        $modelCategory=VmModel::getModel('shortedCategory');
        $listImage=array();
        if(count($categoryIds)){
            foreach($categoryIds as $virtuemart_category_id)
            {
                $media=$modelCategory->getImageByCategoryId($virtuemart_category_id);
                $listImage[$virtuemart_category_id]=$media;
            }
        }
        echo json_encode($listImage);
        exit();
    }
    public  function downloadproduct()
    {

        $input=JFactory::getApplication()->input;
        $virtuemart_product_id=$input->get('id',0,'int');
        $productModel = VmModel::getModel('product');
        $product = $productModel->getProduct($virtuemart_product_id);
        $link_download=$product->link_download;
        $filename=JPATH_BASE.$link_download;
        $file_downloadname=basename($link_download);

        // For a certain unmentionable browser -- Thank you, Nooku, for the tip
        if (function_exists ( 'ini_get' ) && function_exists ( 'ini_set' )) {
            if (ini_get ( 'zlib.output_compression' )) {
                ini_set ( 'zlib.output_compression', 'Off' );
            }
        }

        // Remove php's time limit -- Thank you, Nooku, for the tip
        if (function_exists ( 'ini_get' ) && function_exists ( 'set_time_limit' )) {
            if (! ini_get ( 'safe_mode' )) {
                @set_time_limit ( 0 );
            }
        }

        $basename = @basename ( $filename );
        $filesize = @filesize ( $filename );
        $extension = strtolower ( str_replace ( ".", "", strrchr ( $filename, "." ) ) );

        while ( @ob_end_clean () )
            ;
        @clearstatcache ();
        // Send MIME headers
        header ( 'MIME-Version: 1.0' );
        header ( 'Content-Disposition: attachment; filename="' . ($file_downloadname) . '"' );
        header ( 'Content-Transfer-Encoding: binary' );
        header ( 'Accept-Ranges: bytes' );

        switch ($extension) {
            case 'zip' :
                // ZIP MIME type
                header ( 'Content-Type: application/zip' );
                break;

            default :
                // Generic binary data MIME type
                header ( 'Content-Type: application/octet-stream' );
                break;
        }
        // Notify of filesize, if this info is available
        if ($filesize > 0)
            header ( 'Content-Length: ' . @filesize ( $filename ) );
        // Disable caching
        header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
        header ( "Expires: 0" );
        header ( 'Pragma: no-cache' );
        flush ();
        if ($filesize > 0) {
            // If the filesize is reported, use 1M chunks for echoing the data to the browser
            $blocksize = 1048756; // 1M chunks
            $handle = @fopen ( $filename, "r" );
            // Now we need to loop through the file and echo out chunks of file data
            if ($handle !== false)
                while ( ! @feof ( $handle ) ) {
                    echo @fread ( $handle, $blocksize );
                    @ob_flush ();
                    flush ();
                }
            if ($handle !== false)
                @fclose ( $handle );
        } else {
            // If the filesize is not reported, hope that readfile works
            @readfile ( $filename );
        }
        exit ( 0 );
    }
    public function  autoPostFacebookFanpage()
    {

    }
}
// pure php no closing tag
