<?php

use kring\auth\jwt;
use kring\database\dbal;
use kring\utilities\comm;

class Model_auth
{

    private $jwt;

    public function __construct()
    {
        $this->jwt = new jwt();
    }

    public function comm()
    {
        return new comm();
    }

    public function dbal()
    {
        return new dbal();
    }

    public function checklogindb()
    {
        $email = trim($_POST['email']);
        $password = substr(md5($_POST['password']), 8, 23);
        if (!$email || !$password) {
            return false;
        } else {
            $sql = "SELECT `ID`,`firstname`,`lastname`,`email`,`role`,`photo`,`cell`,`username`,`active` FROM user WHERE `email`='{$email}' AND `password`='{$password}' LIMIT 1";
            $loginerror = $this->checkerror();

            if ($this->dbal()->num_of_row($sql)) {

                return $this->dbal()->query($sql);
            } else {
                $this->userlogineerrorinsert();
                return false;
            }
        }
    }

    public function insert_login_history($userid, $otheinfo = null)
    {
        $ipaddr = $_SERVER['REMOTE_ADDR'];
        $sql = "INSERT INTO `user_login_history`(`UID`, `logintime`, `IP`, `otherdtl`) "
            . "VALUES "
            . "('{$userid}',NOW(),'{$ipaddr}','{$otheinfo}')";
        $this->dbal()->query_exc($sql);
    }

    public function userlogineerrorinsert()
    {
        $usernm = trim($_REQUEST['email'], "'");
        $pass = trim($_REQUEST['password'], "'");
        $ip = $_SERVER['REMOTE_ADDR'];
        $otherinfo = "Host- " . gethostbyaddr($ip);
        $sessid = session_id();
        $insert_sql = "INSERT INTO  `user_loginerr` (
        `usernm` ,
        `pass` ,
        `time` ,
        `ip` ,
        `otherinfo`,`sess` )VALUES(
        '$usernm',
        '$pass',
         NOW(),
        '$ip',
        '$otherinfo','{$sessid}'
        )";
        $this->dbal()->query_exc($insert_sql);
        // AND update_time < date_sub(NOW() - interval 2 minute)
    }

    public function checkerror()
    {
        $usernm = trim($_REQUEST['email'], "'");
        $sql = "SELECT * FROM user_loginerr WHERE `usernm`='$usernm'  AND `time` > date_sub(now(), interval 2 minute)";
        return $this->dbal()->num_of_row($sql);
    }

    public function login_history_insert($UID)
    {
        $insert_sql = "INSERT INTO  `user_login_history` (
        `UID` ,
        `date` ,
        `IP` ,
        `otherdtl`)VALUES(
        '$UID',
         NOW(),
        '{$_SERVER['REMOTE_ADDR']}',
        '{$_SERVER['HTTP_USER_AGENT']}'
        )";
        $this->dbal()->query_exc($insert_sql);
    }

    public function login()
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {

            if ($this->checkerror() > 6) {
                return ["status" => 'error', 'msg' => "A few times attempted! you are not able to log in"];
            } elseif ($this->checklogindb()) {
                foreach ($this->checklogindb() as $content) {
                    $ID = stripslashes($content['ID']);
                    $role = stripslashes($content['role']);
                    $active = stripslashes($content['active']);
                    $name = stripcslashes($content['firstname']) . " " . stripcslashes($content['lastname']);
                    $photo = stripcslashes($content['photo']);

                    if ($active == 1) {
                        if ($role == 22 || $role == 21 || $role == 23 || $role == 24 || $role == 44) {
                            $token = $this->jwt->issue_jwt($ID);
                            $this->insert_login_history($ID);
                            return ['status' => 'success', 'uid' => $ID, 'token' => $token, 'name' => $name, 'photo' => $photo];
                        } else {
                            return ["status" => 'error', 'msg' => "You are not able to login here....."];
                        }
                    } else {
                        $this->login_history_insert($ID);
                        return ["status" => 'error', 'msg' => "Your Account is not Active! Please contact with Support"];
                    }
                }
            } else {
                return ["status" => 'error', 'msg' => "Login Error", 'exinfo' => $this->dbal()->info()];
            }
        } else {
            return ["status" => 'error', 'msg' => "form data not submitted"];
        }
    }

    public function get_user_info()
    {
        $return = $this->dbal()->query("SELECT `firstname`,`lastname`,`email`,`role`,`photo`,`cell`,`username` FROM `user` WHERE `ID`={$this->jwt->get_uid()['uid']} LIMIT 1")[0];
        return $return;
    }

    public function userid()
    {
        return $this->jwt->get_uid()['uid'];
    }

    public function is_in()
    {
        return $this->jwt->get_uid();
    }
}
