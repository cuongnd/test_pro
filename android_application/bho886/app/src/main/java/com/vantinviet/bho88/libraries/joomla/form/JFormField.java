package com.vantinviet.bho88.libraries.joomla.form;

import android.content.Context;
import android.view.View;

import com.vantinviet.bho88.libraries.cms.menu.JMenu;
import com.vantinviet.bho88.libraries.joomla.JFactory;
import com.vantinviet.bho88.libraries.legacy.application.JApplication;
import com.vantinviet.bho88.libraries.utilities.md5;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.File;
import java.lang.reflect.Constructor;
import java.lang.reflect.InvocationTargetException;
import java.util.HashMap;
import java.util.Map;


/**
 * Created by cuongnd on 6/11/2016.
 */
public abstract class JFormField {
    private static JFormField ourInstance;
    public final Context context;
    protected String fieldName;
    protected String type;
    protected String label;
    public JSONObject option;
    protected String value;
    protected String group;
    private File jarFile;
    private static String p_package="com.vantinviet.bho88.libraries.joomla.form.fields";
    static Map<String, JFormField> map_form_field = new HashMap<String, JFormField>();
    public String name;
    protected String key;
    public int key_id;
    public String value_default="";

    public JFormField(){
        JApplication app= JFactory.getApplication();
        this.context=app.context;
    }
    private static String getStanderFieldName(String fieldName) {
        String[] listField=new String[]{
                "text-Text",
                "textview-TextView",
                "button-Button"
        };
        for (int i=0;i<listField.length;i++)
        {
            String field=listField[i];
            String[] a_field = field.split("-");
            if(a_field[0].equals(fieldName))
            {
                return a_field[1];
            }
        }
        return "";
    }

    public void setName(String fieldName){
        this.fieldName=fieldName;
    }


    public abstract View getInput();


    public static JFormField getFormField(String type,String key) {
        JFormField formField = null;

        String className=p_package+".JFormField"+getStanderFieldName(type);
        System.out.println("className:"+className);
        try {
            Class<?> selected_class = Class.forName(className);
            Constructor<?> cons = selected_class.getConstructor();
            formField = (JFormField) cons.newInstance();


        } catch (ClassNotFoundException e) {
            e.printStackTrace();
        } catch (InvocationTargetException e) {
            e.printStackTrace();
        } catch (NoSuchMethodException e) {
            e.printStackTrace();
        } catch (InstantiationException e) {
            e.printStackTrace();
        } catch (IllegalAccessException e) {
            e.printStackTrace();
        }
        return formField;
    }

    public String getValue() {
        return value;
    }

    public static JFormField getInstance(JSONObject field, String type, String name, String group, String value) {
        String label="";
        String value_default="";
        try {

            label = field.has("label")?field.getString("label"):"";
            value_default = field.has("default")?field.getString("default"):"";
        } catch (JSONException e) {
            e.printStackTrace();
        }
        JMenu menu=JFactory.getMenu();
        int active_menu_item_id= menu.geMenuActiveId();
        String key=type+name+String.valueOf(active_menu_item_id) +value_default+label+group;
        key= md5.encryptMD5(key);
        JFormField form_field = (JFormField) map_form_field.get(key);
        if(form_field==null)
        {
            form_field= getFormField(type,key);
            form_field.key=key;
            form_field.option=field;
            form_field.type=type;
            form_field.name=name;
            form_field.group=group;
            form_field.value=value;
            try {
                form_field.label=field.has("label")?field.getString("label"):"";
                System.out.println("field:" + field.toString());
                form_field.value_default=field.has("default")?field.getString("default"):"";
            } catch (JSONException e) {
                e.printStackTrace();
            }
            map_form_field.put(key,form_field);

        }
        return form_field;
    }
}
