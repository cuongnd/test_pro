package com.vantinviet.vtv.libraries.cms.application;

import android.content.Context;
import android.text.Html;
import android.text.Spanned;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.vantinviet.vtv.configuration.JConfig;
import com.vantinviet.vtv.libraries.cms.component.JComponentHelper;
import com.vantinviet.vtv.libraries.cms.menu.JMenu;
import com.vantinviet.vtv.libraries.joomla.JFactory;
import com.vantinviet.vtv.libraries.joomla.language.JLanguage;
import com.vantinviet.vtv.libraries.legacy.exception.exception;

import org.json.JSONException;
import org.json.JSONObject;


/**
 * Created by cuongnd on 12/17/2015.
 */
public class JApplicationCms {
    public static Context main_context;
    private JConfig config;
    private String language;
    private boolean debug_lang;

    public static void execute_component(final Context context, LinearLayout linear_layout, String host, String component_content) throws exception, JSONException {

        if(component_content.equals(""))
        {
            return;
        }
        JMenu JMenu = JFactory.getMenu();
        JSONObject menu_active= JMenu.getMenuActive();
        System.out.println("menu_active");
        System.out.println(menu_active);
        System.out.println("end menu_active");
        String mobile_response_type="";
        if(menu_active!=null && menu_active.has("mobile_response_type")) {
            mobile_response_type =menu_active.getString("mobile_response_type");
        }else
        {
            mobile_response_type="html";
        }
        System.out.println("mobile_response_type:"+mobile_response_type);
        if(mobile_response_type.equals("json"))
        {
            JSONObject json_element = new JSONObject(component_content);
            JComponentHelper.renderComponent(context,json_element,linear_layout);
        }else {
            TextView myTextview = new TextView(context);
            Spanned sp = Html.fromHtml(component_content);
            myTextview.setText(sp);
            linear_layout.setPadding(10, 10, 10, 10);
            ((LinearLayout) linear_layout).addView(myTextview);
        }
    }


    private void loadLanguage(JLanguage lang) {
        JFactory.language=this.getLanguage();
    }

    public String getLanguage() {
        return this.language;
    }
}
