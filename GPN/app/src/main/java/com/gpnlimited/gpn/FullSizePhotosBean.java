package com.gpnlimited.gpn;

import android.graphics.Bitmap;

/**
 * Created by Johnny on 5/2/2015.
 */
public class FullSizePhotosBean {
    Bitmap FSPhoto;
    String FSPhotoPath;

    public FullSizePhotosBean(Bitmap FSPhoto, String FSPhotoPath) {
        this.FSPhoto = FSPhoto;
        this.FSPhotoPath = FSPhotoPath;
    }

    public FullSizePhotosBean(Bitmap FSPhoto) {
        this.FSPhoto = FSPhoto;
    }

    public String getFSPhotoPath() {
        return FSPhotoPath;
    }

    public Bitmap getFSPhoto() {
        return FSPhoto;
    }
}
