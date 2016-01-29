<?php
$doc=JFactory::getDocument();
$app=JFactory::getApplication();
$app = JFactory::getApplication();
$data_post = file_get_contents('php://input');
$data_post = json_decode($data_post);

$block_id=$data_post->block_id;
$append_to=$data_post->append_to;
$clone_number=$data_post->clone_number;
$app->input->set('ajax_clone',1);
JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables');
$tablePosition=JTable::getInstance('Position','JTable');
$tablePosition->load($block_id);
$db=JFactory::getDbo();
$query=$db->getQuery(true);
$query->select('poscon.*')
    ->from('#__position_config AS poscon')
    ->where('lft>'.(int)$tablePosition->lft.' AND  rgt<'.(int)$tablePosition->rgt)

    ->order('poscon.ordering')
;
$listPositionsSetting=$db->setQuery($query)->loadObjectList();
$os = $app->input->get('os', '', 'String');

$children = array();
if (!empty($listPositionsSetting)) {

    $children = array();

    // First pass - collect children
    foreach ($listPositionsSetting as $v) {
        $pt = $v->parent_id;
        $list = @$children[$pt] ? $children[$pt] : array();
        array_push($list, $v);
        $children[$pt] = $list;
    }

}
if ($os == 'android') {
    ob_get_clean();
    $return_children = array(
        'root_id' => $block_id,
        'children' => $children
    );
    header('Content-Type: application/json');
    echo json_encode($return_children, JSON_NUMERIC_CHECK);
    die;
}
require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
$enableEditWebsite = UtilityHelper::getEnableEditWebsite();
$htmls='';
for($i=0;$i<$clone_number;$i++)
{
    $html='';
    websiteHelperFrontEnd::treeRecurse($block_id, $html, $children, 99, 0, $enableEditWebsite, $os);
    $htmls.='<div class="form_clone_'.($i+1).'">'.$html.'</div>';
}
ob_start();

echo $htmls;
?>

<?php
$contents=ob_get_clean();
$response_array[] = array(
    'key' => $append_to,
    'contents' => $contents
);
echo  json_encode($response_array);
?>
