<?php

/*
 * Copyright (c) 2020, SCpc
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

namespace kring\core;

use kring\utilities\comm;

class controller
{

    public $adminarea;
    public $appdir;

    function __construct()
    {
        $this->appdir = __ROOT__;
    }

    function kring()
    {
        $kring = new Kring();
        return $kring;
    }

    function comm()
    {
        return new comm();
    }

    function kdv($key = "ID")
    {
        $kdbl = new \kring\database\kdbal();
        return $kdbl->get_single_result("SELECT `{$key}` FROM `kbiz_subscription` WHERE `appname`='{$kdbl->appname()}'");
    }

    function baseurl()
    {
        return $this->kring()->coreconf('baseurl');
    }

    function loadmodel($modelname)
    {
        $defaultVersion = $this->kring()->coreconf("defaultVersion");
        $modelfile = is_file($this->appdir . "/" . $this->kring()->getApp() . "/" . $defaultVersion . "/models/Model_" . $modelname . ".php") ?
            $this->appdir . "/" . $this->kring()->getApp() . "/" . $defaultVersion . "/models/Model_" . $modelname . ".php" : "File not found";
        //echo $this->appdir . "/apps/" . $defaultVersion . "/models/Model_" . $modelname . ".php\n";

        require_once $modelfile;
        $model = "Model_" . $modelname;
        return new $model();
    }

    function loadESmodel($modelname, $appname = null)
    {
        $defaultVersion = $this->kring()->coreconf("defaultVersion");
        $modelnm = $appname ? $appname : $modelname;
        $modelfolder = ucfirst($modelnm);
        $modelfile = is_file($this->appdir . "/" . $this->kring()->getApp() . "/" . $defaultVersion . "/ESApp/{$modelfolder}/Model_" . $modelname . ".php") ?
            $this->appdir . "/" . $this->kring()->getApp() . "/" . $defaultVersion . "/ESApp/{$modelfolder}/Model_" . $modelname . ".php" : "File not found(Model:{$modelname})";
        //echo $this->appdir . "/" . $this->kring()->getApp() . "/" . $defaultVersion . "/ESApp/{$modelfolder}/Model_" . $modelname . ".php";
        if (is_file($modelfile)) {
            require_once $modelfile;
            $model = "Model_" . $modelname;
            return new $model();
        } else {
            exit($modelfile);
        }
    }

    function loadcrud($modelname)
    {
        $defaultVersion = $this->kring()->coreconf("defaultVersion");
        $modelfile = is_file($this->appdir . "/" . $this->kring()->getApp() . "/" . $defaultVersion . "/crud/crud_" . $modelname . ".php") ?
            $this->appdir . "/" . $this->kring()->getApp() . "/" . $defaultVersion . "/crud/crud_" . $modelname . ".php" : "File not found";
        //echo $this->appdir . "/apps/" . $defaultVersion . "/models/Model_" . $modelname . ".php\n";

        require_once $modelfile;
        $model = "crud_" . $modelname;
        return new $model();
    }

    function init_crud($modelname)
    {
        $sopt = $this->comm()->rqstr('sopt') ? $this->comm()->rqstr('sopt') : "index";
        $bcd = $this->loadcrud($modelname);
        $bcd->$sopt();
    }

    function crud($crudname)
    {
        $defaultVersion = $this->kring()->coreconf("defaultVersion");
        $modelfile = is_file($this->appdir . "/" . $this->kring()->getApp() . "/" . $defaultVersion . "/crud/crud_" . $crudname . ".php") ?
            $this->appdir . "/" . $this->kring()->getApp() . "/" . $defaultVersion . "/crud/crud_" . $crudname . ".php" : "File not found";
        //echo $this->appdir . "/apps/" . $defaultVersion . "/models/Model_" . $modelname . ".php\n";

        require_once $modelfile;
        $model = "crud_" . $crudname;
        return new $model();
    }

    function includeFileContent($fileName, $data)
    {
        ob_start();
        if (is_array($data)) {
            extract($data);
        }
        ob_implicit_flush(false);
        include($fileName);
        return ob_get_clean();
    }

    function includeJScontent($fileName, $data)
    {
        if (is_file($fileName)) {
            ob_start();
            if (is_array($data)) {
                extract($data);
            }
            ob_implicit_flush(false);
            include($fileName);
            return "<script>" . ob_get_clean() . "</script>";
        } else {
            return '';
        }
    }

    function lvr($filename, $data)
    {
        $themepath = $this->kring()->coreconf('theme');
        $defaultVersion = $this->kring()->coreconf("defaultVersion");
        $requestedApp = isset($data['app']) ? $data['app'] : "eshome";
        $esviewFile = $this->appdir . "/" . $this->kring()->getApp() . "/" . $defaultVersion . "/ESApp/{$requestedApp}/";
        if (is_array($data)) {
            $lang['null'] = "None";
            $data = array_merge($data, $lang);
            $keys = null;
            foreach (array_keys($data) as $kaename) {
                $keys .= "{" . "$kaename" . "},";
            }
            $keysearch = explode(",", rtrim($keys, ","));
            $valuetoplce = null;
            foreach (array_values($data) as $keyvalues) {
                if (is_array($keyvalues)) {
                    $valuetoplce .= "None,";
                } else {
                    $valuetoplce .= $keyvalues . ",";
                }
            }
            $valuetoplce22 = explode(",", rtrim($valuetoplce, ","));

            $transform = [
                "{baseurl}" => $this->kring()->coreconf('baseurl'),
                "{ProjectName}" => $this->kring()->conf('ProjectName'),
                "{OrgName}" => $this->kring()->conf('OrgName'),
                "{api}" => $this->kring()->coreconf('api'),
                "{siteurl}" => $this->kring()->coreconf('siteurl')
            ];

            if (is_file($esviewFile . "/{$filename}.php")) {
                $loaderfile = $esviewFile . "/{$filename}.php";
                //echo $loaderfile . ".....";
            } elseif (is_file($themepath . "/{$filename}.php")) {
                $loaderfile = $themepath . "/{$filename}.php";
                //echo $filename . "-In system folder<br>";
            } else {

                $loaderfile = null;
                //echo "file--" . $esviewFile . "/{$filename}.php" . "--loaderfile not found<br>\n\n";
            }
            //include javascript file
            if (!isset($data['nojs'])) {
                if (is_file($esviewFile . "/{$filename}.js")) {
                    $jsfile = $this->includeJScontent($esviewFile . "/{$filename}.js", $data);
                } elseif (is_file($themepath . "/{$filename}.php")) {
                    $loaderfile = $themepath . "/{$filename}.php";
                    // echo $filename . "-In system folder<br>";
                    $jsfile = $this->includeJScontent($themepath . "/{$filename}.js", $data);
                } else {

                    //echo $filename . ".php File not found<br>";
                    $jsfile = '';
                }
            } else {
                $jsfile = '';
            }
            if ($loaderfile) {
                $themedatar = $this->includeFileContent($loaderfile, $data);
            } else {
                $themedatar = '';
            }

            $themedatar .= \JShrink\Minifier::minify($jsfile, array('flaggedComments' => false));
            $themedata = strtr($themedatar, $transform);

            // print_r($valuetoplce22);
            return str_replace($keysearch, $valuetoplce22, $themedata);
        } else {
            return "Error:: Data of this page cannot be initialize";
        }
    }
    function lvp($filename, $data)
    {
        $data['mejorapp'] = isset($data['mejorapp']) ? $data['mejorapp'] : "erp";
        $themepath = isset($data['theme']) ? $data['theme'] : $data['mejorapp'];
        $defthemepath = $this->kring()->coreconf('default_theme') . "/" . $themepath;


        if (is_array($data)) {
            $lang['null'] = "None";
            $data = array_merge($data, $lang);
            $keys = null;
            foreach (array_keys($data) as $kaename) {
                $keys .= "{" . "$kaename" . "},";
            }
            $keysearch = explode(",", rtrim($keys, ","));
            $valuetoplce = null;
            foreach (array_values($data) as $keyvalues) {
                if (is_array($keyvalues)) {
                    $valuetoplce .= "None,";
                } else {
                    $valuetoplce .= $keyvalues . ",";
                }
            }
            $valuetoplce22 = explode(",", rtrim($valuetoplce, ","));

            $transform = [
                "{baseurl}" => $this->kring()->coreconf('baseurl'),
                "{ProjectName}" => $this->kring()->conf('ProjectName'),
                "{OrgName}" => $this->kring()->conf('OrgName'),
                "{siteurl}" => $this->kring()->coreconf('siteurl'),
                '{themeurl}' => $this->kring()->coreconf('siteurl') . "/webtheme/" . $themepath
            ];

            if (is_file($defthemepath . "/{$filename}.php")) {
                $loaderfile = $defthemepath . "/{$filename}.php";
                //echo $filename . "-In system folder<br>";
            } else {

                $loaderfile = null;
                //echo "file--" . $esviewFile . "/{$filename}.php" . "--loaderfile not found<br>\n\n";
            }
            //include javascript file
            if (!isset($data['nojs'])) {
                if (is_file($defthemepath . "/{$filename}.js")) {
                    $jsfile = $this->includeJScontent($defthemepath . "/{$filename}.js", $data);
                } elseif (is_file($defthemepath . "/{$filename}.php")) {
                    $loaderfile = $defthemepath . "/{$filename}.php";
                    //echo $loaderfile . "-In system folder<br>";
                    $jsfile = $this->includeJScontent($defthemepath . "/{$filename}.js", $data);
                } else {

                    //echo $filename . ".php File not found<br>";
                    $jsfile = '';
                }
            } else {
                $jsfile = '';
            }
            if ($loaderfile) {
                $themedatar = $this->includeFileContent($loaderfile, $data);
            } else {
                $themedatar = '';
            }

            $themedatar .= \JShrink\Minifier::minify($jsfile, array('flaggedComments' => false));
            $themedata = strtr($themedatar, $transform);

            // print_r($valuetoplce22);
            return str_replace($keysearch, $valuetoplce22, $themedata);
        } else {
            return "Error:: Data of this page cannot be initialize";
        }
    }

    /*
     * LV direct print and
     * LVR only return the value;
     */

    //Some system methods are defines here
    function lv($filename, $data)
    {
        //echo $filename . "......";
        echo $this->lvr($filename, $data);
    }

    function loadview($filename, $data)
    {
        $this->lv($filename, $data);
    }

    public function tgr($filename, $data)
    {
        $themepath = $this->kring()->coreconf('theme');
        $defaultVersion = $this->kring()->coreconf("defaultVersion");
        $requestedApp = isset($data['app']) ? $data['app'] : "eshome";
        $esviewFile = $this->appdir . "/" . $this->kring()->getApp() . "/" . $defaultVersion . "/ESApp/{$requestedApp}/";
        //echo $esviewFile;
        if ($requestedApp != "eshome" && is_file($esviewFile . $filename . ".twig")) {
            $loaderpath = $esviewFile;
            //echo $filename . "-In system folder<br>";
            //echo "Load EsApp";
        } elseif (is_file($themepath . "/{$filename}.twig")) {
            $loaderpath = $themepath;
        } else {
            exit("Theme file not found");
        }

        $array = [
            'baseurl' => $this->kring()->coreconf('baseurl'),
            'ProjectName' => $this->kring()->conf('ProjectName'),
            'OrgName' => $this->kring()->conf('OrgName'),
            'logourl' => $this->kring()->conf('logourl'),
            'iconurl' => $this->kring()->conf('iconurl'),
            'favicourl' => $this->kring()->conf('faviconurl'),
            'siteurl' => $this->kring()->coreconf('siteurl'),
            'api' => $this->kring()->coreconf('apiurl')
        ];
        $loader = new \Twig\Loader\FilesystemLoader($loaderpath);
        $twig = new \Twig\Environment($loader, ['cache' => $this->appdir . "/cache",]);
        $twig = new \Twig\Environment($loader, ['debug' => true]);
        $twig->addGlobal('session', $_SESSION);
        $twig->addGlobal('loggedin', $this->kring()->isloggedin());
        return $twig->render($filename . ".twig", array_merge($data, $array));
    }

    public function tgp($filename, $data)
    {

        $data['mejorapp'] = isset($data['mejorapp']) ? $data['mejorapp'] : "erp";
        $themepath = isset($data['theme']) ? $data['theme'] : $data['mejorapp'];
        $defthemepath = $this->kring()->coreconf('default_theme') . "/" . $themepath;

        if (is_file($defthemepath . "/{$filename}.twig")) {
            $loaderpath = $defthemepath;
        } else {
            exit("Theme file <u>{$defthemepath}/{$filename}</u> not found");
        }

        $array = [
            'baseurl' => $this->kring()->coreconf('baseurl'),
            'siteurl' => $this->kring()->coreconf('siteurl'),
            'themeurl' => $this->kring()->coreconf('siteurl') . "/webtheme/" . $data['mejorapp']
        ];
        $loader = new \Twig\Loader\FilesystemLoader($loaderpath);
        $twig = new \Twig\Environment($loader, ['cache' => $this->appdir . "/cache",]);
        $twig = new \Twig\Environment($loader, ['debug' => true]);
        $twig->addGlobal('session', $_SESSION);
        $twig->addGlobal('loggedin', $this->kring()->isloggedin());

        echo $twig->render($filename . ".twig", array_merge($data, $array));
    }

    public function tg($filename, $data)
    {
        echo $this->tgr($filename, $data);
    }

    public function rendTxt($output)
    {
        header("Content-Type: text/plain;charset=utf-8");
        echo $output;
    }

    public function rendJson($param)
    {
        header('Content-Length: ' . strlen($param));
        header('Content-type:application/json;charset=utf-8');
        echo $param;
    }

    public function rend($output)
    {
        echo $output;
    }

    public function rend_fd($param)
    {
        if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
            return $param;
        } else {
            return $this->tg('home/dashboard.html', ['title' => "Kring"]);
        }
    }
}
