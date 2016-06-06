<?php
class vm_vatgia_helper
{
	public static function update_product_detail(&$product)
	{
		$temp = new JRegistry;
		$temp->loadString($product->params);
		$params=$temp;
		$html=$params->get('vatgia.html','');
		if($html!='')
		{
			require_once  JPATH_ROOT.'/libraries/simplehtmldom_1_5/simple_html_dom.php';
			$html=str_get_html($html);
			$image=$html->find('img.picture',0)->src;
			if($image!='')
			{
				self::update_image($image,$product);
				$product->image=$image;
			}
			$price=(float)$html->find('div.price',0)->plaintext;
			if($price!=0)
			{
				self::update_price($price,$product);
				$product->price=$price;
			}
		}
	}

	private static function update_image($src,$product)
	{
		$table_media=VmTable::getInstance('medias','Table');
		$table_media->virtuemart_media_id=0;
		$table_media->file_url=$src;
		$table_media->published=1;
		$table_media->store();

		$errors = $table_media->getErrors();
		if($errors){
			throw new Exception($table_media->getError(), 404);
		}
		$virtuemart_media_id=$table_media->virtuemart_media_id;


		$xdata=array();
		$xdata['virtuemart_product_id'] = (int)$product->virtuemart_product_id;
		$xdata['virtuemart_media_id'] =array($virtuemart_media_id);
		$xrefTable = VmTable::getInstance('product_medias','Table');
		$xrefTable->load($product->virtuemart_product_id);
		$xrefTable->bindChecknStore($xdata);
		$errors = $xrefTable->getErrors();
		if($errors){
			throw new Exception($xrefTable->getError(), 404);
		}
		$errors = $xrefTable->getErrors();
		if($errors){
			throw new Exception($xrefTable->getError(), 404);
		}



	}
	private static function update_price($price,$product)
	{

		$product_prices = VmTable::getInstance('product_prices','Table');
		$product_prices->virtuemart_product_price_id=0;
		$product_prices->product_price=$price;
		$product_prices->virtuemart_product_id=$product->virtuemart_product_id;
		$product_prices->store();
		$errors = $product_prices->getErrors();
		if($errors){
			throw new Exception($product_prices->getError(), 404);
		}



	}

	public static function import_product_vatgia_by_virtuemart_category_id($categoryId)
	{
		$com_virtuemart_path=JPath::get_component_path('com_virtuemart');
		$website=JFactory::getWebsite();
		$model_product=VmModel::getModel('product');
		jimport('joomla.filesystem.file');
		$file_vatgia_category_imported_path=$com_virtuemart_path.'/helpers/vatgia_category_imported.txt';
		$vatgia_category_imported=JFile::read($file_vatgia_category_imported_path);
		$app=JFactory::getApplication();
		$input=$app->input;
		$temp = new JRegistry;

		if($vatgia_category_imported!='')
		{
			$temp->loadString($vatgia_category_imported);
		}

		$list_category_imported=(array)$temp->get('list_category_imported',array());
		if(in_array($categoryId,$list_category_imported))
		{
			return;
		}else{
			$list_category_imported[]=$categoryId;
		}
		$temp->set('list_category_imported',$list_category_imported);
		$content=$temp->toString();
		JFile::write($file_vatgia_category_imported_path,$content);
		$vatgia_category_id=self::get_vatgia_category_id_by_vituemart_category_id($categoryId);
		$link_get_product='http://vatgia.com/ajax_v4/load_type_product_up.php?ajax=1&iCat='.$vatgia_category_id.'&page=1';
		//echo $link_get_product;
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
				$params = new JRegistry;
				$params->set('vatgia.html',$wrapper->outertext);
				$data->params=$params->toString();
				$data=(array)$data;
				$product=$model_product->store($data,false);


				$xdata=array();
				$xdata['virtuemart_category_id'] = (int)$categoryId;
				$xdata['virtuemart_product_id'] =$product->virtuemart_product_id;
				$xrefTable = VmTable::getInstance('product_categories','Table');

				$xrefTable->load($categoryId);
				$xrefTable->bindChecknStore($xdata);
				$errors = $xrefTable->getErrors();
				if($errors){
					throw new Exception($xrefTable->getError(), 404);
				}
				self::update_product_detail($product);
			}
		}else{
			return;
		}
	}

	private static function get_vatgia_category_id_by_vituemart_category_id($categoryId)
	{
		$table_category=VmTable::getInstance('categories','Table');
		$table_category->load($categoryId);
		$category_name=$table_category->category_name;
		$com_virtuemart_path=JPath::get_component_path('com_virtuemart');
		jimport('joomla.filesystem.file');
		$category_vatgia_path=$com_virtuemart_path.'/assets/stories/category_vatgia.html';
		$category_vatgia=JFile::read($category_vatgia_path);
		require_once  JPATH_ROOT.'/libraries/simplehtmldom_1_5/simple_html_dom.php';
		$html = str_get_html($category_vatgia);
		$vatgia_category_id=0;
		foreach($html->find('li') as $li)
		{
			$plaintext= $li->plaintext;
			$return= JString::strcmp($plaintext,$category_name);
			if($return==0){
				$vatgia_category_id= $li->attr['idata'];
				break;
			}
		}
		//echo $vatgia_category_id;
		return $vatgia_category_id;
	}


}
?>