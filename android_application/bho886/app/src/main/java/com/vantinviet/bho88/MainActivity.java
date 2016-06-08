package com.vantinviet.bho88;

import android.annotation.TargetApi;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.res.Configuration;
import android.content.res.Resources;
import android.graphics.Color;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.os.Environment;
import android.support.v7.app.AppCompatActivity;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.ScrollView;
import android.widget.TextView;

import com.beardedhen.androidbootstrap.BootstrapEditText;
import com.beardedhen.androidbootstrap.api.defaults.DefaultBootstrapBrand;
import com.google.android.gms.appindexing.Action;
import com.google.android.gms.appindexing.AppIndex;
import com.google.android.gms.common.api.GoogleApiClient;
import com.vantinviet.bho88.libraries.android.http.JSONParser;
import com.vantinviet.bho88.libraries.cms.application.application_cms;
import com.vantinviet.bho88.libraries.cms.component.JComponentHelper;
import com.vantinviet.bho88.libraries.cms.menu.menu;
import com.vantinviet.bho88.libraries.joomla.factory;
import com.vantinviet.bho88.media.element.slider.banner_rotator.elementBanner_RotatorHelper;
import com.vantinviet.bho88.media.element.ui.grid.element_grid_helper;
import com.vantinviet.bho88.media.element.ui.link_image.element_link_image_helper;
import com.vantinviet.bho88.modules.mod_menu.modMenuHelper;
import com.vantinviet.bho88.modules.mod_virtuemart_category.mod_virtuemart_category_helper;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.OutputStreamWriter;
import java.io.UnsupportedEncodingException;
import java.util.Random;

public class MainActivity extends AppCompatActivity {

    public static String host = "";
    /**
     * ATTENTION: This was auto-generated to implement the App Indexing API.
     * See https://g.co/AppIndexing/AndroidStudio for more information.
     */

    private EditText editTextId;
    private Button buttonGet;
    private TextView textViewResult;
    private Context context = this;
    private ProgressDialog loading;
    private int screen_size_width = 0;
    private int screen_size_height = 0;
    private JSONArray modules;
    public static String title = "BHO88";
    private boolean debug = true;
    /**
     * ATTENTION: This was auto-generated to implement the App Indexing API.
     * See https://g.co/AppIndexing/AndroidStudio for more information.
     */
    private GoogleApiClient client;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setContentView(R.layout.activity_main);
        //Remove title bar

        factory.setContext(context);
        DisplayMetrics metrics = new DisplayMetrics();
        getWindowManager().getDefaultDisplay().getMetrics(metrics);
        int screenDensity = (int) metrics.density;
        int screenDensityDPI = metrics.densityDpi;
        float screenscaledDensity = metrics.scaledDensity;
        int width = metrics.widthPixels;
        int height = metrics.heightPixels;


        System.out.println("Screen Density=" + screenDensity + "\n"
                + "Screen DensityDPI=" + screenDensityDPI + "\n"
                + "Screen Scaled DensityDPI=" + screenscaledDensity + "\n"
                + "Height=" + height + "\n"
                + "Width=" + width);
        screen_size_width = width;
        screen_size_height = height;
        if (screenDensity == 0) {
            screenDensity = 1;
        }
        String screenSize = Integer.toString(width / screenDensity) + "x" + Integer.toString(height);
        System.out.println(width / screenDensity);
        String local_version = config.get_version();

        config.screen_size_width = screen_size_width;
        config.screen_size_height = screen_size_height;

        config.screenDensity = screenDensity;
        if (host.equals("")) {
            String test_page = "&Itemid=433";
            test_page = "";
            host = config.root_url + "/index.php?os=android&screenSize=" + screenSize + "&version=" + local_version + test_page;
        } else {
            host = config.root_url + host;
        }


        //ab.setTitle(title);

        System.out.println(host);
        (new AsyncJsonElementViewLoader()).execute(host);

        // ATTENTION: This was auto-generated to implement the App Indexing API.
        // See https://g.co/AppIndexing/AndroidStudio for more information.
        client = new GoogleApiClient.Builder(this).addApi(AppIndex.API).build();
    }

    @Override
    public void onStart() {
        super.onStart();

        // ATTENTION: This was auto-generated to implement the App Indexing API.
        // See https://g.co/AppIndexing/AndroidStudio for more information.
        client.connect();
        Action viewAction = Action.newAction(
                Action.TYPE_VIEW, // TODO: choose an action type.
                "Main Page", // TODO: Define a title for the content shown.
                // TODO: If you have web page content that matches this app activity's content,
                // make sure this auto-generated web page URL is correct.
                // Otherwise, set the URL to null.
                Uri.parse("http://host/path"),
                // TODO: Make sure this auto-generated app deep link URI is correct.
                Uri.parse("android-app://com.vantinviet.bho88/http/host/path")
        );
        AppIndex.AppIndexApi.start(client, viewAction);
    }

    @Override
    public void onStop() {
        super.onStop();

        // ATTENTION: This was auto-generated to implement the App Indexing API.
        // See https://g.co/AppIndexing/AndroidStudio for more information.
        Action viewAction = Action.newAction(
                Action.TYPE_VIEW, // TODO: choose an action type.
                "Main Page", // TODO: Define a title for the content shown.
                // TODO: If you have web page content that matches this app activity's content,
                // make sure this auto-generated web page URL is correct.
                // Otherwise, set the URL to null.
                Uri.parse("http://host/path"),
                // TODO: Make sure this auto-generated app deep link URI is correct.
                Uri.parse("android-app://com.vantinviet.bho88/http/host/path")
        );
        AppIndex.AppIndexApi.end(client, viewAction);
        client.disconnect();
    }

    private class AsyncJsonElementViewLoader extends AsyncTask<String, Void, String> {
        private final ProgressDialog dialog = new ProgressDialog(MainActivity.this);

        @Override
        protected void onPostExecute(String json_string) {
            super.onPostExecute(json_string);
            dialog.dismiss();
            try {
                JSONObject json_object = new JSONObject(json_string);

                String version = json_object.getString("version");
                String local_version = config.get_version();
                if (version != local_version) {
                    int root_id = json_object.getInt("root_id");
                    JSONObject children = json_object.getJSONObject("children");
                    modules = json_object.getJSONArray("modules");
                    JSONObject active_menu_item = json_object.getJSONObject("active_menu_item");
                    JSONArray list_menu_item = json_object.getJSONArray("list_menu_item");
                    menu menu= factory.getMenu();
                    menu.setMenuActive(active_menu_item);
                    menu.setItems(list_menu_item);
                    set_cache_json_by_screen_size(screen_size_width, screen_size_height, 0, json_object.toString());
                    tree_recurse(root_id, children, null, null, null, 0, 0);

                } else {
                    String cache_json = get_cache_json_by_screen_size(screen_size_width, screen_size_height, 0);
                    if (cache_json != "") {
                        JSONObject json = new JSONObject(cache_json);
                        int root_id = json.getInt("root_id");
                        modules = json_object.getJSONArray("modules");
                        JSONObject children = json.getJSONObject("children");
                        JSONObject active_menu_item = json.getJSONObject("active_menu_item");
                        JSONArray list_menu_item = json_object.getJSONArray("list_menu_item");
                        menu menu= factory.getMenu();
                        menu.setItems(list_menu_item);
                        menu.setMenuActive(active_menu_item);
                        tree_recurse(root_id, children, null, null, null, 0, 0);
                    }


                }
            } catch (Throwable t) {
                t.printStackTrace();
            }

        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            dialog.setMessage("Downloading element...");
            dialog.show();
        }

        @Override
        protected String doInBackground(String... params) {
            String return_json = "";
            try {
                // instantiate our json parser
                JSONParser jParser = new JSONParser();
                // get json string from url
                JSONObject json = jParser.getJSONFromUrl(params[0]);
                System.out.println(json.toString());
                return_json = json.toString();

            } catch (Throwable t) {
                t.printStackTrace();
            }
            return return_json;
        }


    }

    private class AsyncComponentViewLoader extends AsyncTask<component_params, Void, component_response> {
        private final ProgressDialog dialog = new ProgressDialog(MainActivity.this);

        @Override
        protected void onPostExecute(component_response com_response) {
            super.onPostExecute(com_response);
            dialog.dismiss();
            try {
                application_cms.execute_component(context, com_response.linear_layout, host, com_response.content);
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

    @Override
    public void onConfigurationChanged(Configuration newConfig) {
        super.onConfigurationChanged(newConfig);
        System.out.println("ratator");
    }

    @TargetApi(Build.VERSION_CODES.KITKAT)
    private String get_cache_json_by_screen_size(int screen_size_width, int screen_size_height, int menu_item_id) throws UnsupportedEncodingException {
        String cache_file = "android_" + String.valueOf(menu_item_id) + "_" + String.valueOf(screen_size_width) + "X" + String.valueOf(screen_size_height);
        /*byte[] data = cache_file.getBytes("UTF-8");
        cache_file = Base64.encodeToString(data, Base64.DEFAULT);*/
        cache_file += ".txt";
        String content = "";
        File root = new File(Environment.getExternalStorageDirectory(), "cache");
        if (!root.exists()) {
            root.mkdirs();
        }
        File a_cache_file = new File(root, cache_file);
        //System.out.println(a_cache_file);
        if (a_cache_file.exists()) {

            try {
                BufferedReader br = new BufferedReader(new FileReader(a_cache_file));
                try {
                    StringBuilder sb = new StringBuilder();
                    String line = br.readLine();

                    while (line != null) {
                        sb.append(line);
                        sb.append(System.lineSeparator());
                        line = br.readLine();
                    }
                    content = sb.toString();
                } finally {
                    br.close();
                }


            } catch (FileNotFoundException e) {
                return "";
            } catch (UnsupportedEncodingException e) {
                return "";
            } catch (IOException e) {
                return "";
            }

        }
        return content;
    }

    private void set_cache_json_by_screen_size(int screen_size_width, int screen_size_height, int menu_item_id, String content) throws UnsupportedEncodingException {
        String cache_file = "android_" + String.valueOf(menu_item_id) + "_" + String.valueOf(screen_size_width) + "X" + String.valueOf(screen_size_height);
       /* byte[] data = cache_file.getBytes("UTF-8");
        cache_file = Base64.encodeToString(data, Base64.DEFAULT);*/
        //this.generateNoteOnSD(cache_file + ".txt", content, "cache");
    }

    public void generateNoteOnSD(String sFileName, String sBody, String folder) {
        String error = "";
        try {
            File root = new File(Environment.getExternalStorageDirectory(), folder);
            if (!root.exists()) {
                root.mkdirs();
            }
            File gpxfile = new File(root, sFileName);
            FileWriter writer = new FileWriter(gpxfile);
            writer.append(sBody);
            writer.flush();
            writer.close();
            //Toast.makeText(this, "Saved", Toast.LENGTH_SHORT).show();
        } catch (IOException e) {
            e.printStackTrace();
            error = e.getMessage();

        }
    }

    private void writeToFile(String data) {
        try {
            OutputStreamWriter outputStreamWriter = new OutputStreamWriter(context.openFileOutput("config.txt", Context.MODE_PRIVATE));
            outputStreamWriter.write(data);
            outputStreamWriter.close();
        } catch (IOException e) {
            Log.e("Exception", "File write failed: " + e.toString());
        }
    }


    public void tree_recurse(int root_id, JSONObject root, JSONObject children, JSONObject prev_children, LinearLayout object_parent, int width, int level) {
        if (width == 0) {
            width = screen_size_width;
        }
        if (object_parent == null) {
            View main_linear_layout = findViewById(R.id.info);
            Random rnd = new Random();
            int currentStrokeColor = 0;
            int scroll_view_id = root_id;
            //add new  scrollview
            ScrollView scroll_view = new ScrollView(MainActivity.this);
            scroll_view.setId(scroll_view_id);
            scroll_view.setLayoutParams(new ViewGroup.LayoutParams(screen_size_width, screen_size_height));
            if (debug) {
                currentStrokeColor = Color.argb(255, rnd.nextInt(256), rnd.nextInt(256), rnd.nextInt(256));
                scroll_view.setBackgroundColor(currentStrokeColor);

            }

            ((LinearLayout) main_linear_layout).addView(scroll_view);

            //add new row liner layout
            LinearLayout row_linear_layout = new LinearLayout(MainActivity.this);
            if (debug) {
                currentStrokeColor = Color.argb(255, rnd.nextInt(256), rnd.nextInt(256), rnd.nextInt(256));
                row_linear_layout.setBackgroundColor(currentStrokeColor);

            }

            row_linear_layout.setLayoutParams(new ViewGroup.LayoutParams(width, ViewGroup.LayoutParams.WRAP_CONTENT));
            row_linear_layout.setOrientation(LinearLayout.VERTICAL);
            scroll_view.addView(row_linear_layout);
            object_parent = ((LinearLayout) row_linear_layout);
            object_parent.setOrientation(LinearLayout.VERTICAL);
        }
        int level1 = level + 1;
        if (level == 0) {


            JSONArray jsonArray = new JSONArray();
            try {
                jsonArray = root.getJSONArray(Integer.toString(root_id));
            } catch (JSONException e) {
            }


            for (int i = 0; i < jsonArray.length(); i++) {
                JSONObject a_children;
                try {
                    JSONObject a_object = jsonArray.getJSONObject(i);

                    JSONObject prev_object = new JSONObject();
                    if (i != 0) {
                        prev_object = jsonArray.getJSONObject(i - 1);
                    } else {
                        prev_object = new JSONObject();
                    }
                    int a_root_id = a_object.getInt("id");
                    LinearLayout a_object_parent = this.render_element_row(object_parent, a_object, width);
                    this.tree_recurse(root_id, root, a_object, prev_object, a_object_parent, width, level1);
                } catch (JSONException e) {
                    System.out.println(e.toString());
                }
            }
        } else {

            int a_root_id = 0;
            LinearLayout a_object_parent = new LinearLayout(MainActivity.this);
            if (children.has("id")) {
                try {
                    a_root_id = children.getInt("id");
                    String type = children.getString("type");
                    switch (type) {
                        case "row":
                            a_object_parent = this.render_element_row(object_parent, children, width);
                            break;
                        case "column":
                            a_object_parent = this.render_element_column(object_parent, children, prev_children, width / 12);
                            int object_width = children.getInt("width");
                            width = (width / 12) * object_width;
                            break;
                        /*case "input":
                            EditText input_object = this.render_element_edit_text(object_parent, children);*/
                        case "input":
                            EditText input_object = this.render_element_edit_text(object_parent, children);
                            break;
                        case "banner_rotator":
                            elementBanner_RotatorHelper.render_banner_rotator(context, object_parent, children, width);
                            break;
                        case "link_image":
                            element_link_image_helper.render_element(context, object_parent, children, width);
                            break;
                        case "grid":
                            element_grid_helper.render_element(context, object_parent, children, width);
                            break;
                        default:
                            Button return_object = this.render_element_button(object_parent, children);
                            //code here
                            break;
                    }
                } catch (JSONException e) {
                    System.out.println(e.toString());
                }
            }
            try {
                JSONArray a_children = root.getJSONArray(Integer.toString(a_root_id));

                if (a_children != null && a_children.length() > 0) {
                    for (int i = 0; i < a_children.length(); i++) {
                        try {
                            JSONObject prev_object = new JSONObject();
                            if (i != 0) {
                                prev_object = a_children.getJSONObject(i - 1);
                            } else {
                                prev_object = new JSONObject();
                            }
                            JSONObject a_object = a_children.getJSONObject(i);
                            this.tree_recurse(a_root_id, root, a_object, prev_object, a_object_parent, width, level1);
                        } catch (JSONException e) {
                            System.out.println(e.toString());
                        }
                    }
                }
            } catch (JSONException e) {
                System.out.println(e.toString());
            }

        }
    }


    public LinearLayout render_element_row(LinearLayout parent_object, JSONObject object, int width) {
        LinearLayout linear_layout = new LinearLayout(MainActivity.this);
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

    public LinearLayout render_element_column(LinearLayout parent_object, JSONObject object, JSONObject prev_object, int width) {

        LinearLayout linear_layout = new LinearLayout(MainActivity.this);
        try {
            int id = object.getInt("id");
            int object_width = object.getInt("width");
            int a_width = width * object_width;
            String type = object.getString("type");
            linear_layout.setId(id);
            Resources resource = context.getResources();
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
                component_params params = new component_params(host_tmpl_component, linear_layout);
                (new AsyncComponentViewLoader()).execute(params);
                //((LinearLayout) linear_layout).addView(edit_text);
            } else {

                for (int i = 0; i < modules.length(); i++) {
                    JSONObject module = modules.getJSONObject(i);
                    String module_position = module.getString("position");
                    if (module_position.toLowerCase().contains("position-" + String.valueOf(id))) {
                        String module_name = module.getString("module");
                        switch (module_name) {
                            case "mod_menu":
                                modMenuHelper.render_menu(context, linear_layout, module, width);
                                break;
                            case "mod_virtuemart_category":
                                mod_virtuemart_category_helper.render_module(context, linear_layout, module, width);
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

    public Button render_element_button(View parent_object, JSONObject object) {
        Button btn = new Button(MainActivity.this);
        try {

            int id = object.getInt("id");
            String type = object.getString("type");
            btn.setId(id);
            btn.setText(type + "-" + Integer.toString(id));
            Resources resource = context.getResources();
            ((LinearLayout) parent_object).addView(btn);

        } catch (JSONException e) {
            e.printStackTrace();
        }
        return btn;
    }

    public BootstrapEditText render_element_edit_text(View parent_object, JSONObject object) {
        BootstrapEditText edit_text = new BootstrapEditText(MainActivity.this);

        try {

            int id = object.getInt("id");
            String type = object.getString("type");
            edit_text.setId(id);
            edit_text.setBootstrapBrand(DefaultBootstrapBrand.PRIMARY);
            edit_text.setText(type + "-" + Integer.toString(id));
            Resources resource = context.getResources();
            ((LinearLayout) parent_object).addView(edit_text);

        } catch (JSONException e) {
            e.printStackTrace();
        }
        return edit_text;
    }


}
