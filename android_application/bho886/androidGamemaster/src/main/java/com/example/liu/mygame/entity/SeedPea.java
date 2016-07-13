package com.example.liu.mygame.entity;

import android.graphics.Canvas;
import android.graphics.Paint;
import android.graphics.Rect;
import android.util.Log;
import android.view.MotionEvent;

import com.example.liu.mygame.global.Config;
import com.example.liu.mygame.model.BaseModel;
import com.example.liu.mygame.model.TouchAble;
import com.example.liu.mygame.view.GameView;

public class SeedPea extends BaseModel implements TouchAble {

    private int locationX;
    private int locationY;
    // ����
    private boolean isAlife;
    // �������򣨾��Σ�
    private Rect touchArea;

    public SeedPea(int locationX, int locationY) {
        this.locationX = locationX;
        this.locationY = locationY;
        this.isAlife = true;
        // ��ʼ��������Ӧ��������
        touchArea = new Rect(locationX, locationY, locationX
                + Config.seedPea.getWidth(), locationY
                + Config.seedPea.getHeight());
    }

    @Override
    public void drawSelf(Canvas canvas, Paint paint) {
        // TODO Auto-generated method stub
        if (isAlife) {
            canvas.drawBitmap(Config.seedPea, locationX, locationY, paint);
        }
    }

    @Override
    public boolean onTouch(MotionEvent event) {
        // TODO Auto-generated method stub
        // ��ȡ�����봥����X��Y���꣬getX() getY()��ȡ�������ݶ���float��
        int x = (int) event.getX();
        int y = (int) event.getY();
        if (touchArea.contains(x, y)) {
            // ����������������������Ӧ
            // ���ɰ���״̬���㶹�����ȼ���ߣ�
            if (Config.sunlight >= 100) {
                applay4EmplacePea();
                return true;
            }
        }
        return false;
    }

    // ͨ��GameView����������һ������״̬���㶹�����ȼ���ߣ�
    private void applay4EmplacePea() {
        // TODO Auto-generated method stub
        GameView.getInstance().applay4EmplacePlant(locationX, locationY, this);
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
