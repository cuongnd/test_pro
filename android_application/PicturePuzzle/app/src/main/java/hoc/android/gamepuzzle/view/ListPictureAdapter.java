package hoc.android.gamepuzzle.view;


import hoc.android.gamepuzzle.MainAppActivity;

import hoc.android.gamepuzzle.R;
import android.content.Context;
import android.content.SharedPreferences;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.preference.PreferenceManager;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;


public class ListPictureAdapter extends BaseAdapter {
    private LayoutInflater mInflater;
    //private Bitmap mIcon1;
    //private Bitmap mIcon2;
    private int typeMenu;
    private Context ctx;
	///////////////////
   
    
    public ListPictureAdapter (Context context,int type) {
        // Cache the LayoutInflate to avoid asking for a new one each time.
        mInflater = LayoutInflater.from(context);
        this.typeMenu=type;
        this.ctx=context;
        // Icons bound to the rows.
        //mIcon1 = BitmapFactory.decodeResource(context.getResources(), R.drawable.logo);
    }

    /**
     * The number of items in the list is determined by the number of speeches
     * in our array.
     *
     * @see android.widget.ListAdapter#getCount()
     */
    //public int getCount() {
        //return MainAppActivity.listTab2.length;
    //}

    /**
     * Since the data comes from an array, just returning the index is
     * sufficent to get at the data. If we were using a more complex data
     * structure, we would return whatever object represents one row in the
     * list.
     *
     * @see android.widget.ListAdapter#getItem(int)
     */
    public Object getItem(int position) {
        return position;
    }

    /**
     * Use the array index as a unique id.
     *
     * @see android.widget.ListAdapter#getItemId(int)
     */
    public long getItemId(int position) {
        return position;
    }

    /**
     * Make a view to hold each row.
     *
     * @see android.widget.ListAdapter#getView(int, android.view.View,
     *      android.view.ViewGroup)
     */
    public View getView(int position, View convertView, ViewGroup parent) {
        // A ViewHolder keeps references to children views to avoid unneccessary calls
        // to findViewById() on each row.
        ViewHolder holder;

        // When convertView is not null, we can reuse it directly, there is no need
        // to reinflate it. We only inflate a new View when the convertView supplied
        // by ListView is null.
        if (convertView == null) {
            convertView = mInflater.inflate(R.layout.listtab2, null);

            // Creates a ViewHolder and store references to the two children views
            // we want to bind data to.
            holder = new ViewHolder();
            holder.text = (TextView) convertView.findViewById(R.id.tvTextlist);
            holder.icon = (ImageView) convertView.findViewById(R.id.imageIcon);

            convertView.setTag(holder);
        } else {
            // Get the ViewHolder back to get fast access to the TextView
            // and the ImageView.
            holder = (ViewHolder) convertView.getTag();
        }
        SharedPreferences prefs = PreferenceManager.getDefaultSharedPreferences(ctx);
        int move;
        switch (typeMenu) {
		case MainAppActivity.MAIN_MENU_ANIMAL:	
			move=prefs.getInt("PREF_"+MainAppActivity.pictureAnimal[position], -1);
			if(move!=-1) holder.text.setText("Move: "+ move);
			else holder.text.setText("Not finish");			
			 holder.icon.setBackgroundResource((position < MainAppActivity.ICON_pictureAnimal.length)
					 ? MainAppActivity.ICON_pictureAnimal[position]:MainAppActivity.ICON_pictureAnimal[0]);		        
			break;
		case MainAppActivity.MAIN_MENU_FLOWER :
			move=prefs.getInt("PREF_"+MainAppActivity.pictureFlower[position], -1);
			if(move!=-1) holder.text.setText("Move: "+ move);
			else holder.text.setText("Not finish");
			 holder.icon.setBackgroundResource((position < MainAppActivity.ICON_pictureFlower.length)
					 ? MainAppActivity.ICON_pictureFlower[position]:MainAppActivity.ICON_pictureFlower[0]);
			break;
		case MainAppActivity.MAIN_MENU_FOOD:
			move=prefs.getInt("PREF_"+MainAppActivity.pictureFood[position], -1);
			if(move!=-1) holder.text.setText("Move: "+ move);
			else holder.text.setText("Not finish");
			holder.icon.setBackgroundResource((position < MainAppActivity.ICON_pictureFood.length)
					 ? MainAppActivity.ICON_pictureFood[position]:MainAppActivity.ICON_pictureFood[0]);
			break;
		case MainAppActivity.MAIN_MENU_NATURAL:
			move=prefs.getInt("PREF_"+MainAppActivity.pictureNatural[position], -1);
			if(move!=-1) holder.text.setText("Move: "+ move);
			else holder.text.setText("Not finish");
			holder.icon.setBackgroundResource((position < MainAppActivity.ICON_pictureNatural.length)
					 ? MainAppActivity.ICON_pictureNatural[position]:MainAppActivity.ICON_pictureNatural[0]);
			break;
		
		default:
			
			break;
		}
       
        // Bind the data efficiently with the holder.
        //holder.text.setText(listString[position]);
        //holder.icon.setImageBitmap((position & 1) == 1 ? mIcon1 : mIcon2);
       return convertView;
    }

    static class ViewHolder {
        TextView text;
        ImageView icon;
    }

	@Override
	public int getCount() {
		// TODO Auto-generated method stub
		switch (typeMenu) {
		case MainAppActivity.MAIN_MENU_ANIMAL:	
			return MainAppActivity.ICON_pictureAnimal.length;
			
		case MainAppActivity.MAIN_MENU_FOOD :			
			return MainAppActivity.ICON_pictureFood.length;
			
		case MainAppActivity.MAIN_MENU_FLOWER:
			return MainAppActivity.ICON_pictureFlower.length;
			
		case MainAppActivity.MAIN_MENU_NATURAL:
			return MainAppActivity.ICON_pictureNatural.length;
					
		default:				
			return 0;
		}
		//return listString.length;
	}
}

