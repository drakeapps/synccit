<?php

class User {

    public $id;
    public $username;
    public $email;
    public $lastlogin;
    public $lastactivity;
    public $lastip;
    public $numlinks;
    public $numcomments;
    public $attempts;
    public $created;
    public $passhash;
    public $salt;
    public $loggedIn = false;


    public function login($u) {
        // this doesn't take into account any authentication
        // it assumes session has already been authorized
        // this can also just allow you to get user information about any user
        $u = (int) $u;
        if($u == 0) {
            return false;
        }
        $sql = "SELECT * FROM `user` WHERE `id` = '".pg_escape_string($u)."' LIMIT 1";

        $res = pg_query($sql);
        if($res->num_rows < 1) {
            return false;
        }
        $user = pg_fetch_array($res, null, PGSQL_ASSOC);

        $this->id = $u;
        $this->username = htmlspecialchars($user["username"]);
        $this->email = htmlspecialchars($user["email"]);
        $this->lastlogin = htmlspecialchars($user["lastlogin"]);
        $this->lastactivity = htmlspecialchars($user["lastactivity"]);
        $this->lastip = htmlspecialchars($user["lastip"]);
        $this->numlinks = htmlspecialchars($user["numlinks"]);
        $this->numcomments = htmlspecialchars($user["numcomments"]);
        $this->attempts = htmlspecialchars($user["attempts"]);
        $this->created = htmlspecialchars($user["created"]);
        $this->passhash = $user["passhash"];
        $this->salt = $user["salt"];


        $this->loggedIn = true;

        //TODO: add last activity/last ip

        return true;



    }


}
