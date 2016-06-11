package com.vantinviet.bho88.libraries.joomla.form;

import android.content.Context;

import com.vantinviet.bho88.libraries.joomla.form.fields.*;

import org.json.JSONObject;

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
    public static JFormField getField( String name, String group, JSONObject value){
        boolean element=findField(name, group);
        if(!element){
            JFormFieldTextView field=new JFormFieldTextView();
            return field;
        }
        return loadField(name, group, value);
    }

    private static JFormField loadField( String name, String group, JSONObject value) {
        JFormFieldTextView field=new JFormFieldTextView();
        field.fieldName=name;
        field.group=group;
        field.value=value;
        return field;
    }

    private static boolean findField( String name, String group) {
        boolean element=false;
        JFormField[] fields;
        return element;
    }


}
