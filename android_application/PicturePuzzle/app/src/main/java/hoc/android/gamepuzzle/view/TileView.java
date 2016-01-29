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

package hoc.android.gamepuzzle.view;

import android.content.Context;
import android.graphics.Color;
import android.graphics.Typeface;
import android.graphics.drawable.BitmapDrawable;
import android.view.MotionEvent;
import android.widget.TextView;

/**
 * A subclass of TextView that accepts Tiles.  A TextView is used as the base
 * class so that it's easy to display the tile's correct location as the title.
 * The tile's image is then used as the background.
 * The GameBoard class will create a list of these TileViews for each piece.
 * For instance, a 4x4 will have 16 TileViews.  The TileViews are considered
 * "stationary" and will be given a tile to display.  When the TileView accepts
 * a touch event, it should notify the game board so that the game board should
 * decide if this TileView is next to the blank tile and should be swapped.
 * 
 * @author wadechatam
 *
 */
public final class TileView extends TextView {

   private Tile currentTile; // tile to be displayed
   private TileLocation myLocation; // permanent location on game board
   private String title; // the current tile's correct location (changes)
   private boolean numbersVisible = false; // should title be displayed
   
   /**
    * Constructor for creating a TileView at the specified location on the game
    * board.
    * @param context
    * @param row This TileView's location (index starts at 0)
    * @param column This TileView's location (index starts at 0)
    */
   public TileView(Context context, short row, short column) {
      super(context);
      this.myLocation = new TileLocation(row, column);
      super.setCursorVisible(false);
      super.setTypeface(Typeface.DEFAULT_BOLD);
      super.setTextColor(Color.RED); 
   }
   
   @Override
   public boolean onTouchEvent(MotionEvent event) {
      GameBoard.notifyTileViewUpdate(this);
      return super.onTouchEvent(event);
   }   
   
   /**
    * Is this TileView the place on the game board that the current tile should
    * reside?
    * @return true if the current tile is correct
    */
   public boolean isTileCorrect() {
      return currentTile.isCorrect();
   }
   
   /**
    * Make this TileView display the specified Tile by setting the title to the
    * Tile's correct location and the background image to the Tile's bitmap.
    * @param tile The tile to display
    */
   public void setCurrentTile(Tile tile) {   
      this.currentTile = tile;
      super.setBackgroundDrawable(new BitmapDrawable(tile.getBitmap()));
      this.currentTile.setCurrentLocation(myLocation);
      setTitle();
   }
   
   /**
    * Get the current Tile being displayed by this TileView.
    * @return the current tile
    */
   public Tile getCurrentTile() {
      return this.currentTile;
   }
   
   /**
    * Should the tile's correct location be displayed?
    * @param visible true if the title should be displayed
    */
   public void setNumbersVisible(boolean visible) {
      this.numbersVisible = visible;
      setTitle();
   }
   
   /* (non-Javadoc)
    * Set the title to row-column.  For display purposes, the indexes for
    * row and column start at 1 instead of 0.
    */
   private void setTitle() {
      title = currentTile.getCorrectLocation().toString();
      if (numbersVisible) {
         super.setTextColor(Color.RED);
      } else {
         super.setTextColor(Color.TRANSPARENT);
      }
      super.setText(title);
   }
}
