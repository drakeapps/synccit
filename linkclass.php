<?php

class Link {

    public $id;
    public $redditid;
    public $url;
    public $firstvisit;
    public $lastvisit;
    public $numcomments;
    public $commenttime;
    public $userid;
    public $devices;
    public $developers;

    public function loadLink($i) {

        global $mysql;
        $sql = "SELECT * FROM `links` WHERE `linkid` = '".$mysql->real_escape_string($i)."' LIMIT 1";

        if(!($res = $mysql->query($sql))) {
            return false;
        }

        $link = $res->fetch_assoc();

        $this->id = $link["id"];
        $this->redditid = $link["linkid"];
        $this->firstvisit = $link["firstvisit"];
        $this->lastvisit = $link["lastvisit"];
        $this->numcomments = $link["lastcommentcount"];
        $this->commenttime = $link["lastcommenttime"];
        $this->developers = $link["developers"];
        $this->devices = $link["lastcall"];

    }


}