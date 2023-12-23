<?php

use kring\core\Controller;

class Customers extends Controller
{

    public $adminarea;

    function __construct()
    {
        parent::__construct();
        $this->adminarea = 1;
    }

    function model()
    {
        return $this->loadESmodel('customers', 'Customers');
    }

    function index()
    {
        return $this->model()->datasource();
    }

    function datasource()
    {
        return $this->model()->datasource();
    }

    function new()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ress = $this->model()->customersnew__record_create();
            if ($ress[0] == 1) {
                $data['status'] = "success";
                $data['msg'] = "Created New customers Record Success!";
                $data['customerID'] = $ress[1];
            } else {
                $data['status'] = "error";
                $data['msg'] = $ress;
            }
        } elseif ($this->model()->comm()->rqstr('sopt') == 'CheckValid') {
            $ress = $this->model()->customers_CheckValid();
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

    function editdata()
    {
        return $this->model()->get_customersEditData()[0];
    }

    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ress = $this->model()->customersedited_data_save();
            if ($ress == 1) {
                $data['status'] = "success";
                $data['msg'] = "Successfully Saved!";
            } else {
                $data['status'] = "error";
                $data['msg'] = $ress;
            }
        } elseif ($this->model()->comm()->rqstr('sopt') == 'CheckValid') {
            $ress = $this->model()->customers_CheckValid();
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

    function view($pr)
    {
        $data['ID'] = "success";
        $data['data'] = $this->model()->customersViewdata($pr[2]);
        return $data;
    }
    function data_for_select()
    {
        return $this->model()->get_for_select();
    }
    function delete()
    {
        $msg = $this->model()->customersDeleteSql();
        if ($msg == true) {
            $data['status'] = "success";
            $data['msg'] = "customers Record Deleted!";
        } else {
            $data['status'] = "error";
            $data['msg'] = "customers Record Delete Failed!";
        }
    }

    function customers_restore()
    {

        $msg = $this->model()->customersRestoreSql();
        if ($msg == true) {
            $data['status'] = "success";
            $data['msg'] = "customers Record Restored!";
        } else {
            $data['status'] = "error";
            $data['msg'] = "customers Record Restore Failed!";
        }
    }
    function country_options()
    {
        return $this->model()->get_country_options_data();
    }
}
