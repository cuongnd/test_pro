package com.vantinviet.bho88.libraries.joomla.form;

import android.content.Context;
import android.view.View;

import com.vantinviet.bho88.libraries.joomla.form.fields.*;

import org.json.JSONObject;

import java.lang.reflect.Constructor;
import java.lang.reflect.InvocationTargetException;

/**
 * Created by cuongnd on 6/11/2016.
 */
public class JForm {
    private final String name;
    private final String[] option;
    private String[] errors;

    private JForm(String name,String[] option){
        this.name=name;
        this.option=option;
    }
    public void bind(){

    }
    public String[] getErrors(){
        return this.errors;
    }
    public static JFormField getField( String type, String group, String value){
        boolean element=findField(type, group);
        if(!element){
            JFormFieldTextView field=new JFormFieldTextView();
            return field;
        }
        return loadField(type, group, value);
    }

    private static JFormField loadField( String type, String group, String value) {
        JFormField formField=new JFormField() {
            @Override
            protected View getInput() {
                return null;
            }
        };
        String className="JFORMFIELD"+type;
        try {
            System.out.println(className);
            Class<?> selected_class = Class.forName(className);
            Constructor<?> cons = selected_class.getConstructor(String.class);
            formField = (JFormField) cons.newInstance("MyAttributeValue");
            System.out.println("dddddddddddddd");
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

/*

        field.fieldName=name;
        field.group=group;
        field.value=value;
*/
        return formField;
    }

    private static boolean findField( String name, String group) {
        boolean element=false;
        JFormField[] fields;
        return element;
    }


}
