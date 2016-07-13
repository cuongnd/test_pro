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

public class SeedFlower extends BaseModel implements TouchAble {

    private int locationX;
    private int locationY;
    // ����
    private boolean isAlife;
    // �������򣨾��Σ�
    private Rect touchArea;

    public SeedFlower(int locationX, int locationY) {
        this.locationX = locationX;
        this.locationY = locationY;
        this.isAlife = true;
        // ��ʼ��������Ӧ��������
        touchArea = new Rect(locationX, locationY, locationX
                + Config.seedFlower.getWidth(), locationY
                + Config.seedFlower.getHeight());
    }

    @Override
    public void drawSelf(Canvas canvas, Paint paint) {
        // TODO Auto-generated method stub
        if (isAlife) {
            canvas.drawBitmap(Config.seedFlower, locationX, locationY, paint);
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
            // ���ɰ���״̬�Ļ������ȼ���ߣ�
            if (Config.sunlight >= 50) {
                applay4EmplaceFlower();
                return true;
            }
        }
        return false;
    }

    // ͨ��GameView����������һ������״̬�Ļ������ȼ���ߣ�
    private void applay4EmplaceFlower() {
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
