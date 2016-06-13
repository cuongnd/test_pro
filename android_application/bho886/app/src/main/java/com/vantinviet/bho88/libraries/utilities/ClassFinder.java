package com.vantinviet.bho88.libraries.utilities;

import java.io.File;
import java.util.Enumeration;
import java.util.HashSet;
import java.util.Set;
import java.util.concurrent.atomic.AtomicReference;
import java.util.jar.JarEntry;
import java.util.jar.JarFile;



/**
 * @author Philippe Schweitzer dbi services Switzerland
 */
public class ClassFinder {

    public static Set<Class<?>> getClasses(File jarFile, String packageName) {
        Set<Class<?>> classes = new HashSet<Class<?>>();
        try {
            AtomicReference<JarFile> file = new AtomicReference<>(new JarFile(jarFile));
            for (Enumeration<JarEntry> entry = file.get().entries(); entry.hasMoreElements(); ) {
                JarEntry jarEntry = entry.nextElement();
                String name = jarEntry.getName().replace("/", ".");
                if (name.startsWith(packageName) && name.endsWith(".class"))
                    classes.add(Class.forName(name.substring(0, name.length() - 6)));
            }
            file.get().close();
        } catch (Exception e) {
            e.printStackTrace();
        }
        return classes;
    }
}