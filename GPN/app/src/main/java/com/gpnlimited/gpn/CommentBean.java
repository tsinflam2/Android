package com.gpnlimited.gpn;

/**
 * Created by Johnny on 4/9/2015.
 */
public class CommentBean {
    String username;
    String comment;
    String postedat;
    String profilepic;
    int registereduser_mk;

    public CommentBean() {
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getComment() {
        return comment;
    }

    public void setComment(String comment) {
        this.comment = comment;
    }

    public String getPostedat() {
        return postedat;
    }

    public void setPostedat(String postedat) {
        this.postedat = postedat;
    }

    public String getProfilepic() {
        return profilepic;
    }

    public void setProfilepic(String profilepic) {
        this.profilepic = profilepic;
    }

    public int getRegistereduser_mk() {
        return registereduser_mk;
    }

    public void setRegistereduser_mk(int registereduser_mk) {
        this.registereduser_mk = registereduser_mk;
    }
}
