<?php

session_start();

class Session {

    public $user;
    public $hash;
    public $id;
    public $hasLoggedIn = false;



    public function generateHash() {


        //$this->hash = uniqid("", TRUE);
        $this->hash = hash("sha256", uniqid("", TRUE));

    }

    public function setID($i) {
        $this->id = $i;
    }

    public function setUser($u) {
        $this->user = $u;
    }

    public function setHash($h) {
        $this->hash = $h;
    }

    public function setPHPSession() {
        if(!isset($this->id) || !isset($this->hash)) {
            return false;
        }

        $_SESSION['id'] = $this->id;
        $_SESSION['hash'] = $this->hash;

        return true;
    }

    public function restorePHPSession() {
        if(isset($_SESSION['id']) && isset($_SESSION['hash'])) {
            $this->id = $_SESSION['id'];
            $this->hash = $_SESSION['hash'];
            return true;
        } else {
            return false;
        }
    }

    public function destroyPHPSession() {
        $_SESSION['id'] = null;
        $_SESSION['hash'] = null;
        return true;
    }

    public function isLoggedIn() {

        $this->restorePHPSession();
        global $mysql;
        $sql = "SELECT * FROM `logincodes` WHERE `id` = '".$mysql->real_escape_string($this->id)."' LIMIT 1";


        $result = $mysql->query($sql);
        if($result->num_rows > 0) {
            $info = $result->fetch_assoc();
            if(strcmp($info["id"], $this->id) == 0) {
                $this->setHash($info["authhash"]);
                $this->setUser($info["userid"]);
                $this->hasLoggedIn = true;
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }

    }


}


$session = new Session();