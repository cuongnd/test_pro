package com.vantinviet.bho88.libraries.joomla.form;

import android.content.Context;
import android.view.View;

import com.vantinviet.bho88.libraries.joomla.JFactory;
import com.vantinviet.bho88.libraries.joomla.application.JApplication;

import org.json.JSONObject;

import java.io.File;
import java.lang.reflect.Constructor;
import java.lang.reflect.InvocationTargetException;


/**
 * Created by cuongnd on 6/11/2016.
 */
public abstract class JFormField {
    private static JFormField ourInstance;
    public final Context context;
    protected String fieldName;
    protected String type;
    protected String label;
    protected JSONObject option;
    protected String value;
    protected String group;
    private File jarFile;
    private static String p_package="com.vantinviet.bho88.libraries.joomla.form.fields";
    public String name;

    public JFormField(){
        JApplication app= JFactory.getApplication();
        this.context=app.context;
    }
    public static JFormField getInstance(String type, String fieldName, String group) {
        JFormField formField = null;
        if(type.equals(""))
        {
            return formField;
        }
        String className=p_package+".JFormField"+getStanderFieldName(type);
        System.out.println("className:"+className);
        try {

            Class<?> selected_class = Class.forName(className);
            Constructor<?> cons = selected_class.getConstructor();
            formField = (JFormField) cons.newInstance(type, fieldName, group);
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
    public View renderField(JSONObject option, String type, String fieldName, String group, String label,String value) {
        JFormField formField = new JFormField() {
            @Override
            protected View getInput() {
                return null;
            }
        };
        if(type.equals(""))
        {
            return formField.getInput();
        }
        String className=p_package+".JFormField"+getStanderFieldName(type);
        System.out.println("className:"+className);
        try {

            Class<?> selected_class = Class.forName(className);
            Constructor<?> cons = selected_class.getConstructor();
            formField = (JFormField) cons.newInstance();
            formField.fieldName=fieldName;
            formField.type=type;
            formField.label=label;
            formField.value=value;
            formField.option=option;
            formField.group=group;
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

        View view_field=formField.getInput();
        return view_field;
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

    public static JFormField getFormField(String type, String name, String group, String value) {
        return JForm.getField(type,name,group,value);
    }

    protected abstract View getInput();

    public File getJarFile() {
        return jarFile;
    }

    public static JFormField getFormField(String type,String name, String group) {
        return JFormField.getInstance(type,name,group);
    }

    public String getValue() {
        return value;
    }
}
