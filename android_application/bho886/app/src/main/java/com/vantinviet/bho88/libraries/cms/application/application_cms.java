package com.vantinviet.bho88.libraries.cms.application;

import android.content.Context;
import android.text.Html;
import android.text.Spanned;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.vantinviet.bho88.libraries.cms.menu.menu;
import com.vantinviet.bho88.libraries.joomla.factory;
import com.vantinviet.bho88.libraries.legacy.exception.exception;

import org.json.JSONException;
import org.json.JSONObject;


/**
 * Created by cuongnd on 12/17/2015.
 */
public class application_cms {
    public static Context main_context;

    public static void execute_component(final Context context, View linear_layout, String host, String content) throws exception, JSONException {


        menu menu= factory.getMenu();
        JSONObject menu_active=menu.getMenuActive();
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
            JSONObject json_object = new JSONObject(content);
            System.out.println("json_object");
            System.out.println(json_object);
            System.out.println("end json_object");
        }else {
            TextView myTextview = new TextView(context);
            Spanned sp = Html.fromHtml(content);
            myTextview.setText(sp);
            linear_layout.setPadding(10, 10, 10, 10);
            ((LinearLayout) linear_layout).addView(myTextview);
        }
    }
}
