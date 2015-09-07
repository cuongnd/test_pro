<?php
defined('_JEXEC') or die('Restricted access');
$config = AFactory::getConfig();
?>
<?php
$app = JFactory::getApplication();
$input = $app->input;

require_once (JPATH_SITE . '/components/com_bookpro/controllers/tcpdf/tcpdf.php');
?>
<A HREF="javascript:window.print()"><?php echo JText::_('COM_BOOKPRO_CLICK_PRINT') ?></A>
<?php
ob_start();
?>

<div class="table_passenger">
    <table style="width: 100%" class="table table-bordered">
        <thead>
            <tr>


                <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_FULL_NAME') ?>
                </th>

                <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_BIRTHDAY') ?>
                </th>
                <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_EMAIL') ?>
                </th>
                <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_PHONE1') ?>
                </th>
                <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_ADDRESS') ?>
                </th>
                <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT') ?>
                </th>

            </tr>
        </thead>
        <?php
        if (count($this->passengers) > 0) {
            $i = 1;
            foreach ($this->passengers as $pass) {
                ?>
                <tr>

                    <td><?php echo ($i++) . '.' . $pass->firstname . ' ' . $pass->lastname . ' (' . ($pass->gender ? "Male" : "Female") . ')'; ?><?php if ($pass->leader) { ?><span class="label label-warning leader"><?php echo JText::_('COM_BOOKPRO_LEADER') ?></span><?php } ?></td>
                    <td><?php echo JHtml::_('date', $pass->birthday, "d-m-Y"); ?></td>
                    <td><?php echo $pass->email; ?></td>
                    <td><?php echo $pass->phone1; ?></td>
                    <td><?php echo $pass->address; ?></td>
                    <td><?php echo $pass->passport; ?></td>

                </tr>
                <?php
            }
        }
        ?>
    </table>
</div>

<?php
$html = ob_get_contents();
ob_end_clean();
echo $html;

$config = new JConfig();
$tmp_dest = $config->tmp_path;
$pdf = new TCPDF();
$pdf->addPage();
$pdf->setFont('helvetica', '', 9);

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->SetTextColor(255);

$pdf->SetAutoPageBreak(false, 0);
$pdf->setFontSubsetting(false);
$utf8text = file_get_contents("cache/utf8test.txt", true);
$pdf->Write(5, $utf8text);
$path_file = JPATH_SITE . '/tmp/test.pdf';
$pdf->Output($path_file, 'F');


if (file_exists($path_file)) {
    header('Content-type: application/force-download');
    header('Content-Disposition: attachment; filename=' . basename($path_file));
    @readfile($path_file);
}
?>
