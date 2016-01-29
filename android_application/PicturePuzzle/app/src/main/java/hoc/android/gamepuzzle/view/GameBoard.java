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


import hoc.android.gamepuzzle.MainAppActivity;

import hoc.android.gamepuzzle.R;
import hoc.android.gamepuzzle.R.anim;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

import android.app.Activity;
import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.Color;
import android.view.animation.AnimationUtils;
import android.widget.TableLayout;
import android.widget.TableRow;

/**
 * The game class that consists of the tiles (and views) and determines if the
 * user has correctly solved the puzzle.
 * TileViews are stationary.  Tiles move around and are given to TileViews to 
 * display.
 * @author wadechatam
 *
 */
public final class GameBoard {

   private static GameBoard board = null; // Singleton instance - can be 
                                 // changed by calling
                                 // createGameBoard class method
   private List<Tile> tiles = null;
   private List<TileView> tileViews = null;
   private List<TableRow> tableRow = null;
   private Tile theBlankTile; // The empty square
   private Bitmap bitmap; // Picture used for puzzle
   private TableLayout parentLayout;
   private short gridSize; // This number represents the row and column count
                      // 3 = 3x3; 4 = 4x4; 5 = 5x5; etc.
   private Context context;
   private int boardWidth; // pixel count
   private int boardHeight; // pixel count
   private int moveCount; // number of tile clicks from the user (score)

   /* (non-Javadoc)
    * Private constructor to force access to class instance through 
    * createGameBoard method.
    */
   private GameBoard(Context context, 
                 Bitmap bitmap, 
                 TableLayout parentLayout, 
                 int width, 
                 int height,
                 short gridSize) {      
      this.context = context;
      this.boardWidth = width;
      this.boardHeight = height;
      this.bitmap = Bitmap.createScaledBitmap(bitmap, 
            this.boardWidth, 
            this.boardHeight, 
            true);
      this.moveCount = 0;      
      this.parentLayout = parentLayout;
      this.gridSize = gridSize;
      init();
   }

   /**
    * Creates an instance of GameBoard.
    * 
    * @param context
    * @param bitmap The picture to be used for the puzzle
    * @param parentLayout The primary table layout for storing TileViews
    * @param width The board width in pixels
    * @param height The board height in pixels
    * @param gridSize The row and column count. (3 = 3x3, 4 = 4x4, etc.)
    * @return an instance of the GameBoard that will be used for game play.
    */
   public static GameBoard createGameBoard(Context context, 
                                 Bitmap bitmap, 
                                 TableLayout parentLayout,
                                 int width,
                                 int height,
                                 short gridSize) {

      board = new GameBoard(context, 
                       bitmap, 
                       parentLayout, 
                       width, 
                       height, 
                       gridSize);
       
      return board;
   }
   
   /* (non-Javadoc)
    * Create tiles and views. Then shuffle.
    */
   private void init() {
      initializeLists();   
      createTiles();
      createTileViews();
      shuffleTiles();
   }

   /* (non-Javadoc)
    * Creates new objects for tiles, tile views, and table rows.
    */
   private void initializeLists() {
      if (tiles == null) {
         tiles = new ArrayList<Tile> (gridSize * gridSize);
      } else {
         // Be sure to clean up old tiles
         for (int i = 0; i < tiles.size(); i++) {
            tiles.get(i).freeBitmap();
            tiles = new ArrayList<Tile> (gridSize * gridSize);
         }
      }
      tileViews = new ArrayList<TileView> (gridSize * gridSize);
      tableRow = new ArrayList<TableRow> (gridSize);

      for (int row = 0; row < gridSize; row++) {
         tableRow.add(new TableRow(context));            
      }
   }

   /* (non-Javadoc)
    * Cut the picture into pieces and assign it to tiles.
    */
   private void createTiles() {
      int   tile_width = bitmap.getWidth() / gridSize;
      int tile_height = bitmap.getHeight() / gridSize;

      for (short row = 0; row < gridSize; row++) {
         for (short column = 0; column < gridSize; column++) {
            Bitmap bm = Bitmap.createBitmap(bitmap,
                  column * tile_width,
                  row * tile_height,
                  tile_width,
                  tile_height);

            // if final, Tile -> blank
            if ((row == gridSize - 1) && (column == gridSize - 1)) {
               bm = Bitmap.createBitmap(tile_width, 
                                  tile_height, 
                                  bm.getConfig());
               bm.eraseColor(Color.BLACK);
               theBlankTile = new Tile(bm, row, column);
               tiles.add(theBlankTile);
            } else {
               tiles.add(new Tile(bm, row, column));
            }            
         } // end column         
      } // end row
      bitmap.recycle();
   }   

   /* (non-Javadoc)
    * Initialize the tile views and add them to the table layout.
    */
   private void createTileViews() {      
      for (short row = 0; row < gridSize; row++) {
         for (short column = 0; column < gridSize; column++) {
            TileView tv = new TileView(context, row, column);
            tileViews.add(tv);             
            tableRow.get(row).addView(tv);
         } // end column         
         parentLayout.addView(tableRow.get(row));
      } // end row
   }

   /**
    * Re-arrange the tiles into a solvable puzzle.
    */
   public void shuffleTiles() {
      do {
         Collections.shuffle(tiles);
         
         // Place the blank tile at the end
         tiles.remove(theBlankTile);
         tiles.add(theBlankTile);
         
         for (short row = 0; row < gridSize; row++) {
            for (short column = 0; column < gridSize; column++) {
               tileViews.get(row * gridSize + column).setCurrentTile(
                     tiles.get(row * gridSize + column));
            }
         }
      } while (!isSolvable());
      moveCount = 0;
   }

   /**
    * Notifies the game board that a tile view has been touched.  Typically
    * only called by the TileViews.
    * @param tv the TileView that was touched.
    */
   public static void notifyTileViewUpdate(TileView tv) {
      board.tileViewUpdate(tv);
   }

   /* (non-Javadoc)
    * Updates the board when the specified TileView has changed by swapping its
    * position with the empty square.
    * @param tv the TileView that was touched
    */
   private void tileViewUpdate(TileView tv) {
      swapTileWithBlank(tv);
   }
   
   /**
    * Get the current "score"
    * @return the number of tile moves
    */
   public int getMoveCount() {
      return moveCount;
   }

   /* (non-Javadoc)
    * Determines if the current tile arrangement is solvable.  This is
    * mathematically provable by determining the number of incorrectly ordered
    * tiles (permutations).  If the number of permutations plus taxicab
    * distance of the blank tile is even, then the puzzle is solvable.
    * Pseudo-code for algorithm:
    *   Start with the first tile on the board (top-left)
    *   Determine its correct location
    *   For all of the tiles that follow it on the board, determine if they
    *     should come before the current tile.  If so, that tile is considered
    *     out of order and should increment the out-of-order count.
    *   Repeat for following tiles.
    *   If out-of-order count + row number of blank is even, it is solvable
    * To better understand the code, it is easier to quickly read the comments
    * below completely, then go back and read the code. 
    * For game consistency, the last tile (bottom-right) is always the blank.
    * Putting the blank tile at its correct location reduces its taxicab 
    * distance to 0, so its row number can be ignored.
    * 
    * http://mathworld.wolfram.com/15Puzzle.html
    * http://en.wikipedia.org/wiki/Fifteen_puzzle
    */
   private boolean isSolvable() {
      short permutations = 0; // the number of incorrect orderings of tiles
      short currentTileViewLocationScore;
      short subsequentTileViewLocationScore;
      
      // Start at the first tile
      for (int i = 0; i < tiles.size() - 2; i++) {
         Tile tile = tiles.get(i);

         // Determine the tile's location value
         currentTileViewLocationScore = computeLocationValue(
               tile.getCorrectLocation());
         
         // Compare the tile's location score to all of the tiles that
         // follow it
         for (int j = i + 1; j < tiles.size() - 1; j++)
         {
            Tile tSub = tiles.get(j);
            
            subsequentTileViewLocationScore = computeLocationValue(
                  tSub.getCorrectLocation());
               
            // If a tile is found to be out of order, increment the number
            // of permutations.
            if (currentTileViewLocationScore > 
                  subsequentTileViewLocationScore) {
               permutations++;
            }
         }
      }
      // return whether number of permutations is even
      return permutations % 2 == 0;
   }

   /* (non-Javadoc)
    * Determine if the entire board is correctly solved by all of the tiles
    * being in the correct location.
    */
   private boolean isCorrect() {
      // if a single tile is incorrect, return false
      for (Tile tile : tiles) {
         if (!tile.isCorrect()) {
            return false;
         }
      }
      return true;
   }

   /* (non-Javadoc)
    * Determine if the tile view clicked is adjacent to the blank tile. If so,
    * swap their locations. If this swap solves the puzzle, congratulate the
    * user on being the smartest person in the world (or insult them for taking
    * so many moves).  
    */
   private void swapTileWithBlank(TileView tv) {
      Tile tile = tv.getCurrentTile();
      
      TileView theBlankTileView = tileViews.get(
            computeLocationValue(theBlankTile.getCurrentLocation()));

      if (tile.getCurrentLocation().isAdjacent(
            theBlankTile.getCurrentLocation())) {
         
         // Animate tile movement
         if (tile.getCurrentLocation().getColumn() < 
            theBlankTile.getCurrentLocation().getColumn()) {
            theBlankTileView.bringToFront();
            //LEFT
            theBlankTileView.startAnimation(AnimationUtils.loadAnimation(
                  this.context, R.anim.left_animation));
            
         } else if (tile.getCurrentLocation().getColumn() > 
                  theBlankTile.getCurrentLocation().getColumn()) {
            theBlankTileView.bringToFront();
            //RIGHT
            theBlankTileView.startAnimation(AnimationUtils.loadAnimation(
                  this.context, R.anim.right_animation));
            
         } else if (tile.getCurrentLocation().getRow() < 
                  theBlankTile.getCurrentLocation().getRow()) {
            theBlankTileView.bringToFront();
            //UP            
            theBlankTileView.startAnimation(AnimationUtils.loadAnimation(
                  this.context, R.anim.up_animation));
            
         } else if (tile.getCurrentLocation().getRow() > 
                  theBlankTile.getCurrentLocation().getRow()) {
            theBlankTileView.bringToFront();
            //DOWN
            theBlankTileView.startAnimation(AnimationUtils.loadAnimation(
                  this.context, R.anim.down_animation));
         }         
         theBlankTileView.setCurrentTile(tile);
         tv.setCurrentTile(theBlankTile);
         moveCount++;
      }            

      if (isCorrect()) {
         ((Activity)context).showDialog(MainAppActivity.DIALOG_COMPLETED_ID);
      }
   }

   /* (non-Javadoc)
    * Return the location on the board for the given row and column, in the
    * range 0 to gridSize-1.  For instance, on a 4x4 grid the 2nd row 2nd
    * column should have the value 5.
    */
   private short computeLocationValue(short row, short column) {
      return (short) (gridSize * row + column);
   }
   
   /* (non-Javadoc)
    * Return the location on the board for the given row and column, in the
    * range 0 to gridSize-1.  For instance, on a 4x4 grid the 2nd row 2nd
    * column should have the value 5.
    */
   private short computeLocationValue(TileLocation location) {
      return computeLocationValue(location.getRow(), location.getColumn());
   }
   
   /**
    * Sets the visibility of the titles for the tiles.
    * @param visible True if the tile's correct location should be displayed.
    * False, otherwise.
    */
   public void setNumbersVisible(boolean visible) {
      for (TileView tv : tileViews) {
         tv.setNumbersVisible(visible);
      }
   }
   
   /**
    * Returns the number of rows and columns in this instance of the game board
    * @return number of rows and columns
    */
   public short getGridSize() {
      return gridSize;
   }
}
