<?php

use kring\core\Controller;

/*
 * Copyright 2021 sjnx.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * This controller is made for all Server Sent Events via JavaScript 
 * also this will connected with server and client devices
 * @author sjnx
 */
class Sse extends Controller {

    public $adminarea;

    function __construct() {
        parent::__construct();
        $this->adminarea = 0;
    }

    function model() {
        return $this->loadmodel('home');
    }

    function index($pr) {
        if (isset($_REQUEST['fd'])) {
            $data['title'] = "sse Controller";
//$this->tg('home/dashboard.html', $data);
        } else {
            $data['title'] = "Admin Dashboard";
            $data['var'] = "Variable";
            $this->tg('home/dashboard.html', $data);
        }
    }

    function islogedin() {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        if (isset($_SESSION['UsrID']) && isset($_SESSION['UsrName']) && isset($_SESSION['UsrRole'])) {
            echo "data: true\n\n";
        } else {
            echo "data: false\n\n";
        }
        flush();
    }

    function got_msg() {
        $unreadedQ = $this->model()->msg_Q();
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        echo "data: {$unreadedQ}\n\n";

        flush();
    }

}
