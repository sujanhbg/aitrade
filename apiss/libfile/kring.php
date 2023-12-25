<?php

namespace kring\core;

use kring\auth\jwt;
use kring\database;

/*
 * Copyright (c) 2020, sjnx
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */
/*
 * It need to define the file location with same format
 */

class kring
{

    public $controllerName;
    public $methodname;
    public $arguments;
    public $appdir;
    private $jwt;

    function __construct()
    {
        $this->appdir = __ROOT__;
        $this->jwt = new jwt();
    }

    function setAppDir($dir)
    {
        return $dir;
    }

    function getApp()
    {
        require $this->appdir . "/configs/applications.php";
        $defappfolder = isset($app[$this->getrequestarr()[1]]) ? $app[$this->getrequestarr()[1]] : "apps";
        return $defappfolder;
    }

    function get_dir()
    {
        return $this->appdir;
    }

    function configfile($filename)
    {
        if (is_file($this->appdir . "/configs/{$filename}.php")) {
            require $this->appdir . "/configs/{$filename}.php";
        } else {
            exit($filename . " Can not be included;Please Check! the " . $this->appdir . "/configs/{$filename}.php");
        }
    }

    function getapps()
    {
        if (is_file($this->appdir . "/configs/applications.php")) {
            require $this->appdir . "/configs/applications.php";
            return $app;
        } else {
            exit($filename . " Can not be included;Please Check! the " . $this->appdir . "/configs/{$filename}.php");
        }
    }

    function get_siteid()
    {
        $kdb = new database\kdbal();
        $site = explode(".", $_SERVER['SERVER_NAME']);
        if ($site[1] == "phplab" && $site[2] == "xyz") {
            $sitename = $site[0];
            $siteid = $kdb->get_single_result("SELECT `ID` FROM `kbiz_subscription` WHERE `appname`='{$sitename}';");
        } else {
            $sitename = $_SERVER['SERVER_NAME'];
            $return = $kdb->get_single_result("SELECT `ID` FROM `kbiz_subscription` WHERE `custom_domain`='{$sitename}';");
        }

        exit($siteid);
    }

    function getDatabase()
    {
        $site = explode(".", $_SERVER['SERVER_NAME']);
        if ($site[1] == "phplab" && $site[2] == "xyz") {
            $sitename = $site[0];
        } else {
            $sitename = $_SERVER['SERVER_NAME'];
        }
        return "edb_" . $sitename;
    }

    function coreconf($varname)
    {
        require $this->appdir . "/configs/core_" . $this->getApp() . ".php";
        if (isset($core[$varname])) {
            return $core[$varname];
        } else {
            return false;
        }
    }

    function dbconf($varname)
    {
        if (is_file($this->appdir . "/configs/database_" . $this->getApp() . ".php")) {
            require $this->appdir . "/configs/database_" . $this->getApp() . ".php";
        } else {
            require $this->appdir . "/configs/database.php";
            //echo $this->appdir . "/configs/database_" . $this->getApp() . ".php" . " in not loaded......";
        }

        if (isset($db[$varname])) {
            return $db[$varname];
        } else {
            return false;
        }
    }

    function conf($key)
    {
        if ($this->coreconf('GetCnfValFromDB') == true) {
            $dval = new \kring\database\dbal();
            return $dval->get_single_result("SELECT value FROM configs WHERE name='{$key}' LIMIT 1;");
        } else {
            return null;
        }
    }

    function kcnf($key)
    {
        $dval = new \kring\database\dbal();
        return $dval->get_single_result("SELECT '{$key}' FROM configs WHERE  LIMIT 1;");
    }

    function getV()
    {
        return $this->coreconf('defaultVersion');
    }

    function isloggedin()
    {
        //Chack if user deleted of blocked or suspended

        if (isset($_SESSION['UsrID'])) {
            $dval = new \kring\database\dbal();
            $useractive = $dval->get_single_result("SELECT ID FROM `user` WHERE `ID`= {$_SESSION['UsrID']}");
            $rt = isset($_SESSION['UsrID']) && isset($_SESSION['UsrName']) && isset($_SESSION['UsrRole']) && $useractive ? true : false;
        } else {
            $rt = isset($_SESSION['UsrID']) && isset($_SESSION['UsrName']) && isset($_SESSION['UsrRole']) ? true : false;
        }
        return $rt;
    }

    private function get_request()
    {
        return $_SERVER['REQUEST_URI'];
    }

    private function getrequestarr()
    {
        return explode("/", $this->get_request());
    }

    public function getClassName()
    {
        if ($this->getApp() == "apps") {
            if (isset($_GET['app'])) {
                $classname = ucfirst(strtolower($_GET['app']));
            } elseif (isset($this->getrequestarr()[1]) && strlen($this->getrequestarr()[1]) > 1) {
                $classname = ucfirst(strtolower($this->getrequestarr()[1]));
            } else {
                $classname = $this->coreconf('defaultController');
            }
        } else {
            if (isset($_GET['app'])) {
                $classname = ucfirst(strtolower($_GET['app']));
            } elseif (isset($this->getrequestarr()[2]) && strlen($this->getrequestarr()[2]) > 1) {
                $classname = ucfirst(strtolower($this->getrequestarr()[2]));
            } else {
                $classname = $this->coreconf('defaultController');
            }
        }
        //echo $classname;
        //exit();
        return $classname;
    }

    private function getClass()
    {
        $classname = $this->getClassName();
        if ($classname == "Css" || $classname == "Js" || $classname == "Asset") {
            require_once 'asset.php';
            return new assets();
        } else {

            if (is_file($this->appdir . '/' . $this->getApp() . '/' . $this->getV() . '/controllers/' . $classname . ".php")) {
                require_once $this->appdir . '/' . $this->getApp() . '/' . $this->getV() . '/controllers/' . $classname . ".php";
                return new $classname();
            } elseif (is_file($this->appdir . '/' . $this->getApp() . '/' . $this->getV() . '/ESApp/' . $classname . '/Ctrl_' . $classname . ".php")) {
                require_once $this->appdir . '/' . $this->getApp() . '/' . $this->getV() . '/ESApp/' . $classname . '/Ctrl_' . $classname . ".php";
                return new $classname();
            } else {
                require_once $this->appdir . '/' . $this->getApp() . '/' . $this->getV() . '/controllers/' . "Home.php";
                return new \Home();
            }
        }
    }

    function getAuthClass()
    {
        if (is_file($this->appdir . '/' . $this->getApp() . '/' . $this->getV() . '/controllers/' . "Auth.php")) {
            require_once $this->appdir . '/' . $this->getApp() . '/' . $this->getV() . '/controllers/' . "Auth.php";
            if (class_exists("Auth")) {
                return true;
            } else {
                require_once 'error.php';
                $err = new \errorhndlr();
                echo $err->error("Class Auth not found on Auth Controller", "Rename or define Class Name 'Auth'");
                return false;
            }
        } else {
            require_once 'error.php';
            $err = new \errorhndlr();
            echo $err->error("Controller Auth.php not found", "Create a Controller with Auth.php name");
            return false;
        }
    }

    public function getMethod()
    {
        if ($this->getApp() == "apps") {
            if (isset($_GET['opt'])) {
                $classname = strtolower($_GET['opt']);
            } elseif (isset($this->getrequestarr()[2]) && strlen($this->getrequestarr()[2]) > 1) {
                $classname = strtolower($this->getrequestarr()[2]);
            } else {
                $classname = $this->coreconf('defaultMethod');
            }
        } else {
            if (isset($_GET['opt'])) {
                $classname = strtolower($_GET['opt']);
            } elseif (isset($this->getrequestarr()[3]) && strlen($this->getrequestarr()[3]) > 1) {
                $classname = strtolower($this->getrequestarr()[3]);
            } else {
                $classname = $this->coreconf('defaultMethod');
            }
        }
        return $classname;
    }

    public function getparams()
    {
        $totalobj = count($this->getrequestarr());
        $totalindx = $totalobj - 1;
        $ret = [];

        if ($totalobj > 1) {
            $t = 2;
            while ($t <= $totalindx) {
                $ret[$t] = $this->getrequestarr()[$t];
                $t++;
            }
        }
        return $ret;
    }

    function incache($file)
    {
        $filename = $this->appdir . "/kdata/{$file}";
        if (!file_exists($filename)) {
            return false;
        } else {
            return true;
        }
    }

    function getcache($file)
    {
        $filename = $this->appdir . "/kdata/{$file}";
        if (!file_exists($filename)) {
            return false;
        } else {
            return file_get_contents($filename);
        }
    }

    function writecache($file, $content)
    {
        $filename = $this->appdir . "/kdata/{$file}";
        $myfile = fopen($filename, "w") or die("Unable to open file!");
        fwrite($myfile, $content);
        return $content;
    }

    private function ipdtls()
    {
        //http://ip-api.com/json/{query}?fields=4976639
        if ($this->coreconf('SaveIpDataInFile') == true) {
            $ip = $_SERVER['REMOTE_ADDR'];
            if (!file_exists($this->appdir . "/kdata/ipdata/{$ip}.json")) {
                $url = "http://ip-api.com/json/{$_SERVER['REMOTE_ADDR']}?fields=4976639";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_URL, $url);
                $result = curl_exec($ch);
                $myfile = fopen($this->appdir . "/kdata/ipdata/{$ip}.json", "w") or die("Unable to open file!");
                fwrite($myfile, $result);
                return $result;
            } else {
                return file_get_contents($this->appdir . "/kdata/ipdata/{$ip}.json");
            }
        } else {
            return null;
        }
    }

    private function addstats()
    {
        if ($this->coreconf('SaveLogInDb') == true) {
            $dbal = new database\dbal();
            $sessionid = session_id();
            $userip = $_SERVER['REMOTE_ADDR'];
            $useragent = $_SERVER['HTTP_USER_AGENT'];
            if (!$dbal->num_of_row("SELECT `ID` FROM `v_sessions` WHERE `session_id`='{$sessionid}'")) {
                $sess = "INSERT INTO `v_sessions`"
                    . "(`ID`, `session_id`, `user_ip`, `date_time`, `user_agent`)"
                    . " VALUES "
                    . "(NULL,'{$sessionid}','{$userip}',NOW(),'{$useragent}')";
                $dbal->query_exc($sess);
            }
            $pageurl = $this->get_request();
            $getpageid = $dbal->get_single_result("SELECT ID FROM v_pages WHERE pageurl='{$pageurl}'");
            $pageid = $getpageid ? $getpageid : $dbal->insert('v_pages', ['pageurl' => $pageurl]);
            $dbal->insert('v_visit', ["session_id" => $sessionid, "page_id" => $pageid]);
        }
    }

    public function get_version()
    {
        return "Version 1.0.0 (First Version)";
    }

    function load_helpers()
    {
        foreach (explode(",", $this->coreconf('helpersClass')) as $value) {
            if (is_file($this->appdir . '/' . $this->getApp() . '/' . $this->getV() . '/helper/helper_' . $value . ".php")) {
                require_once $this->appdir . '/' . $this->getApp() . '/' . $this->getV() . '/helper/helper_' . $value . ".php";
            }
        }
    }

    function isaccess()
    {
        $userrole = isset($_SESSION['UsrRole']) ? $_SESSION['UsrRole'] : 0;
        $userID = isset($_SESSION['UsrID']) ? $_SESSION['UsrID'] : 0;
        $app = $this->getClassName();
        $opt = $this->getMethod();
        if ($this->coreconf('advancedPermission') == true) {
            if ($userrole == 21 || $userrole == 22) {
                return true;
            } else {
                if ($app == "Home") {
                    return true;
                } else {
                    $dval = new \kring\database\dbal();
                    return $dval->get_single_result("SELECT ID  FROM usergranted_options WHERE userID='{$userID}' AND appname='{$app}' AND isgrant='1' LIMIT 1 ");
                }
            }
        } else {
            return true;
        }
    }

    function isaccessByJwt()
    {
        $app = $this->getClassName();
        $opt = $this->getMethod();
        //exit($app);
        if (in_array($app, ['Home', 'Auth', 'Registration'], true)) {
            return true;
        } else {
            $uid = $this->jwt->juid();
            //echo "Uid found-" . $uid;
            $dbal = new database\dbal();
            $userrole = !$dbal->get_single_result("SELECT role FROM user WHERE ID={$uid}") ?
                $dbal->get_single_result("SELECT role FROM persons WHERE ID={$uid}") : $dbal->get_single_result("SELECT role FROM user WHERE ID={$uid}");
            $userID = $uid;

            if ($this->coreconf('advancedPermission') == true) {
                if ($userrole == 21 || $userrole == 22) {
                    return true;
                } else {
                    $dval = new \kring\database\dbal();
                    //$getappssnumber = $dval->get_single_result("SELECT ID FROM priv_options WHERE appName='{$app}' AND optName='{$opt}' LIMIT 1");
                    if ($app == "home") {
                        return true;
                    } else {
                        return $dval->get_single_result("SELECT ID  FROM usergranted_options WHERE userID='{$userID}' AND appname='{$app}' AND isgrant='1' LIMIT 1 ");
                    }
                }
            } else {
                return true;
            }
        }
    }

    function jwt_login()
    {
        $uid = $this->jwt->juid();
        $dbal = new database\dbal();
        $numR = $dbal->num_of_row("SELECT role FROM user WHERE ID={$uid}");
        $isIn = !$numR ? $dbal->num_of_row("SELECT role FROM persons WHERE ID={$uid}") : $numR;
        if ($isIn == 1) {
            $return = true;
        } else {
            $return = false;
        }
        return $return;
    }

    function is_suspended()
    {
        $kdb = new \kring\database\kdbal();
        return $kdb->kbiz_status();
    }

    public function Run()
    {
        require_once 'error.php';
        $err = new \errorhndlr();
        if ($this->is_suspended() == "suspend") {
            echo $err->suspended();
        } else {

            //print_r($ipd);
            $this->load_helpers();
            if ($this->coreconf('accesslimit') == true) {
                $ipd = json_decode($this->ipdtls(), true);
                if (!in_array($ipd['country'], $this->coreconf('AllowedCountry'))) {
                    //echo "This Site is not available in your country";
                    setcookie('llid', $ipd['country']);
                    header("HTTP/1.0 404 Not Found");
                    exit();
                }
                if ($this->coreconf('AllowProxy') == false) {
                    if ($ipd['proxy'] == 1 || $ipd['proxy'] == true) {
                        setcookie('llid', $ipd['country']);
                        header("HTTP/1.0 404 Not Found");
                        exit();
                    }
                }
            }

            $method = $this->getMethod();
            if (method_exists($this->getClass(), $method)) {
                if ($this->getClass()->adminarea == 1 && !$this->isloggedin() && $this->coreconf("loginwithDB") == true) {
                    if (in_array($method, ['login', 'register', 'index', 'logout'], true)) {
                        if ($this->getAuthClass()) {
                            $auth = new \Auth();
                            $auth->$method($this->getparams());
                        }
                    } else {
                        echo $err->index([]);
                    }
                } else {
                    if ($this->isaccess() == true) {
                        $pagejs = isset($this->getClass()->pagejs) ? $this->getClass()->pagejs : 0;
                        if ($pagejs == 1 && !isset($_GET['fd'])) {
                            $this->getClass()->index($this->getparams());
                        } else {
                            $this->getClass()->$method($this->getparams());
                        }
                    } elseif ($this->getClassName() == "Home") {
                        $this->getClass()->$method($this->getparams());
                    } elseif ($this->getClassName() == "Myprofile") {
                        $this->getClass()->$method($this->getparams());
                    } elseif ($this->getClassName() == "Auth") {
                        $this->getClass()->$method($this->getparams());
                    } else {
                        //print_r($_SESSION);
                        if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
                            echo "<div class=\"w3-panel w3-card w3-pale-red w3-text-red w3-xlarge\"><p>"
                                . "<i class=\"fa fa-times\" aria-hidden=\"true\"></i> "
                                . "Permission Denied</p></div>";
                        } else {
                            echo $err->permission([]);
                        }
                    }
                }

                // . "()";
            } elseif ($this->getClassName() == "Css") {
                $this->getClass()->css($this->getparams());
            } elseif ($this->getClassName() == "Js") {
                $this->getClass()->jscript($this->getparams());
            } elseif ($this->getClassName() == "Asset") {
                $this->getClass()->asset();
            } else {

                echo $err->index([]);
            }
            $this->addstats();
            $this->ipdtls();
            //print_r($this->getparams());
        }
    }

    public function Run_restapi_json()
    {

        //print_r($ipd);
        $this->load_helpers();
        if ($this->coreconf('accesslimit') == true) {
            $ipd = json_decode($this->ipdtls(), true);
            if (!in_array($ipd['country'], $this->coreconf('AllowedCountry'))) {
                //echo "This Site is not available in your country";
                setcookie('llid', $ipd['country']);
                header("HTTP/1.0 404 Not Found");
                exit();
            }
            if ($this->coreconf('AllowProxy') == false) {
                if ($ipd['proxy'] == 1 || $ipd['proxy'] == true) {
                    setcookie('llid', $ipd['country']);
                    header("HTTP/1.0 404 Not Found");
                    exit();
                }
            }
        }
        $method = $this->getMethod();

        if (method_exists($this->getClass(), $method)) {

            if ($this->isaccessByJwt() == true) {
                if ($this->getClass()->adminarea == 1 && !$this->jwt_login()) {

                    $arr = ["error" => 1, "msg" => "Not Authorized!"];
                } else {
                    $arr = $this->getClass()->$method($this->getparams());
                }

                //$arr = ["error" => 0, "response" => $this->getClass()->$method($this->getparams())];
            } else {

                $arr = ["error" => 1, "msg" => "Permission Denied!"];
            }

            // . "()";
        } else {

            $arr = ["error" => 1, "msg" => "Method not exists!"];
        }
        $this->addstats();
        $this->ipdtls();


        $jsoncontent = json_encode($arr);




        header("Access-Control-Allow-Origin: *");
        header('Content-Type: application/json; charset=utf-8');
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
            exit();
        }
        echo $jsoncontent;
    }
    public function Run_restapi_json2()
    {

        //print_r($ipd);
        $this->load_helpers();
        if ($this->coreconf('accesslimit') == true) {
            $ipd = json_decode($this->ipdtls(), true);
            if (!in_array($ipd['country'], $this->coreconf('AllowedCountry'))) {
                //echo "This Site is not available in your country";
                setcookie('llid', $ipd['country']);
                header("HTTP/1.0 404 Not Found");
                exit();
            }
            if ($this->coreconf('AllowProxy') == false) {
                if ($ipd['proxy'] == 1 || $ipd['proxy'] == true) {
                    setcookie('llid', $ipd['country']);
                    header("HTTP/1.0 404 Not Found");
                    exit();
                }
            }
        }
        $method = $this->getMethod();

        if (method_exists($this->getClass(), $method)) {

            if ($this->isaccess() == true) {
                if ($this->getClass()->adminarea == 1 && !$this->isloggedin()) {

                    $arr = ["error" => 1, "msg" => "Not Authorized!", "UserID" => $_SESSION['UsrID']];
                } else {
                    $arr = $this->getClass()->$method($this->getparams());
                }

                //$arr = ["error" => 0, "response" => $this->getClass()->$method($this->getparams())];
            } else {

                $arr = ["error" => 1, "msg" => "Permission Denied! [r2:627]"];
            }

            // . "()";
        } else {

            $arr = ["error" => 1, "msg" => "Method not exists!"];
        }
        $this->addstats();
        $this->ipdtls();

        if (isset($arr['response']['pretty'])) {
            $jsoncontent = json_encode($arr, JSON_PRETTY_PRINT);
        } else {
            $jsoncontent = json_encode($arr);
        }



        header("Access-Control-Allow-Origin: *");
        header('Content-Type: application/json; charset=utf-8');
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
            exit();
        }
        echo $jsoncontent;
    }
}
