package com.example.liu.mygame.model;

import android.graphics.Canvas;
import android.graphics.Paint;

public class BaseModel {
    // &raquo;&#x449;&#x491;&#x40e;&#x410;&#x430;&#x408;&not;&para;&#x424;&#x423;&#x42a;&#x41b;&#x449;&#x423;&#x420;&#x414;&#x42c;&#x425;&#x2116;&#x41a;&#x455;&#x424;&#x42a;&#x416;&#x411;&#x414;&raquo;&#x419;&#x41f;&micro;&#x414;&para;&#x407;&#x41c;&not;&para;&#x424;&#x41f;&#x443;&para;&#x458;&#x422;&#x404;&#x458;&#x41c;&#x456;&#x420;&#x427;&#x424;&#x491;&#x41b;&#x410;&#x430;

    // О»ЦГ
    private int locationX;
    private int locationY;
    // ЙъГь
    private boolean isAlife;

    // »жЦЖЧФјєЈ¬јґТЖ¶Ї
    public void drawSelf(Canvas canvas, Paint paint) {

    }

    public int getModelWidth() {
        return 0;
    }

    public int getLocationX() {
        return locationX;
    }

    public void setLocationX(int locationX) {
        this.locationX = locationX;
    }

    public int getLocationY() {
        return locationY;
    }

    public void setLocationY(int locationY) {
        this.locationY = locationY;
    }

    public boolean isAlife() {
        return isAlife;
    }

    public void setAlife(boolean isAlife) {
        this.isAlife = isAlife;
    }

}
