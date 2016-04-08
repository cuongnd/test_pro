<?php
/**
 * Created by PhpStorm.
 * User: cuongnd
 * Date: 5/31/14
 * Time: 5:57 AM
 */

class joomlart {
    public function getProducts()
    {
        $db=JFactory::getDbo();
        require_once(JPATH_VM_ADMINISTRATOR.'/helpers/simple_html_dom.php');
        $html = file_get_html('http://www.joomlart.com/demo/#ja_fixel');

        $products=$html->find('div[class="products"]');
        $products=$products[0];
        $linkProducts=$products->find('div[class="product"]');
        $listLinksDemo=array();
        for($i=0;$i<count($linkProducts);$i++)
        {
            $linkProduct=$linkProducts[$i];
            $listLinksDemo[$linkProduct->{"data-name"}]=$linkProduct->{"data-url"};

        }
        $model_product=VmModel::getModel('product');
        $m=14;
       for($i=$m;$i<=16;$i++)
       {
           $link='http://www.joomlart.com/joomla/templates/page-'.$i;
           echo $link;
           $html = file_get_html($link);
           $linkProducts=$html->find('ul[class="pd-cta"] li a');

           for($j=1;$j<count($linkProducts);$j=$j+2)
           {
               $data=array();
               $htmlLinkDemo=$linkProducts[$j-1];
               $linkDemo=$htmlLinkDemo->href;
               $post=strpos($linkDemo,'#');
               $linkDemo=substr($linkDemo,$post+1);
               $data['shortLink']=$linkDemo;
               $data['linkdemo']= $listLinksDemo[$linkDemo];
               $query=$db->getQuery(true);
               $query->select('virtuemart_product_id');
               $query->from('#__virtuemart_products_en_gb');
               $query->where('linkdemo='.$db->quote($data['linkdemo']));
               $db->setQuery($query);
               $virtuemart_product_id=$db->loadResult();
               if($virtuemart_product_id!=0)
                   continue;
               $linkProduct=$linkProducts[$j];
               $linkDetail= $linkProduct->href;
               $html= file_get_html('http://www.joomlart.com/'.$linkDetail);

               $title=$html->find('div[class="page-header page-header-primary"] h2');
               $data['product_name']=$title[0]->innertext ;
               if(trim($data['product_name'])=='')
               {
                   $data['product_name']=$data['shortLink'];
               }
               $data['product_sku']=$data['product_name'];
               $htmlImages= $html->find('div[class="carousel-inner"] div[class="item"] img');
               $images=array();
               foreach($htmlImages as $image)
               {
                   $images[]= $image->src;

               }
               $data['images']=$images;
               $htmlQuickInfo=$html->find('div[class="quick-info pd-quickglance"] p a');
               $listQuickInfo=array();
               foreach($htmlQuickInfo as $quickInfo)
               {
                   $listQuickInfo[]= $quickInfo->title;

               }
               $data['listQuickInfo']=$listQuickInfo;
               $description=$html->find('div[class="description"]');
               $data['product_s_desc']=$description[0]->innertext ;
               $pdItemContent=$html->find('section[class="pd-item-content"]');
               $data['product_desc']=$pdItemContent[0]->innertext ;
               $model_product->saveProduct2($data);

           }




       }
    }
} 