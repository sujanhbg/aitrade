<?php

use kring\auth\jwt;
use kring\core\Controller;

class Registration extends Controller
{

    public $adminarea;
    private $jwt;
    public function __construct()
    {
        parent::__construct();
        $this->adminarea = 0;
        $this->jwt       = new jwt();
    }

    public function model()
    {
        return $this->loadESmodel('register', 'Registration');
    }

    public function index()
    {
        $data['msg']    = "AIFX Rest API V.01";
        $data['Developer'] = "<----CBS----> ";
        //$data['database'] = $this->model()->dbal()->info();
        return $data;
    }
    function register_new()
    {

        $rdt = $this->model()->usernew__record_create();
        if ($rdt == 1) {
            $data['status'] = 'success';
            $data['msg'] = '';
        } else {
            $data['status'] = 'error';
            $data['msg'] = $rdt;
        }


        return $data;
    }
    function register_new_phone()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $rdt = $this->model()->usernew__record_create_phone();
            if ($rdt == 1) {
                $data['status'] = 'success';
                $data['msg'] = '';
            } else {
                $data['status'] = 'error';
                $data['msg'] = $rdt;
            }
        } else {
            $data['status'] = 'error';
            $data['msg'] = 'Data not found';
        }

        return $data;
    }

    function verify()
    {
        $verify = $this->model()->verify();
        if ($verify == 1) {
            $data['status'] = 'success';
            $data['msg'] = '';
        } else {
            $data['status'] = 'error';
            $data['msg'] = $verify;
        }
        return $data;
    }
}
