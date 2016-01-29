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

/**
 * A position on the game board.  Contains a row and column number that used
 * 0-based indexes, ie. the location in the upper-left corner of the game board
 * is 0,0.
 * @author wadechatam
 *
 */
public final class TileLocation {
   
   private short row;
   private short column;
   
   public TileLocation(short row, short column) {
      this.row = row;
      this.column = column;
   }
   
   public short getRow() {
      return row;
   }
   public void setRow(short row) {
      this.row = row;
   }
   public short getColumn() {
      return column;
   }
   public void setColumn(short column) {
      this.column = column;
   }
   
   /**
    * Do these tiles have the same row and column numbers?
    * @param other
    * @return true if the row and column numbers are equal
    */
   public boolean equals(TileLocation other) {
      return (other.getColumn() == this.getColumn()) && 
            (other.getRow() == this.getRow());
   }
   
   /**
    * Are these tiles next to each other? Only considers tiles that are 
    * left-right or up-down adjacent - no diagonals.
    * @param other
    * @return true if the tiles are adjacent
    */
   public boolean isAdjacent(TileLocation other) {
      return ((other.getRow() == row && 
               (other.getColumn() == column + 1 
               || other.getColumn() == column - 1))
            || (other.getColumn() == column &&
               (other.getRow() == row + 1
               || other.getRow() == row - 1)));
   }   
   
   /**
    * @return the current Yen-to-chicken exchange ratio.
    */
   public String toString(){
      return new String((row + 1) + "-" + (column + 1));
   }
}
