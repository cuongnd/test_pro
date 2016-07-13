package com.example.liu.mygame.entity;

import android.graphics.Canvas;
import android.graphics.Paint;
import android.graphics.Rect;
import android.view.MotionEvent;

import com.example.liu.mygame.global.Config;
import com.example.liu.mygame.model.BaseModel;
import com.example.liu.mygame.model.TouchAble;

public class Sun extends BaseModel implements TouchAble {

    // λ��
    private int locationX;
    private int locationY;
    // ����
    private boolean isAlife;
    // �ɴ�������
    private Rect touchArea;
    // �������ʱ��
    private long birthTime;
    // ��ʾ�����״̬
    private SunState state;

    // �ƶ�����
    private int DirectionDistanceX;
    private int DirectionDistanceY;

    // XY�����ϵ��ٶȷ���
    // ����֡����ȷ���ƶ�ʱ�䣬Ȼ����ȷ���ƶ���ʽ
    private float SpeedX;
    private float SpeedY;

    // �ô�ö������ʾ�����״̬
    // ����״̬����ֹ���ƶ�
    // �ƶ��������������ڶ�������Ч����ֹʱ����������Ч
    public enum SunState {
        SHOW, MOVE
    }

    public Sun(int locationX, int locationY) {
        this.locationX = locationX;
        this.locationY = locationY;
        this.isAlife = true;
        // ��ʼ��������Ӧ��������
        // ����ÿ��������˵�ܳ�û�ĵط�ֻ��������ͼƬ��С������
        touchArea = new Rect(locationX, locationY, locationX
                + Config.sun.getWidth(), locationY + Config.sun.getHeight());
        // ��ȡϵͳʱ��
        birthTime = System.currentTimeMillis();
        // ��ʼʵ����ΪSHOW״̬
        state = SunState.SHOW;
    }

    @Override
    public void drawSelf(Canvas canvas, Paint paint) {
        // TODO Auto-generated method stub
        if (isAlife) {

            if (state == SunState.SHOW) {
                // �жϵ�ǰϵͳʱ������ȳ���ʱ���5000������ô����������������ʧ
                if (System.currentTimeMillis() - birthTime > Config.lifeTime) {
                    isAlife = false;
                }
            } else {// ����move״̬������Ĵ���
                // �ƶ�
                locationX -= SpeedX;
                locationY -= SpeedY;
                // ���ͼƬ��Y�������ƶ���������Ļ����˵�ƶ�������Ļ��ƽ����ô�������ڽ���
                if (locationY <= 0) {
                    // ȥ������
                    isAlife = false;
                    // �ı�����ֵ
                    Config.sunlight += 25;
                }
            }

            canvas.drawBitmap(Config.sun, locationX, locationY, paint);

        }
    }

    // �����¼���Ӧ
    @Override
    public boolean onTouch(MotionEvent event) {
        // TODO Auto-generated method stub
        // ��ȡ������
        int x = (int) event.getX();
        int y = (int) event.getY();
        // ����������ڿɴ���������
        if (touchArea.contains(x, y)) {
            // ��ʼ�˶����Ҳ��ɱ������ͬʱ���ܻ����ϱ߿������ײ�¼�
            // �ƶ�������Ҳ��Ҫʱ�䣬�������ռ�ʱ�������˳�����������ֵ5���ʱ��
            // ��ô������Ҫ�ڵ���Ժ�ı������״̬��ɾ��ԭ���ľ�̬����
            state = SunState.MOVE;
            // �ı�״̬�Ժ���ô��Ҫ��ʼ�ƶ����ƶ�����㲻һ���������յ���һ����
            // �ƶ����յ������Ϊ�����ο�seedBank�������Ͻǵ�
            // ��ʼ����Ǵ�����ͼƬ�����Ͻ�
            // XY�����ϵ��ƶ�����
            DirectionDistanceX = locationX - Config.seedBankLocationX;
            DirectionDistanceY = locationY;
            // �ƶ��ٶȷ����ļ��㣬����֡����Ҫ��Ŀ��������������Ϊ20֡
            SpeedX = DirectionDistanceX / 20f;
            SpeedY = DirectionDistanceY / 20f;
            return true;
        }
        return false;
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
