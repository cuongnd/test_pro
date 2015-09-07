Joomla.submitbutton = function (pressbutton) {
    var form = document.adminForm;
    if (pressbutton == 'show_help') {
        $jMaQma("#infobox").show();
        return;
    }

    Joomla.submitform(pressbutton, document.getElementById('adminForm'));
}

$jMaQma(document).ready(function () {
    $jMaQma('#contentTable').tableDnD({
        onDrop:function (table, row) {
            var rows = table.tBodies[0].rows;
            for (var i=0; i<rows.length; i++) {
                var RowID = rows[i].id;
                $jMaQma('#adminForm').append($jMaQma('<input/>', {
                    type: 'hidden',
                    name: 'contentTable[]',
                    value: RowID.replace('contentTable-row-', '')
                }));
            }
            $jMaQma("#task").val('workgroup_saveorder');
            $jMaQma("#adminForm").submit();
        },
        dragHandle:"dragHandle"
    });

    $jMaQma("#contentTable tbody tr").hover(function () {
        $jMaQma(this.cells[0]).addClass('showDragHandle');
    }, function () {
        $jMaQma(this.cells[0]).removeClass('showDragHandle');
    });
});