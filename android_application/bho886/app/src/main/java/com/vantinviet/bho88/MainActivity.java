package com.vantinviet.bho88;

import android.annotation.TargetApi;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.res.Configuration;
import android.graphics.Color;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.os.Environment;
import android.support.v7.app.AppCompatActivity;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.ViewGroup;
import android.webkit.JavascriptInterface;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.ScrollView;
import android.widget.TextView;

import com.google.android.gms.appindexing.Action;
import com.google.android.gms.appindexing.AppIndex;
import com.google.android.gms.common.api.GoogleApiClient;
import com.vantinviet.bho88.libraries.cms.menu.JMenu;
import com.vantinviet.bho88.libraries.joomla.JFactory;
import com.vantinviet.bho88.libraries.legacy.application.JApplication;
import com.vantinviet.bho88.libraries.legacy.request.JRequest;

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
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class MainActivity extends AppCompatActivity {

    public static String host = "";
    /**
     * ATTENTION: This was auto-generated to implement the App Indexing API.
     * See https://g.co/AppIndexing/AndroidStudio for more information.
     */

    private EditText editTextId;
    private Button buttonGet;
    private TextView textViewResult;
    public Context context = this;
    private ProgressDialog loading;
    private int screen_size_width = 0;
    private int screen_size_height = 0;
    private JSONArray modules;
    public static String title = "BHO88";
    private boolean debug = false;
    /**
     * ATTENTION: This was auto-generated to implement the App Indexing API.
     * See https://g.co/AppIndexing/AndroidStudio for more information.
     */
    private GoogleApiClient client;
    private LinearLayout main_linear_layout;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setContentView(R.layout.activity_main);
        //Remove title bar
        JApplication app = JFactory.getApplication();
        app.context = context;
        app.activity = this;
        app.main_linear_layout = findViewById(R.id.info);
        JFactory.setContext(context);
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
        app.screen_size_width = width;
        app.screen_size_height = height;
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
        } else if (!host.contains(config.root_url)) {
            host = config.root_url + "/" + host;
        }
        app.host = host;

        System.out.println("---------host---------");
        System.out.println("host " + host);
        System.out.println("---------host---------");
        //ab.setTitle(title);
        WebViewClient web_view_client = new WebViewClient() {

            @Override
            public boolean shouldOverrideUrlLoading(WebView view, String url) {
                return false;
            }

            @Override
            public void onPageFinished(WebView view, String url) {

                view.loadUrl("javascript:HtmlViewer.showHTML" +
                        "(document.getElementsByTagName('body')[0].innerHTML);");
            }
        };

        WebView web_browser = JFactory.getWebBrowser();
        web_browser.getSettings().setJavaScriptEnabled(true);
        web_browser.getSettings().setSupportZoom(true);
        web_browser.getSettings().setBuiltInZoomControls(true);
        web_browser.setWebViewClient(web_view_client);
        web_browser.loadUrl(host);
        main_linear_layout = (LinearLayout) findViewById(R.id.info);
        web_browser.addJavascriptInterface(new MyJavaScriptInterfaceWebsite(), "HtmlViewer");


        //(new AsyncJsonElementViewLoader()).execute(host);

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
            if (json_string.equals("")) {
                return;
            }
            super.onPostExecute(json_string);
            dialog.dismiss();
            try {
                JApplication app = JFactory.getApplication();

                JSONObject json_object = new JSONObject();
                try {
                    json_object = new JSONObject(json_string);

                    if(json_object.has("link_redirect"))
                    {
                        String link_redirect=json_object.getString("link_redirect");
                        app.setRedirect(link_redirect);
                        return;
                    }

                    int root_id = json_object.getInt("root_id");
                    JSONObject children = json_object.getJSONObject("children");
                    app.modules = json_object.getJSONArray("modules");
                    JSONObject active_menu_item = json_object.getJSONObject("active_menu_item");
                    JSONArray list_menu_item = json_object.getJSONArray("list_menu_item");
                    JMenu JMenu = JFactory.getMenu();
                    JMenu.setMenuActive(active_menu_item);
                    JMenu.setItems(list_menu_item);
                    tree_recurse(root_id, children, null, null,main_linear_layout, 0, 0,999);

                    JSONObject request = json_object.getJSONObject("request");
                    JRequest jrequest= JFactory.getRequest();
                    System.out.println(jrequest);
                    jrequest.setRequest(request);

                } catch (JSONException e) {
                    e.printStackTrace();
                }

                //JApplication app=JFactory.getApplication();
                // app.execute(json_string, a_main_linear_layout);


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
            String link = params[0];


            String return_json = "";
            //String return_json = JApplication.get_content_website(link);
            return params[0];
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


    private class MyJavaScriptInterfaceWebsite {
        public MyJavaScriptInterfaceWebsite() {
        }

        @JavascriptInterface
        public void showHTML(String html) {


            final Pattern pattern = Pattern.compile("<android_response>(.+?)</android_response>");
            final Matcher matcher = pattern.matcher(html);
            matcher.find();
            System.out.println("html:" + html);
            System.out.println(matcher.group(1)); // Prints String I want to extract
            html = matcher.group(1);
            System.out.println("html:" + html);
            JApplication app = JFactory.getApplication();
            (new AsyncJsonElementViewLoader()).execute(html);

        }
    }


    public void tree_recurse(int root_id, JSONObject root, JSONObject children, JSONObject prev_children, LinearLayout object_parent, int width, int level, int max_level) {
        JApplication app = JFactory.getApplication();
        if (width == 0) {
            width = screen_size_width;
        }

        //add new  scrollview
        if (level == 0) {
            Random rnd = new Random();
            int currentStrokeColor = 0;
            int scroll_view_id = root_id;
            //add new  scrollview
            ScrollView scroll_view = new ScrollView(app.context);
            scroll_view.setId(scroll_view_id);
            scroll_view.setLayoutParams(new ViewGroup.LayoutParams(screen_size_width, screen_size_height));
            if (debug) {
                currentStrokeColor = Color.argb(255, rnd.nextInt(256), rnd.nextInt(256), rnd.nextInt(256));
                scroll_view.setBackgroundColor(currentStrokeColor);

            }


            //add new row liner layout
            LinearLayout row_linear_layout = new LinearLayout(app.context);
            if (debug) {
                currentStrokeColor = Color.argb(255, rnd.nextInt(256), rnd.nextInt(256), rnd.nextInt(256));
                row_linear_layout.setBackgroundColor(currentStrokeColor);

            }
            object_parent.addView(scroll_view);

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
                    LinearLayout a_object_parent = app.render_element_row(object_parent, a_object, width);
                    this.tree_recurse(root_id, root, a_object, prev_object, a_object_parent, width, level1,max_level);
                } catch (JSONException e) {
                    System.out.println(e.toString());
                }
            }
        } else {

            int a_root_id = 0;
            LinearLayout a_object_parent = new LinearLayout(app.context);
            if (children.has("id")) {
                try {
                    a_root_id = children.getInt("id");
                    String type = children.getString("type");
                    switch (type) {
                        case "row":
                            a_object_parent = app.render_element_row(object_parent, children, width);
                            break;
                        case "column":
                            a_object_parent = app.render_element_column(object_parent, children, prev_children, width / 12);
                            int object_width = children.getInt("width");
                            width = (width / 12) * object_width;
                            break;
                        case "input":
                            EditText input_object = app.render_element_edit_text(object_parent, children);
                            break;
                        case "banner_rotator":
                            //app.elementBanner_RotatorHelper.render_banner_rotator(app.context, object_parent, children, width);
                            break;
                        case "link_image":
                            //app.element_link_image_helper.render_element(app.context, object_parent, children, width);
                            break;
                        case "grid":
                            //app.element_grid_helper.render_element(app.context, object_parent, children, width);
                            break;
                        default:
                            Button return_object = app.render_element_button(object_parent, children);
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
                            this.tree_recurse(a_root_id, root, a_object, prev_object, a_object_parent, width, level1,max_level);
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

}
