package com.gpnlimited.gpn;

/**
 * Created by Johnny on 5/3/2015.
 */
public class VideosBean {
    String videoPath;
    String youtubeID;

    public VideosBean(String videoPath) {
        this.videoPath = videoPath;
    }

    public VideosBean(String videoPath, String youtubeID) {
        this.videoPath = videoPath;
        this.youtubeID = youtubeID;
    }

    public String getVideoPath() {
        return videoPath;
    }

    public String getYoutubeID() {
        return youtubeID;
    }

}
