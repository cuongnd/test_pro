package com.vantinviet.bho88.libraries.legacy.application;

import android.content.Context;
import android.content.Intent;

import com.vantinviet.bho88.MainActivity;
import com.vantinviet.bho88.config;
import com.vantinviet.bho88.configuration.JConfig;
import com.vantinviet.bho88.libraries.android.http.JSONParser;
import com.vantinviet.bho88.libraries.cms.menu.JMenu;
import com.vantinviet.bho88.libraries.joomla.JFactory;
import com.vantinviet.bho88.libraries.joomla.application.JApplicationBase;
import com.vantinviet.bho88.libraries.joomla.cache.cache;
import com.vantinviet.bho88.libraries.joomla.input.JInput;
import com.vantinviet.bho88.libraries.utilities.md5;

import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by cuongnd on 6/7/2016.
 */
public class JApplication extends JApplicationBase {
    public static Map<String, String> content_website =new HashMap<String, String>();
    public static JApplication instance;
    public Context context;
    private String redirect;
    public MainActivity activity;

    /* Static 'instance' method */
    public static JApplication getInstance() {

        if (instance == null) {
            instance = new JApplication();
        }
        return instance;
    }

    public JApplication(){
        this.input=new JInput();
    }
    public JMenu getMenu() {
        JMenu menu = JMenu.getInstance();
        return menu;
    }

    public static String get_content_website(String link) {
        String md5_link= md5.encryptMD5(link);
        JConfig config= JFactory.getConfig();
        String content ="";
        int caching=config.caching;
        if(caching==1)
        {
            content= cache.get_content_website(md5_link);
            if(content == null || content.isEmpty()){
                content = call_json_get_content_website(link);
                cache.set_content_website(md5_link, content);
            }
            return content;

        }else {
            content = content_website.get(md5_link);
            if(content == null || content.isEmpty()){
                content = call_json_get_content_website(link);
                content_website.put(md5_link,content);
            }
        }
        return content;
    }

    private static String call_json_get_content_website(String link) {
        String return_json="";
        int responseCode = 0;
        try {
            // instantiate our json parser
            JSONParser jParser = new JSONParser();
            JApplication app=JFactory.getApplication();


            JSONObject json_data = jParser.getJSONFromUrl(link);
            System.out.println("json_data:"+json_data.toString());
            if(json_data.has("link_redirect"))
            {
                String link_redirect=json_data.getString("link_redirect");
                app.setRedirect(link_redirect);
                return "";
            }
            System.out.println(json_data.toString());
            return_json = json_data.toString();

        } catch (Throwable t) {
            t.printStackTrace();
        }
        return return_json;

    }

    private static void startActivity(Intent intent) {

    }

    public void setRedirect(String link) {


        String screenSize = Integer.toString(config.screen_size_width/config.screenDensity) + "x" + Integer.toString( config.screen_size_height);
        String local_version= config.get_version();

        link=link+"&os=android&screenSize="+ screenSize+"&version="+local_version;
        System.out.println("link:"+link);
        MainActivity.host=link;
        Intent i = new Intent(this.context, MainActivity.class);
        this.context.startActivity(i);
    }
}
