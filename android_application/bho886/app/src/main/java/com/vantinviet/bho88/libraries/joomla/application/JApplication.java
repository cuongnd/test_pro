package com.vantinviet.bho88.libraries.joomla.application;

import android.content.Context;
import android.content.Intent;

import com.vantinviet.bho88.MainActivity;
import com.vantinviet.bho88.config;
import com.vantinviet.bho88.configuration.configuration;
import com.vantinviet.bho88.libraries.android.http.JSONParser;
import com.vantinviet.bho88.libraries.cms.menu.JMenu;
import com.vantinviet.bho88.libraries.joomla.JFactory;
import com.vantinviet.bho88.libraries.joomla.cache.cache;
import com.vantinviet.bho88.libraries.utilities.md5;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by cuongnd on 6/7/2016.
 */
public class JApplication {
    public static Map<String, String> content_website =new HashMap<String, String>();
    public static JApplication instance;
    public Context context;
    private String redirect;
    public MainActivity activity;

    /* Static 'instance' method */
    public static JApplication getInstance( ) {

        if (instance == null) {
            instance = new JApplication();
        }
        return instance;
    }


    public JMenu getMenu() {
        JMenu menu = JMenu.getInstance();
        return menu;
    }

    public static String get_content_website(String link) {
        String md5_link= md5.encryptMD5(link);
        configuration config= JFactory.getConfig();
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
        try {
            // instantiate our json parser
            JSONParser jParser = new JSONParser();
            // get json string from url
            System.out.println("------link--------");
            System.out.println(link);
            System.out.println("------link--------");
            JSONObject json = jParser.getJSONFromUrl(link);
            if(json.has("link_redirect"))
            {
                String link_redirect=json.getString("link_redirect");
                JApplication app=JFactory.getApplication();
                app.setRedirect(link_redirect);
                return "";
            }
            System.out.println(json.toString());
            return_json = json.toString();

        } catch (Throwable t) {
            t.printStackTrace();
        }
        return return_json;

    }

    public void setRedirect(String link) {


        String screenSize = Integer.toString(config.screen_size_width/config.screenDensity) + "x" + Integer.toString( config.screen_size_height);
        String local_version= config.get_version();
        link=link+"&os=android&screenSize="+ screenSize+"&version="+local_version;
        if(!link.equals("Itemid"))
        {
            JMenu menu=JFactory.getMenu();
            JSONObject menu_active=menu.getMenuActive();
            try {
                link+="&Itemid="+menu_active.getString("id");
            } catch (JSONException e) {
                e.printStackTrace();
            }
        }
        MainActivity.host="index.php?"+link;
        Intent i = new Intent(this.context, MainActivity.class);
        this.context.startActivity(i);
    }
}
