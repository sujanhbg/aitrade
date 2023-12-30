<?php

use kring\auth\jwt;
use kring\core\Controller;

class Home extends Controller
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
        return $this->loadESmodel('home', 'Home');
    }

    public function index()
    {
        $data['pretty'] = true;
        $data['msg']    = "AIFX Rest API V.01";
        $data['Developer'] = "<----CBS----> ";
        //$data['database'] = $this->model()->dbal()->info();
        return $data;
    }
    function menu()
    {
        $data['status'] = "success";
        $data['data'] = $this->model()->get_leftmenu_items();
        return $data;
    }
    function companyprofile()
    {
        $data['status'] = "success";
        $data['data'] = $this->model()->get_kring_data();
        return $data;
    }
    function onofline()
    {
        $data['status'] = "success";
        $data['data'] = $this->model()->kdb()->kbiz_data();
        return $data;
    }
    //this is kbiz_subscription edit option
    function editdata()
    {
        return $this->model()->get_kbiz_subscriptionEditData()[0];
    }

    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ress = $this->model()->kbiz_subscriptionedited_data_save();
            if ($ress == 1) {
                $data['status'] = "success";
                $data['msg'] = "Successfully Saved!";
            } else {
                $data['status'] = "error";
                $data['msg'] = $ress;
            }
        } elseif ($this->model()->comm()->rqstr('sopt') == 'CheckValid') {
            $ress = $this->model()->kbiz_subscription_CheckValid();
            if ($ress == 1  && strlen($_REQUEST['fval']) > 0) {
                $data['status'] = "success";
                $data['msg'] = "Valid!";
            } elseif ($ress == 1 && strlen($_REQUEST['fval']) == 0) {
                $data['status'] = "init";
                $data['msg'] = "Optional, Not Required";
            } else {
                $data['status'] = "error";
                $data['msg'] = $ress;
            }
        } else {
            $data['status'] = "error";
            $data['msg'] = "No Data Posted";
        }
        return $data;
    }
    function userdata()
    {
        $data['balance'] = $this->model()->get_balance();
        return $data;
    }
}
