<?php
$doc=JFactory::getDocument();
JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');
$doc->addStyleSheet(JUri::root() . '/components/com_phpmyadmin/views/relation/tmpl/assets/less/view_relation_default.less', 'text/css', '', array('rel' => 'stylesheet/less'));
$doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/build/less-js/dist/less-1.5.0.js');
$doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/oz.js");
$doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/config.js");
$doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/globals.js");
$doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/visual.js");
$doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/row.js");
$doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/table.js");
$doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/relation.js");
$doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/key.js");
$doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/rubberband.js");
$doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/map.js");
$doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/toggle.js");
$doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/io.js");
$doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/tablemanager.js");
$doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/rowmanager.js");
$doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/keymanager.js");
$doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/window.js");
$doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/options.js");
$doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/wwwsqldesigner.js");
$doc->addScript(JUri::root().'/media/system/js/twitter-typeahead.js/dist/typeahead.jquery.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery.hotkeys-master/jquery.hotkeys.js');
$doc->addScript(JUri::root().'/components/com_phpmyadmin/views/relation/tmpl/assets/js/jquery.realtion_ship.js');
require_once JPATH_ROOT.'/components/com_phpmyadmin/tables/updatetable.php';
$db=JFactory::getDbo();
$table_diagram=new JTableUpdateTable($db,'diagram');
$table_diagram->load(
    array(
        "type"=>'global',
        "website_id"=>0
    )
);
if(!$table_diagram->id)
{
    $table_diagram->type='global';
    $table_diagram->website_id=0;
    $table_diagram->store();
}
$xml_input=$table_diagram->xml;
$db = JFactory::getDbo();
$tables = $db->getTableList();
for ($i = 0; $i < count($tables); $i++) {
    $table = $tables[$i];
    $list_table[] = str_replace($db->getPrefix(), '', $table);
}
?>
<script type="application/javascript">
    jQuery(document).ready(function($){

        $('#drag-drop-demo').realtion_ship({
            list_table:<?php echo json_encode($list_table) ?>
        });


    });
</script>
<div id="drag-drop-demo">
    <div class="row">
        <div  class="col-xs-12 col-sm-12 col-md-12 col-lg-12 diagrams  demo drag-drop-demo">
            <div id="area"></div>
            <div id="controls">
                <div id="bar">
                    <div id="toggle"></div>
                    <input type="button" id="saveload"/>

                    <hr/>
                    <button class="btn btn-danger save-main-ralationship save-close-main-ralationship"  type="button"><i class="fa-save"></i>Save&amp;close</button>
                    <hr/>
                    <button class="btn btn-danger save-main-ralationship apply-main-ralationship"  type="button"><i class="fa-save"></i>Save</button>
                    <hr/>
                    <button class="btn btn-danger cancel-main-ralationship"  type="button"><i class="fa-save"></i>Cancel</button>
                    <hr/>

                    <input type="button" id="addtable"/>
                    <input type="button" id="edittable"/>
                    <input type="button" id="tablekeys"/>
                    <input type="button" id="removetable"/>
                    <input type="button" id="aligntables"/>
                    <input type="button" id="cleartables"/>

                    <hr/>

                    <input type="button" id="addrow"/>
                    <input type="button" id="editrow"/>
                    <input type="button" id="uprow" class="small"/><input type="button" id="downrow"
                                                                          class="small"/>
                    <input type="button" id="foreigncreate"/>
                    <input type="button" id="foreignconnect"/>
                    <input type="button" id="foreigndisconnect"/>
                    <input type="button" id="removerow"/>

                    <hr/>

                    <input type="button" id="options"/>
                    <a href="https://github.com/ondras/wwwsqldesigner/wiki" target="_blank"><input type="button"
                                                                                                   id="docs"
                                                                                                   value=""/></a>
                </div>

                <div id="rubberband"></div>

                <div id="minimap"></div>

                <div id="background"></div>

                <div id="window">
                    <div id="windowtitle"><img id="throbber" src="images/throbber.gif" alt="" title=""/></div>
                    <div id="windowcontent"></div>
                    <input type="button" id="windowok"/>
                    <input type="button" id="windowcancel"/>
                </div>
            </div>
            <!-- #controls -->

            <div id="opts">
                <table>
                    <tbody>
                    <tr>
                        <td>
                            * <label id="language" for="optionlocale"></label>
                        </td>
                        <td>
                            <select  disableChosen="true" id="optionlocale">
                                <option></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            * <label id="db" for="optiondb"></label>
                        </td>
                        <td>
                            <select disableChosen="true" id="optiondb">
                                <option></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label id="snap" for="optionsnap"></label>
                        </td>
                        <td>
                            <input type="text" size="4" id="optionsnap"/>
                            <span class="small" id="optionsnapnotice"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label id="pattern" for="optionpattern"></label>
                        </td>
                        <td>
                            <input type="text" size="6" id="optionpattern"/>
                            <span class="small" id="optionpatternnotice"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label id="hide" for="optionhide"></label>
                        </td>
                        <td>
                            <input type="checkbox" id="optionhide"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            * <label id="vector" for="optionvector"></label>
                        </td>
                        <td>
                            <input type="checkbox" id="optionvector"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            * <label id="showsize" for="optionshowsize"></label>
                        </td>
                        <td>
                            <input type="checkbox" id="optionshowsize"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            * <label id="showtype" for="optionshowtype"></label>
                        </td>
                        <td>
                            <input type="checkbox" id="optionshowtype"/>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <hr/>

                * <span class="small" id="optionsnotice"></span>
            </div>

            <div id="io">
                <table>
                    <tbody>
                    <tr>
                        <td style="width:60%">
                            <fieldset>
                                <legend id="client"></legend>
                                <div id="singlerow">
                                    <input type="button" id="clientsave"/>
                                    <input type="button" id="clientload"/>
                                </div>
                                <div id="singlerow">
                                    <input type="button" id="clientlocalsave"/>
                                    <input type="button" id="clientlocalload"/>
                                    <input type="button" id="clientlocallist"/>
                                </div>
                                <div id="singlerow">
                                    <input type="button" id="dropboxsave"/><!-- may get hidden by dropBoxInit() -->
                                    <input type="button" id="dropboxload"/><!-- may get hidden by dropBoxInit() -->
                                    <input type="button" id="dropboxlist"/><!-- may get hidden by dropBoxInit() -->
                                </div>
                                <hr/>
                                <input type="button" id="clientsql"/>
                            </fieldset>
                        </td>
                        <td style="width:40%">
                            <fieldset>
                                <legend id="server"></legend>
                                <label for="backend" id="backendlabel"></label> <select  disableChosen="true" id="backend">
                                    <option></option>
                                </select>
                                <hr/>
                                <input type="button" id="serversave"/>
                                <input type="button" id="quicksave"/>
                                <input type="button" id="serverload"/>
                                <input type="button" id="serverlist"/>
                                <input type="button" id="serverimport"/>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <fieldset>
                                <legend id="output"></legend>
                                <textarea id="textarea" rows="1" cols="1"></textarea><!--modified by javascript later-->

                            </fieldset>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div id="keys">
                <fieldset>
                    <legend id="keyslistlabel"></legend>
                    <select disableChosen="true" id="keyslist">
                        <option></option>
                    </select>
                    <input type="button" id="keyadd"/>
                    <input type="button" id="keyremove"/>
                </fieldset>
                <fieldset>
                    <legend id="keyedit"></legend>
                    <table>
                        <tbody>
                        <tr>
                            <td>
                                <label for="keytype" id="keytypelabel"></label>
                                <select disableChosen="true" id="keytype">
                                    <option></option>
                                </select>
                            </td>
                            <td></td>
                            <td>
                                <label for="keyname" id="keynamelabel"></label>
                                <input type="text" id="keyname" size="10"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <hr/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="keyfields" id="keyfieldslabel"></label><br/>
                                <select disableChosen="true" id="keyfields" size="5" multiple="multiple">
                                    <option></option>
                                </select>
                            </td>
                            <td>
                                <input type="button" id="keyleft" value="&lt;&lt;"/><br/>
                                <input type="button" id="keyright" value="&gt;&gt;"/><br/>
                            </td>
                            <td>
                                <label for="keyavail" id="keyavaillabel"></label><br/>
                                <select  disableChosen="true" id="keyavail" size="5" multiple="multiple">
                                    <option></option>
                                </select>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </fieldset>
            </div>

            <div id="table">
                <table>
                    <tbody>
                    <tr>
                        <td><label  for="full_table">Full fied</label></td>
                        <td><input  type="checkbox" name="full_table" id="full_table"/></td>
                    </tr>

                    <tr>
                        <td>
                            <label id="tablenamelabel" for="tablename"></label>
                        </td>
                        <td>
                            <input class="list_table form-control" id="tablename" type="text"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label id="tablecommentlabel" for="tablecomment"></label>
                        </td>
                        <td>
                            <textarea rows="5" cols="40" id="tablecomment"></textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <input type="hidden" id="xml_output" name="xml_output" value="<?php echo $xml_input ?>" />
        </div>
    </div>

</div>


