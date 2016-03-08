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

        $sql = "SELECT * FROM `links` WHERE `linkid` = '".pg_escape_string($i)."' LIMIT 1";

        if(!($res = pg_query($sql))) {
            return false;
        }

        $link = pg_fetch_array($res, null, PGSQL_ASSOC);

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
