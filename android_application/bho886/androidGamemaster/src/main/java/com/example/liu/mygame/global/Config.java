package com.example.liu.mygame.global;

import android.graphics.Bitmap;
import android.graphics.Point;

import java.util.HashMap;

//����
public class Config {
    public static float scaleWidth;
    public static float scaleHeight;

    public static int deviceWidth;
    public static int deviceHeight;

    public static Bitmap gameBK;
    public static Bitmap seedBank;

    public static Bitmap gameOver;

    // seedBank��λ��X����
    public static int seedBankLocationX;

    public static Bitmap seedFlower;
    public static Bitmap seedPea;

    // ����
    public static Bitmap sun;
    // ���������ʱ��5000����
    public static long lifeTime = 5000;
    // ���ڵ�����ֵ
    public static int sunlight = 200;
    // ��ʬ��ֲ��ͼƬ�ĸ߶Ȳ�
    public static int heightYDistance;

    // �ӵ�
    public static Bitmap bullet;

    // ��ͼƬ֡��������
    public static Bitmap[] flowerFrames = new Bitmap[8];
    public static Bitmap[] peaFrames = new Bitmap[8];
    public static Bitmap[] zombieFrames = new Bitmap[7];

    // ����ֲ��ĵ�
    public static HashMap<Integer, Point> plantPoints = new HashMap<Integer, Point>();
    // �ܵ�
    public static int[] raceWayYpoints = new int[5];

    // ��Ӯ�жϱ�־
    public static boolean game = true;

}
