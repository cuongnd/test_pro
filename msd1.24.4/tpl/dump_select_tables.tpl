<div id="pagetitle">{PAGETITLE}</div>
<h6>{L_DATABASE}: {DATABASE}</h6>
<div id="content">
<form name="frm_tbl" action="filemanagement.php" method="post"
	onSubmit="return chkFormular()">

<div><input type="button" class="Formbutton" onclick="Sel(true);"
	value="{L_SELECT_ALL}"> <input type="button" onclick="Sel(false);"
	value="{L_DESELECT_ALL}" class="Formbutton"> <input type="submit"
	class="Formbutton" name="{BUTTON_NAME}"
	value="{L_START_BACKUP}"></div>
<br>

<table class="bdr">
	<tr class="thead">
		<th>#</th>
		<th>{L_NAME}</th>
		<th><!-- 
			Aktion
			--></th>
		<th>{L_ROWS}</th>
		<th>{L_SIZE}</th>
		<th>{L_LAST_UPDATE}</th>
		<th>{L_TABLE_TYPE}</th>

	</tr>
	<!-- BEGIN ROW -->
	<tr class="{ROW.CLASS}">
		<td style="text-align: right">{ROW.NR}.</td>
		<td><label for="t{ROW.ID}">{ROW.TABLENAME}</label></td>
		<td class="sm" align="left"><input type="checkbox" class="checkbox"
			name="chk_tbl" id="t{ROW.ID}" value="{ROW.TABLENAME}"> <!-- 
			<input type="checkbox" class="checkbox" name="chk_tbl_data" id="t_data{ROW.ID}" value="{ROW.TABLENAME}">
			 --></td>
		<td style="text-align: right">{ROW.RECORDS}</td>
		<td style="text-align: right">{ROW.SIZE}</td>
		<td>{ROW.LAST_UPDATE}</td>
		<td>{ROW.TABLETYPE}</td>
	</tr>
	<!-- END ROW -->
</table>
<br>
<div><input type="button" class="Formbutton" onclick="Sel(true);"
	value="{L_SELECT_ALL}"> <input type="button" onclick="Sel(false);"
	value="{L_DESELECT_ALL}" class="Formbutton"> <input type="submit"
	class="Formbutton" name="{BUTTON_NAME}"
	value="{L_START_BACKUP}"></div>
<br>
<br>
<br>
<br>
<input type="hidden" name="dumpKommentar" value="{DUMP_COMMENT}"> <input
	type="hidden" name="tbl_array" value=""> <input type="hidden"
	name="filename" value="{FILENAME}"> <input type="hidden"
	name="sel_dump_encoding" value="{SEL_DUMP_ENCODING}"></form>
</div>
</body>
</html>
<script type="text/javascript">
    jQuery(document).ready(function(){
        $('table.bdr tbody tr').shiftcheckbox({

            // Options accept selectors, jQuery objects, or DOM
            // elements.

            checkboxSelector : ':checkbox',
            ignoreClick      : 'a',

            // The onChange function will be called whenever the
            // plugin changes the state of a checkbox.

            onChange : function(checked) {
            }

        });

    });
</script>