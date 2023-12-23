<?php

use kring\core;
use kring\database AS db;
use kring\utilities\comm;

/*
 * The MIT License
 *
 * Copyright 2021 sjnx.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

class Model_home {

    function __construct() {
        
    }

    function comm() {
        return new comm();
    }

    function dbal() {
        return new db\dbal();
    }

    function kdbal() {
        return new db\kdbal();
    }

    function kring() {
        return new \kring\core\kring();
    }

    function baseurl() {
        return $this->kring()->conf('baseurl');
    }

    function get_kring_data() {
        $app = explode(".", $_SERVER['SERVER_NAME'])[0];
        $qry = "SELECT * FROM `kbiz_subscription` WHERE `appname`='{$app}' LIMIT 1";
        return $this->kdbal()->query($qry)[0];
    }

    function get_home_page($pageid) {
        return $this->dbal()->query("SELECT * FROM web_pages WHERE `ID`='{$pageid}' LIMIT 1");
    }

    function get_menu($type = 1) {
        return $this->dbal()->query("SELECT * FROM `web_menus` WHERE `menu_type`={$type} AND `deleted`=0");
    }

}
