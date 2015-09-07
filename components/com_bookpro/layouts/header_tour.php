<?php
AImporter::helper('activity', 'tour');
AImporter::model('category', 'tourcategory');
$grade = JURI::base() . "/components/com_bookpro/assets/images/" . $displayData->grade . 'grade.png';
$activities = ActivityHelper::buildActivities('title', $displayData->activities);
AImporter::helper('tour');
$itis = $displayData->itis;

$country = TourHelper::getCountryByTour($displayData->id);
//$categoryModel = new BookProModelCategory();
//$categoryModel->setId($displayData->cat_id);
//$category = $categoryModel->getObject();

$modelTourCategory = new BookProModelTourCategory();
$listsTourCategory = array('tour_id' => $displayData->id);
$modelTourCategory->init($listsTourCategory);
$tourCategories = $modelTourCategory->getData();
$categoriestitle = '';
if ($tourCategories) {
    foreach ($tourCategories as $key => $tourCategory) {
        $categoryModel = new BookProModelCategory();
        $categoryModel->setId($tourCategory->cat_id);
        $category = $categoryModel->getObject();
        if ($category) {
            if ($key) {
                $categoriestitle .= ', ' . $category->title;
            } else {
                $categoriestitle .= $category->title;
            }
        }
    }
}
?>

<div class="div1_span8_col_left">
    <p style="background: #95A5A5; text-transform: uppercase; padding: 5px; font-weight: bold; color: #fff" class="span8_main_body_div1"><?php echo $displayData->title; ?></p>
    <div class="country_title"><?php echo JText::sprintf('COM_BOOKPRO_HEAD_TOUR_TITLE', $displayData->days, TourHelper::getHTMLInline($country)); ?></div>


    <div class="content_text_span8 text-left">
        <div class="text1_span8 span4">
            <p><?php echo JText::sprintf('COM_BOOKPRO_TOUR_CODE_TXT', $displayData->code) ?></p>
            <p><?php echo JText::sprintf('COM_BOOKPRO_TOUR_TYPE', TourHelper::formatTourType($displayData->stype)) ?></p>
        </div>
        <div class="text1_span8 span4 text-left">
            <p><?php echo JText::sprintf('COM_BOOKPRO_TOUR_STYLE', $categoriestitle) ?></p>
                                                <!--<p><?php //echo JText::sprintf('COM_BOOKPRO_TOUR_ACTIVITIES_TXT',$activities)  ?></p>-->
            <span>
                <span class="phisical" style="font-weight:bold; color:#007799;"> Physical grade:</span>
                <span><img style="position:relative; top:-6px;" src="<?php echo $grade; ?>"> </span>
            </span>
        </div>
        <div class="text1_span8 span4 text-left">
            <p>Start city:&nbsp;<?php
                $first = reset($itis);
                echo JText::sprintf('COM_BOOKPRO_HEAD_TOUR_CITY', $first->city_title, $first->country_name);
                ?></p>
            <p>End city :&nbsp;<?php
                $end = end($itis);
                echo JText::sprintf('COM_BOOKPRO_HEAD_TOUR_CITY', $end->city_title, $end->country_name);
                ?></p>
        </div>
    </div>
</div>