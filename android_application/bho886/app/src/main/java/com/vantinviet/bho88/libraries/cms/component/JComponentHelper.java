package com.vantinviet.bho88.libraries.cms.component;

import android.content.Context;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;

import com.vantinviet.bho88.R;
import com.vantinviet.bho88.configuration.configuration;
import com.vantinviet.bho88.libraries.android.registry.JRegistry;
import com.vantinviet.bho88.libraries.cms.menu.JMenu;
import com.vantinviet.bho88.libraries.joomla.cache.cache;
import com.vantinviet.bho88.libraries.joomla.JFactory;
import com.vantinviet.bho88.libraries.joomla.form.JFormField;
import com.vantinviet.bho88.libraries.legacy.request.JRequest;
import com.vantinviet.bho88.libraries.utilities.md5;
import com.vantinviet.bho88.libraries.utilities.utilities;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import de.codecrafters.tableview.SortableTableView;
import de.codecrafters.tableview.TableDataAdapter;
import de.codecrafters.tableview.listeners.TableDataClickListener;
import de.codecrafters.tableview.toolkit.SimpleTableHeaderAdapter;
import de.codecrafters.tableview.toolkit.SortStateViewProviders;
import de.codecrafters.tableview.toolkit.TableDataRowColorizers;

/**
 * Created by cuongnd on 6/8/2016.
 */
public class JComponentHelper {
    public static Map<String, String> content_component =new HashMap<String, String>();
    public static List<String> columns;
    public static View linear_layout;

    public static String getContentComponent(String link) {
        System.out.println(link);
        configuration config= JFactory.getConfig();
        String content ="";
        String md5_link= md5.encryptMD5(link);
        int caching=config.caching;
        if(caching==1)
        {

            content= cache.get_content_component(md5_link);
            if(content == null || content.isEmpty()){
                content = utilities.callURL(link);
                cache.set_content_component(md5_link, content);
            }
            return content;

        }else {
            content = content_component.get(md5_link);
            if(content == null || content.isEmpty()){
                content = utilities.callURL(link);
                content_component.put(md5_link,content);
            }
        }
        return content;
    }

    public static void renderComponent(Context context, JSONObject json_element, LinearLayout linear_layout) throws JSONException {

        linear_layout=linear_layout;
        JMenu menu=JFactory.getMenu();
        JSONObject menu_active=menu.getMenuActive();
        JRegistry menu_active_params = JRegistry.getParams(menu_active);
        String android_render=menu_active_params.get("android_render","auto","String");
        if(android_render.equals("auto"))
        {
            auto_render_component(context,json_element,linear_layout);
        }else{
            customizable_render_component(context,json_element,linear_layout);
        }

    }

    private static void customizable_render_component(Context context, JSONObject json_element, LinearLayout linear_layout) {
        JRequest jrequest= JFactory.getRequest();
        JSONObject request=jrequest.getRequest();
    }

    private static void auto_render_component(Context context, JSONObject json_element, LinearLayout linear_layout) throws JSONException {
        JMenu menu=JFactory.getMenu();
        JSONObject menu_active=menu.getMenuActive();
        JRegistry menu_active_params = JRegistry.getParams(menu_active);
        String android_render_form_type=menu_active_params.get("android_render_form_type","list","String");
        System.out.println("android_render_form_type:" + android_render_form_type);
        if(android_render_form_type.equals("list"))
        {
            auto_render_component_list_type(context,json_element,linear_layout);
        }else{
            auto_render_component_form_type(context,json_element, linear_layout);
        }

    }

    private static void auto_render_component_form_type(Context context, JSONObject json_element, View linear_layout) {

        abstract class subRenderComponent {
            public abstract void render_element(JSONObject array_element,String root_element,View linear_layout, int level, int max_level) throws JSONException;
        }
        final subRenderComponent subRenderComponent = new  subRenderComponent() {

            @Override public  void render_element(JSONObject json_element,String root_element,View linear_layout,int level,int max_level) throws JSONException {
                int level1=level+1;
                Iterator<?> keys = json_element.keys();
                while( keys.hasNext() ) {
                    String key = (String)keys.next();
                    System.out.println(json_element.get(key));
                    if ( json_element.get(key) instanceof JSONObject ) {
                        JSONObject a_object=(JSONObject)json_element.get(key);
                        render_element(a_object, root_element, linear_layout, level1, max_level);
                    }
                }



            }
        };
        String root_element="html";
        //subRenderComponent.render_element(json_element, root_element, linear_layout, 0, 999);

    }
    private static class TableClickListener implements TableDataClickListener {


        @Override
        public void onDataClicked(int rowIndex, Object clickedData) {
            String item_string= clickedData.toString();
            //Toast.makeText(MainActivity., item_string, Toast.LENGTH_SHORT).show();
        }
    }
    private static void auto_render_component_list_type(final Context context, JSONObject json_element,final LinearLayout linear_layout) throws JSONException {
        SortableTableView<? extends Object> table_view = new SortableTableView<Object>(context);
        String columnFields=json_element.getString("columnFields");
        final JSONArray fields=new JSONArray(columnFields);
        List<String> list_column_title = new ArrayList<String>();
        columns = new ArrayList<String>();
        for (int i = 0; i < fields.length(); i++) {
            JSONObject field = fields.getJSONObject(i);
            if(field.has("label"))
            {
                String column_title = field.getString("label");
                list_column_title.add(column_title);
            }

            if(field.has("name"))
            {
                String column_name = field.getString("name");
                columns.add(column_name);

            }

        }
        System.out.println(list_column_title.toString());
        String[] array_column_title = new String[list_column_title.size()];
        list_column_title.toArray(array_column_title);
        SimpleTableHeaderAdapter simpleTableHeaderAdapter = new SimpleTableHeaderAdapter(context,array_column_title );

        simpleTableHeaderAdapter.setTextColor(context.getResources().getColor(R.color.table_header_text));
        table_view.setHeaderAdapter(simpleTableHeaderAdapter);

        int rowColorEven = context.getResources().getColor(R.color.table_data_row_even);
        int rowColorOdd = context.getResources().getColor(R.color.table_data_row_odd);
        table_view.setDataRowColoriser(TableDataRowColorizers.alternatingRows(rowColorEven, rowColorOdd));
        table_view.setHeaderSortStateViewProvider(SortStateViewProviders.brightArrows());

        table_view.setColumnWeight(0, 2);
        table_view.setColumnWeight(1, 3);
        table_view.setColumnWeight(2, 3);
        table_view.setColumnWeight(3, 2);
        table_view.setLayoutParams(new ViewGroup.LayoutParams(android.view.ViewGroup.LayoutParams.FILL_PARENT, 1000));
        JSONArray items=json_element.getJSONArray("items");
        List<String> list_data = new ArrayList<String>();
        for (int i = 0; i < items.length(); i++) {
            JSONObject row = items.getJSONObject(i);
            list_data.add(row.toString());
        }

        table_view.setDataAdapter(new TableDataAdapter(context, list_data) {
            @Override
            public View getCellView(int rowIndex, int columnIndex, ViewGroup parentView) {
                Object item = getRowData(rowIndex);
                JSONObject item_json_object = new JSONObject();
                try {
                    item_json_object = new JSONObject(item.toString());
                } catch (JSONException e) {
                    e.printStackTrace();
                }
                View view_field= new View(context);
                if(columnIndex<fields.length()) {
                    JSONObject field = null;
                    try {
                        field = (JSONObject)fields.getJSONObject(columnIndex);
                        if(field.has("type")&&field.has("name")&&field.has("label"))
                        {
                            String type = field.getString("type");
                            String name = field.getString("name");
                            String label = field.getString("label");
                            String group="";
                            JSONObject value=new JSONObject();
                            JFormField formField=JFormField.getFormField( name,group,value);
                            view_field= formField.renderField(field,type, name,group, label);
                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                    }



                }
                return view_field;
            }
        });
        table_view.addDataClickListener(new TableClickListener());



        ((LinearLayout) linear_layout).addView(table_view);

        System.out.println(columnFields);
        abstract class subRenderComponent {
            public abstract void render_element(JSONObject array_element,String root_element,View linear_layout, int level, int max_level) throws JSONException;
        }
        final subRenderComponent subRenderComponent = new  subRenderComponent() {

            @Override public  void render_element(JSONObject json_element,String root_element,View linear_layout,int level,int max_level) throws JSONException {
                int level1=level+1;
                Iterator<?> keys = json_element.keys();
                while( keys.hasNext() ) {
                    String key = (String)keys.next();
                    System.out.println(json_element.get(key));
                    if ( json_element.get(key) instanceof JSONObject ) {
                        JSONObject a_object=(JSONObject)json_element.get(key);
                        render_element(a_object, root_element, linear_layout, level1, max_level);
                    }
                }



            }
        };
        String root_element="html";
        //subRenderComponent.render_element(json_element, root_element, linear_layout, 0, 999);
    }
}
