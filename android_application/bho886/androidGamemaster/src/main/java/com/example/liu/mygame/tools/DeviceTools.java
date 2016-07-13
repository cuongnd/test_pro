package com.example.liu.mygame.tools;

import com.example.liu.mygame.global.Config;

import android.app.Activity;
import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.Matrix;
import android.util.DisplayMetrics;
import android.util.Log;

public class DeviceTools {
    private static int[] deviceWidthHeight = new int[2];

    // ��������Bitmap�Ĵ�С
    public static Bitmap resizeBitmap(Bitmap bitmap) {
        if (bitmap != null) {
            int width = bitmap.getWidth();
            int height = bitmap.getHeight();
            Log.i("info", width + "," + height);
            Matrix matrix = new Matrix();
            matrix.postScale(Config.scaleWidth, Config.scaleHeight);
            Bitmap resizedBitmap = Bitmap.createBitmap(bitmap, 0, 0, width,
                    height, matrix, true);
            return resizedBitmap;
        } else {
            return null;
        }
    }

    // ����
    // ԭ�����ҵ����ز���Ҫ���д�������Ӧ�ֻ���Ļ���ȱȲ���,��������ϳɵ����Ų���ͼ���ɱ��� ��ô�Ͳ��ò���������������Ӧ
    // ���ȴ���һ��bitmap�������Ŀ��
    public static Bitmap resizeBitmap(Bitmap bitmap, int w, int h) {
        if (bitmap != null) {
            // ��ȡ�����ͼƬ���
            int width = bitmap.getWidth();
            int height = bitmap.getHeight();
            // ���������Ŀ��
            int newWidth = w;
            int newHeight = h;
            // ���ű�
            float scaleWidth = ((float) newWidth) / width;
            float scaleHeight = ((float) newHeight) / height;
            // ͼƬ�������3X3����
            Matrix matrix = new Matrix();
            // �����űȴ�����������
            matrix.postScale(scaleWidth, scaleHeight);
            // ����������ͼƬ
            Bitmap resizeBitmap = Bitmap.createBitmap(bitmap, 0, 0, width,
                    height, matrix, true);
            return resizeBitmap;
        } else {
            return null;
        }
    }

    // ��ȡ��Ļ�Ŀ��
    // ��DisplayMetrics���п��Ի�ȡ��Ļ�����ȣ���ߣ�ˢ���ʵ������Ϣ
    public static int[] getDeviceInfo(Context context) {
        if ((deviceWidthHeight[0] == 0) && (deviceWidthHeight[1] == 0)) {
            DisplayMetrics metrics = new DisplayMetrics();
            ((Activity) context).getWindowManager().getDefaultDisplay()
                    .getMetrics(metrics);

            deviceWidthHeight[0] = metrics.widthPixels;
            deviceWidthHeight[1] = metrics.heightPixels;
        }
        return deviceWidthHeight;
    }

}
