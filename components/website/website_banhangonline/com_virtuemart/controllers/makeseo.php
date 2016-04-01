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
class VirtueMartControllerMakeSeo extends JControllerLegacy {

    /**
    * Method Description
    *
    * @access public
    * @author RolandD
    */
    public function __construct() {
     	 parent::__construct();
   	}

	/**
	* Function Description
	*
	* @author RolandD
	* @author George
	* @access public
	*/
	public function display($cachable = false, $urlparams = false)  {

        parent::display();
	}
    public function  loctrunglaptrongtoandatabase()
    {
        $input=JFactory::getApplication()->input;
        $db=JFactory::getDbo();

        $subTask=$input->get('subTask','getCategory','string');


        if($subTask=='getCategory')
        {
            $objectResult=array();
            $query=$db->getQuery(true);
            $query->from('#__virtuemart_categories_en_gb AS cl');
            $query->select('cl.virtuemart_category_id AS virtuemart_category_id,cl.category_name,cl.metakey');
            $query->where('dakiemtratrunglap=0');
            $db->setQuery($query,0,1);
            $category=$db->loadObject();
            $objectResult['category']=$category;
            $objectResult['subTask']='filterRepeatCategory';
            $objectResult['beforeFilter']='';
            $objectResult['afterFilter']='';


            $query=$db->getQuery(true);
            $query->select('COUNT(*)');
            $query->from('#__virtuemart_categories_en_gb');
            $query->where('dakiemtratrunglap=0');
            $db->setQuery($query);
            $result=$db->loadResult();
            $objectResult['chuakiemtra']=$result;


            $query=$db->getQuery(true);
            $query->select('COUNT(*)');
            $query->from('#__virtuemart_categories_en_gb');
            $query->where('dakiemtratrunglap=1');
            $db->setQuery($query);
            $result=$db->loadResult();
            $objectResult['dakiemtra']=$result;




            $objectResult['listDownUp']=array();
            echo json_encode($objectResult);
            die;

        }elseif($subTask=='filterRepeatCategory')
        {

            $virtuemart_category_id=$input->get('virtuemart_category_id',0,'int');
            $objectResult=array();
            $query=$db->getQuery(true);
            $query->from('#__virtuemart_categories_en_gb AS cl');
            $query->select('cl.virtuemart_category_id AS virtuemart_category_id,cl.category_name,cl.metakey');
            $query->where('virtuemart_category_id='.$virtuemart_category_id);
            $db->setQuery($query);
            $category=$db->loadObject();

            $metaKey=$category->metakey;
            $metaKey=explode(',',$metaKey);
            $objectResult['beforeFilter']=count($metaKey);

            $query=$db->getQuery(true);
            $query->from('#__virtuemart_categories_en_gb AS cl');
            $query->select('cl.virtuemart_category_id AS virtuemart_category_id,cl.category_name,cl.metakey');
            $query->where('virtuemart_category_id!='.$virtuemart_category_id);
            $query->where('dakiemtratrunglap=0');

            $db->setQuery($query);
            $categories=$db->loadObjectList();
            $listDownUp=array();
            foreach($categories as $category1)
            {
                $metaKey1=$category1->metakey;
                $metaKey1=explode(',',$metaKey1);
                $metaKey2=array_diff($metaKey,$metaKey1);
                $listDownUp[]=count($metaKey)-count($metaKey2);
                $metaKey=$metaKey2;
            }

            $metaKey=implode(',',$metaKey);
            $category->metakey=$metaKey;
            $query=$db->getQuery(true);
            $query->update('#__virtuemart_categories_en_gb')
                ->set('dakiemtratrunglap=1')
                ->set('metakey='.$db->quote($metaKey))
                ->where('virtuemart_category_id='.$virtuemart_category_id);
            $db->setQuery($query);
            $db->execute();






            $objectResult['listDownUp']=$listDownUp;
            $objectResult['category']=$category;
            $objectResult['subTask']='getCategory';



            $query=$db->getQuery(true);
            $query->select('COUNT(*)');
            $query->from('#__virtuemart_categories_en_gb');
            $query->where('dakiemtratrunglap=0');
            $db->setQuery($query);
            $result=$db->loadResult();
            $objectResult['chuakiemtra']=$result;


            $query=$db->getQuery(true);
            $query->select('COUNT(*)');
            $query->from('#__virtuemart_categories_en_gb');
            $query->where('dakiemtratrunglap=1');
            $db->setQuery($query);
            $result=$db->loadResult();

            $objectResult['dakiemtra']=$result;



            echo json_encode($objectResult);
            die;
        }
    }

    public function  loctrunglap()
    {
        $input=JFactory::getApplication()->input;
        $db=JFactory::getDbo();

        $subTask=$input->get('subTask','getCategory','string');


        if($subTask=='getCategory')
        {
            $objectResult=array();
            $query=$db->getQuery(true);
            $query->from('#__virtuemart_categories_en_gb AS cl');
            $query->select('cl.virtuemart_category_id AS virtuemart_category_id,cl.category_name,cl.metakey');
            $query->where('dakiemtratrunglap=0');
            $db->setQuery($query,0,1);
            $category=$db->loadObject();
            $objectResult['category']=$category;
            $objectResult['subTask']='filterRepeatCategory';
            $objectResult['beforeFilter']='';
            $objectResult['afterFilter']='';


            $query=$db->getQuery(true);
            $query->select('COUNT(*)');
            $query->from('#__virtuemart_categories_en_gb');
            $query->where('dakiemtratrunglap=0');
            $db->setQuery($query);
            $result=$db->loadResult();
            $objectResult['chuakiemtra']=$result;

            echo json_encode($objectResult);
            die;

        }elseif($subTask=='filterRepeatCategory')
        {

            $virtuemart_category_id=$input->get('virtuemart_category_id',0,'int');
            $objectResult=array();
            $query=$db->getQuery(true);
            $query->from('#__virtuemart_categories_en_gb AS cl');
            $query->select('cl.virtuemart_category_id AS virtuemart_category_id,cl.category_name,cl.metakey');
            $query->where('virtuemart_category_id='.$virtuemart_category_id);
            $db->setQuery($query);
            $category=$db->loadObject();
            $listKeyword=array();
            $metaKey=$category->metakey;
            $metaKey=explode(',',$metaKey);
            $objectResult['beforeFilter']=count($metaKey);
            foreach($metaKey as $itemMetaKey)
            {
                if(!in_array($itemMetaKey,$listKeyword))
                {
                    $listKeyword[]=$itemMetaKey;
                }
            }
            $objectResult['afterFilter']=count($listKeyword);
            $strList=implode(',',$listKeyword);
            $category->metakey=$strList;
            $query=$db->getQuery(true);
            $query->update('#__virtuemart_categories_en_gb')
                ->set('metakey='.$db->quote($strList))
                ->set('dakiemtratrunglap=1')
                ->where('virtuemart_category_id='.$category->virtuemart_category_id);
            $db->setQuery($query);
            $db->execute();

            $objectResult['category']=$category;
            $objectResult['subTask']='getCategory';


            $query=$db->getQuery(true);
            $query->select('COUNT(*)');
            $query->from('#__virtuemart_categories_en_gb');
            $query->where('dakiemtratrunglap=1');
            $db->setQuery($query);
            $result=$db->loadResult();
            $objectResult['dakiemtra']=$result;



            echo json_encode($objectResult);
            die;
        }
    }

    public  function setMetaRobotByGoogleSearch()
    {
        $input=JFactory::getApplication()->input;
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->from('#__virtuemart_categories_en_gb AS cl');
        $query->select('cl.virtuemart_category_id AS virtuemart_category_id,cl.category_name,cl.metakey');
        $query->where('cl.metakey ="0"');
        $db->setQuery($query,0,1);
        $listCategory=$db->loadObjectList('virtuemart_category_id');

        $listOpenSource=array(
            "Drupal"
            ,"Joomla"
            ,"PHP-Fusion"
            ,"CMS Made Simple"
            ,"NukeViet"
            ,"MODx"
            ,"Mambo"
            ,"MAXdev"
            ,"XOOPS"
            ,"PHP-Nuke"
            ,"MyBB"
            ,"phpBB"
            ,"SMF"
            ,"PunBB"
            ,"Phorum"
            ,"AEF"
            ,"Vanilla"
            ,"UseBB"
            ,"miniBB"
            ,"XMB"
            ,"WordPress"
            ,"Textpattern"
            ,"Nucleus CMS"
            ,"LifeType"
            ,"Serendipity"
            ,"Dotclear"
            ,"Zomplog"
            ,"FlatPress"
            ,"NibbleBlog"
            ,"Croogo"
            ,"Magento"
            ,"Zen Cart"
            ,"OpenCart"
            ,"osCommerce"
            ,"PrestaShop"
            ,"AlegroCart"
            ,"Freeway"
            ,"eclime"
            ,"osCSS"
            ,"TomatoCart"
            ,"Moodle"
            ,"ATutor "
            ,"eFront"
            ,"Dokeos"
            ,"Docebo"
            ,"Interact"
            ,"DrupalEd"
            ,"ILIAS"
            ,"Open Conference Systems"
            ,"Open Journal Systems"
        );

        $allKeyword=array();
        $interval=0;
        foreach($listCategory as $category)
        {
            $allKeyword[$interval]['category']=$category->category_name;
            $allKeyword[$interval]['category_id']=$category->virtuemart_category_id;
            $listKeyword=array();
            $categoryName=$category->category_name;
            $categoryName=str_replace('&',' ',$categoryName);
            $categoryName=str_replace(',',' ',$categoryName);
            $arrayCategoryName=explode(' ',$categoryName);

            foreach($arrayCategoryName as $categoryName)
            {
                if($categoryName!='')
                    $listKeyword[]=$categoryName;
            }

            $list=array();
            foreach($listKeyword as $keyword)
            {
                $this->getListSuggestion($keyword,$list,0,1);
            }

            $listKeyword=array();
            foreach($list as $item)
            {
                foreach($listOpenSource as $textOpenSource)
                {
                    $listKeyword[]=$item.' '.$textOpenSource;
                }
            }
            $strList=implode(',',$listKeyword);
            $query=$db->getQuery(true);
            $query->update('#__virtuemart_categories_en_gb')
                ->set('metakey='.$db->quote($strList))
                ->where('virtuemart_category_id='.$category->virtuemart_category_id);
            $db->setQuery($query);
            $db->execute();
            $allKeyword[$interval]['listKeyword']=$strList;
        }
        echo json_encode($allKeyword);
       die;
    }
    public  function  ajaxGetListSuggestion()
    {

        $input=JFactory::getApplication()->input;
        $keyword=$input->get('keyword','','string');
        $keyword=explode(' ',$keyword);
        $keyword=end($keyword);
        $url='http://google.com/complete/search?hl=en&output=toolbar&q='.$keyword;
        $ch = curl_init();
        $header[] = "Accept-Language: en-US,en;q=0.8";
        $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.3";
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, $url);

        $content = curl_exec($ch);

        $content=simplexml_load_string($content);

        $completeSuggestions=$content->CompleteSuggestion;
        $listSuggestions=array();
        if(count($completeSuggestions))
        {
            foreach($completeSuggestions as $completeSuggestion)
            {
                $suggestion=$completeSuggestion->suggestion;
                $data=$suggestion->attributes()->data;
                $data=(string)$data;
                $listSuggestions[]=$data ;
            }
        }
        $list_result=array();
        if(count($listSuggestions))
        {
            foreach ($listSuggestions as $suggestion)
            {
                $list_result[]=array(
                    "id"=>$suggestion,
                    "label"=>$suggestion,
                    "value"=>$suggestion
                );
            }
        }
        echo json_encode($list_result);
        exit();

    }
    public  function getListSuggestion($keyword='',&$list=array(),$level=0,$maxLevel=99)
    {
        if($level<=$maxLevel)
        {
            $params=array(
                "hl"=>"en",
                "output"=>"toolbar",
                "q"=>$keyword
            );
            $params=http_build_query($params,null,'&');
            $url='http://google.com/complete/search?'.$params;

            $content=file_get_contents($url);

            $content=simplexml_load_string($content);

            $completeSuggestions=$content->CompleteSuggestion;
            $listSuggestions=array();
            if(count($completeSuggestions))
            {
                foreach($completeSuggestions as $completeSuggestion)
                {
                    $suggestion=$completeSuggestion->suggestion;
                    $data=$suggestion->attributes()->data;
                    $data=(string)$data;
                    $listSuggestions[]=$data ;
                    $list[]=$data;
                }
            }

            if(count($listSuggestions))
            {
                foreach($listSuggestions as $suggestion)
                {
                    $list=$this->getListSuggestion($suggestion,$list,$level+1,$maxLevel);
                }
            }
        }
        return $list;
    }
}
// pure php no closing tag
