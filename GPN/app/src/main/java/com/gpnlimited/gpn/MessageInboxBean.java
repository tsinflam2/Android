package com.gpnlimited.gpn;

import android.graphics.Bitmap;

public class MessageInboxBean {
    private int REGISTEREDUSER_MK_FROM;
    private int REGISTEREDUSER_MK_TO;
    private String sent;
    private String lastmsg;
    private String USERNAME;
    private String PROFILEPIC;
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

    public int getREGISTEREDUSER_MK_TO() {
        return REGISTEREDUSER_MK_TO;
    }

    public void setREGISTEREDUSER_MK_TO(int REGISTEREDUSER_MK_TO) {
        this.REGISTEREDUSER_MK_TO = REGISTEREDUSER_MK_TO;
    }

    public String getSent() {
        return sent;
    }

    public void setSent(String sent) {
        this.sent = sent;
    }

    public String getLastmsg() {
        return lastmsg;
    }

    public void setLastmsg(String lastmsg) {
        this.lastmsg = lastmsg;
    }

    public String getUSERNAME() {
        return USERNAME;
    }

    public void setUSERNAME(String USERNAME) {
        this.USERNAME = USERNAME;
    }

    public String getPROFILEPIC() {
        return PROFILEPIC;
    }

    public void setPROFILEPIC(String PROFILEPIC) {
        this.PROFILEPIC = PROFILEPIC;
    }
}
