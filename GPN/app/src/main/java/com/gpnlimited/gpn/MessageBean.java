package com.gpnlimited.gpn;

import android.graphics.Bitmap;

public class MessageBean {

    private int REGISTEREDUSER_MK_FROM;
    private String USERNAME_FROM;
    private String SENT;
    private String PMCONTENT;

    private int NEWSPOST_MK;
    private String PROFILEPIC;
    private String NEWSTITLE;
    private String LATITUDE;
    private String LONGITUDE;

    private int icon;

    public void setIcon(int icon) {
        this.icon = icon;
    }

    public int getIcon() {
        return icon;
    }

    public int getREGISTEREDUSER_MK_FROM() {
        return REGISTEREDUSER_MK_FROM;
    }

    public void setREGISTEREDUSER_MK_FROM(int REGISTEREDUSER_MK_FROM) {
        this.REGISTEREDUSER_MK_FROM = REGISTEREDUSER_MK_FROM;
    }

    public String getUSERNAME_FROM() {
        return USERNAME_FROM;
    }

    public void setUSERNAME_FROM(String USERNAME_FROM) {
        this.USERNAME_FROM = USERNAME_FROM;
    }

    public String getSENT() {
        return SENT;
    }

    public void setSENT(String SENT) {
        this.SENT = SENT;
    }

    public String getPMCONTENT() {
        return PMCONTENT;
    }

    public void setPMCONTENT(String PMCONTENT) {
        this.PMCONTENT = PMCONTENT;
    }

    public int getNEWSPOST_MK() {
        return NEWSPOST_MK;
    }

    public void setNEWSPOST_MK(int NEWSPOST_MK) {
        this.NEWSPOST_MK = NEWSPOST_MK;
    }

    public String getPROFILEPIC() {
        return PROFILEPIC;
    }

    public void setPROFILEPIC(String PROFILEPIC) {
        this.PROFILEPIC = PROFILEPIC;
    }

    public String getNEWSTITLE() {
        return NEWSTITLE;
    }

    public void setNEWSTITLE(String NEWSTITLE) {
        this.NEWSTITLE = NEWSTITLE;
    }

    public String getLATITUDE() {
        return LATITUDE;
    }

    public void setLATITUDE(String LATITUDE) {
        this.LATITUDE = LATITUDE;
    }

    public String getLONGITUDE() {
        return LONGITUDE;
    }

    public void setLONGITUDE(String LONGITUDE) {
        this.LONGITUDE = LONGITUDE;
    }
}
