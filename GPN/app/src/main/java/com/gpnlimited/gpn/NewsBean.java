package com.gpnlimited.gpn;

import java.util.ArrayList;

/**
 * Created by Johnny on 3/29/2015.
 */
public class NewsBean {
    int mk;
    String newstitle;
    String newsdescription;
    String latitude;
    String longitude;
    String postedat;
    String username;
    String profilepic;
    String favourited;
    String hasfavourited;
    int viewcount;
    int registereduser_mk;
    int commentsCount;
    int distance;
    ArrayList<VPath> videos;
    ArrayList<PPath> photos;

    public NewsBean() {
    }
}

class VPath {
    String youtube;
}

class PPath {
    String jpg;
}