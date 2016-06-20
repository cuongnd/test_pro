package com.vantinviet.bho88.libraries.joomla.application;

import android.app.ProgressDialog;
import android.content.res.Resources;
import android.graphics.Color;
import android.os.AsyncTask;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.LinearLayout;

import com.beardedhen.androidbootstrap.BootstrapEditText;
import com.beardedhen.androidbootstrap.api.defaults.DefaultBootstrapBrand;
import com.vantinviet.bho88.libraries.cms.application.JApplicationCms;
import com.vantinviet.bho88.libraries.cms.component.JComponentHelper;
import com.vantinviet.bho88.libraries.joomla.JFactory;
import com.vantinviet.bho88.libraries.joomla.input.JInput;
import com.vantinviet.bho88.libraries.legacy.application.JApplication;
import com.vantinviet.bho88.modules.mod_menu.modMenuHelper;
import com.vantinviet.bho88.modules.mod_virtuemart_category.mod_virtuemart_category_helper;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.Random;

/**
 * Created by cuongnd on 6/18/2016.
 */
public class JApplicationBase {
    public JInput input;
    public JSONArray modules;
    private boolean debug=false;
    public int screen_size_width;
    public int screen_size_height;
    public String host;
    public View main_linear_layout=null;
    private LinearLayout linear_layout_component;


    public LinearLayout render_element_row(LinearLayout parent_object, JSONObject object, int width) {
        JApplication app=JFactory.getApplication();
        LinearLayout linear_layout = new LinearLayout(app.context);
        try {
            int id = object.getInt("id");
            String type = object.getString("type");
            linear_layout.setId(id);
            Random rnd = new Random();
            if (debug) {
                int currentStrokeColor = Color.argb(255, rnd.nextInt(256), rnd.nextInt(256), rnd.nextInt(256));
                linear_layout.setBackgroundColor(currentStrokeColor);
            }
            linear_layout.setLayoutParams(new ViewGroup.LayoutParams(width, ViewGroup.LayoutParams.WRAP_CONTENT));
            linear_layout.setOrientation(LinearLayout.HORIZONTAL);
            if (debug) {
                //linear_layout=a_render_element_text_view("row-"+id+":"+Integer.toString(width),id,linear_layout);
            }
            ((LinearLayout) parent_object).addView(linear_layout);

        } catch (JSONException e) {
            e.printStackTrace();
        }
        return linear_layout;
    }
    public LinearLayout render_element_column(LinearLayout parent_object, JSONObject object, JSONObject prev_object, int width) {
        JApplication app=JFactory.getApplication();
        LinearLayout linear_layout = new LinearLayout(app.context);
        try {
            int id = object.getInt("id");
            int object_width = object.getInt("width");
            int a_width = width * object_width;
            String type = object.getString("type");
            linear_layout.setId(id);
            Resources resource = app.context.getResources();
            Random rnd = new Random();
            if (debug) {
                int currentStrokeColor = Color.argb(255, rnd.nextInt(256), rnd.nextInt(256), rnd.nextInt(256));
                linear_layout.setBackgroundColor(currentStrokeColor);
            }
            linear_layout.setOrientation(LinearLayout.VERTICAL);
            //offset
            try {
                int prev_id = 0;
                if (prev_object.has("id")) {
                    prev_id = prev_object.getInt("id");
                }
                String prev_type = "";

                if (prev_object.has("type")) {
                    prev_type = prev_object.getString("type");
                }
                int offset = 0;
                if (prev_type.toLowerCase().contains("column")) {
                    offset = object.getInt("gs_x") - (prev_object.getInt("gs_x") + prev_object.getInt("width"));
                    linear_layout.setPadding(width * offset, 0, 0, 0);
                } else {
                    offset = object.getInt("gs_x");
                    linear_layout.setPadding(width * offset, 0, 0, 0);
                }
                a_width += width * offset;
            } catch (JSONException e) {
                e.printStackTrace();
            }
            //linear_layout = a_render_element_text_view("column-" + id + ":" + Integer.toString(a_width), id, linear_layout);
            linear_layout.setLayoutParams(new ViewGroup.LayoutParams(a_width, ViewGroup.LayoutParams.WRAP_CONTENT));

            String position = object.getString("position");
            if (position.toLowerCase().contains("position-component")) {

                String host_tmpl_component = host.replaceAll("os=android&", "");
                host_tmpl_component = host_tmpl_component + "&tmpl=android&layout=android";
                linear_layout_component=linear_layout;
                //component_params params = new component_params(host_tmpl_component, linear_layout);






                //(new AsyncComponentViewLoader()).execute(params);
                //((LinearLayout) linear_layout).addView(edit_text);
            } else {

                for (int i = 0; i < modules.length(); i++) {
                    JSONObject module = modules.getJSONObject(i);
                    String module_position = module.getString("position");
                    if (module_position.toLowerCase().contains("position-" + String.valueOf(id))) {
                        String module_name = module.getString("module");
                        switch (module_name) {
                            case "mod_menu":
                                modMenuHelper.render_menu(app.context, linear_layout, module, width);
                                break;
                            case "mod_virtuemart_category":
                                mod_virtuemart_category_helper.render_module(app.context, linear_layout, module, width);
                                break;
                            default:
                                //Button return_object = this.render_element_button(object_parent, children);
                                //code here
                                break;
                        }
                    }
                }
            }

            ((LinearLayout) parent_object).addView(linear_layout);

        } catch (JSONException e) {
            e.printStackTrace();
        }
        return linear_layout;
    }
    public BootstrapEditText render_element_edit_text(View parent_object, JSONObject object) {
        JApplication app=JFactory.getApplication();
        BootstrapEditText edit_text = new BootstrapEditText(app.context);

        try {

            int id = object.getInt("id");
            String type = object.getString("type");
            edit_text.setId(id);
            edit_text.setBootstrapBrand(DefaultBootstrapBrand.PRIMARY);
            edit_text.setText(type + "-" + Integer.toString(id));
            Resources resource = app.context.getResources();
            ((LinearLayout) parent_object).addView(edit_text);

        } catch (JSONException e) {
            e.printStackTrace();
        }
        return edit_text;
    }
    public Button render_element_button(View parent_object, JSONObject object) {
        JApplication app=JFactory.getApplication();
        Button btn = new Button(app.context);
        try {

            int id = object.getInt("id");
            String type = object.getString("type");
            btn.setId(id);
            btn.setText(type + "-" + Integer.toString(id));
            Resources resource = app.context.getResources();
            ((LinearLayout) parent_object).addView(btn);

        } catch (JSONException e) {
            e.printStackTrace();
        }
        return btn;
    }

    public void execute_component(String json_component_string) {
        JApplication app=JFactory.getApplication();
        JApplicationCms.execute_component(linear_layout_component,json_component_string);
    }

    private static class component_params {
        String link;
        LinearLayout linear_layout;

        component_params(String link, LinearLayout linear_layout) {
            this.link = link;
            this.linear_layout = linear_layout;
        }
    }
    private static class component_response {
        String link;
        LinearLayout linear_layout;
        String content;

        component_response(String link, LinearLayout linear_layout,String content) {
            this.link = link;
            this.linear_layout = linear_layout;
            this.content = content;
        }
    }
    private class AsyncComponentViewLoader extends AsyncTask<component_params, Void, component_response> {
        JApplication app=JFactory.getApplication();
        private final ProgressDialog dialog = new ProgressDialog(app.context);

        @Override
        protected void onPostExecute(component_response com_response) {
            super.onPostExecute(com_response);
            dialog.dismiss();
            try {
                JApplicationCms.execute_component(com_response.linear_layout, com_response.content);
            } catch (Throwable t) {
                t.printStackTrace();
            }

        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            dialog.setMessage("Downloading component...");
            dialog.show();
        }

        @Override
        protected component_response doInBackground(component_params... component_params) {

            component_response com_response=new component_response(component_params[0].link, component_params[0].linear_layout,"");
            try {
                String link=component_params[0].link;
                String content= JComponentHelper.getContentComponent(link);

                com_response = new component_response(component_params[0].link, component_params[0].linear_layout,content);

            } catch (Throwable t) {
                t.printStackTrace();
            }
            return com_response;
        }


    }




}
