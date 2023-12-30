<?php

use kring\database\dbal;
use kring\utilities\comm;
use kring\database\kdbal;
use kring\auth\jwt;

class Model_home
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

    function kdb()
    {
        return new kdbal();
    }

    function app()
    {
        return isset($_GET['host']) ? $_GET['host'] : explode(".", $_SERVER['SERVER_NAME'])[0];;
    }
    function get_balance()
    {
        $userid = $this->jwt->get_uid()['uid'];
        return $this->dbal()->get_single_result("SELECT SUM(`amount`) FROM user_transection WHERE `userid`={$userid}");
    }
}
