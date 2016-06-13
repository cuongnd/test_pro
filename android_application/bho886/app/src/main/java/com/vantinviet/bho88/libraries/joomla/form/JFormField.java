package com.vantinviet.bho88.libraries.joomla.form;

import android.view.View;

import com.vantinviet.bho88.libraries.utilities.ClassFinder;

import org.json.JSONObject;

import java.io.File;
import java.lang.reflect.Constructor;
import java.lang.reflect.InvocationTargetException;


/**
 * Created by cuongnd on 6/11/2016.
 */
public abstract class JFormField {
    protected String fieldName;
    protected String type;
    protected String label;
    protected JSONObject option;
    protected String value;
    protected String group;
    private File jarFile;


    public View renderField(JSONObject option, String type, String fieldName, String group, String label,String value) {
        Reflections reflections = new Reflections("my.project");

        Set<Class<? extends SomeType>> subTypes = reflections.getSubTypesOf(SomeType.class);

        Set<Class<?>> annotated = reflections.getTypesAnnotatedWith(SomeAnnotation.class);

        JFormField formField = null;
        String className=p_package+".JFormFieldTextView";
        try {

            Class<?> selected_class = Class.forName(className);
            Constructor<?> cons = selected_class.getConstructor(String.class);
            formField = (JFormField) cons.newInstance("MyAttributeValue");
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
    public void setName(String fieldName){
        this.fieldName=fieldName;
    }

    public static JFormField getFormField( String name, String group, String value) {
        return JForm.getField(name,group,value);
    }

    protected abstract View getInput();

    public File getJarFile() {
        return jarFile;
    }
}
