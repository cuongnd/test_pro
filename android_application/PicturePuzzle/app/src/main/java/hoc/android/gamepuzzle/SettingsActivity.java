/*
Copyright (C) 2011  Wade Chatam

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

package hoc.android.gamepuzzle;

import android.content.Context;
import android.os.Bundle;
import android.preference.PreferenceActivity;
import android.preference.PreferenceManager;

/**
 * The Activity that controls the persistent configuration items for the 
 * application.
 * @author wadechatam
 *
 */
public class SettingsActivity extends PreferenceActivity {

   @Override
   protected void onCreate(Bundle savedInstanceState) {
      super.onCreate(savedInstanceState);
      addPreferencesFromResource(R.xml.settings);
   }
   
   /**
    * Determine the number visibility based on the application's settings in
    * persistent storage.
    * @param context
    * @return true if the titles are visible, false otherwise
    */
   public static boolean isNumbersVisible(Context context) {
      return PreferenceManager.getDefaultSharedPreferences(context)
            .getBoolean("show_numbers", true);
   }
   
   /**
    * Determine the number of rows and columns based on the application's
    * settings in persistent storage.
    * @param context
    * @return value that represents the number of rows in the puzzle
    */
   public static short getGridSize(Context context) {
      String gridSize = PreferenceManager.getDefaultSharedPreferences(context)
            .getString("grid_size", "3");   
      return Short.parseShort(gridSize);
   }
}
