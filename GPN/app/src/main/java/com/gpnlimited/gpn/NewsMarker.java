package com.gpnlimited.gpn;

import android.graphics.Bitmap;

import java.util.ArrayList;

/**
 * Created by Johnny on 5/4/2015.
 */
public class NewsMarker {
    private String newsMK;
    private String username;
    private String newsTitle;
    private String latitude;
    private String longitude;
    private String newsPhoto;
    private Bitmap newsPhotoBitmap;


    public NewsMarker(String newsMK, String username, String newsTitle, String latitude, String longitude, String newsPhoto, Bitmap newsPhotoBitmap) {
        this.newsMK = newsMK;
        this.username = username;
        this.newsTitle = newsTitle;
        this.latitude = latitude;
        this.longitude = longitude;
        this.newsPhoto = newsPhoto;
        this.newsPhotoBitmap = newsPhotoBitmap;
    }

    public String getNewsMK() {
        return newsMK;
    }

    public String getUsername() {
        return username;
    }

    public Bitmap getNewsPhotoBitmap() { return newsPhotoBitmap; }

    public String getNewsTitle() {
        return newsTitle;
    }

    public String getLatitude() {
        return latitude;
    }

    public String getLongitude() {
        return longitude;
    }

    public String getNewsPhoto() {
        return newsPhoto;
    }
}
