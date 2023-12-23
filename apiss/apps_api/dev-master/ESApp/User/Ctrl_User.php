<?php

use kring\core\Controller;

class User extends Controller
{

    public $adminarea;

    function __construct()
    {
        parent::__construct();
        $this->adminarea = 1;
    }

    function model()
    {
        return $this->loadESmodel('user', 'User');
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
            $ress = $this->model()->usernew__record_create();
            if ($ress == 1) {
                $data['status'] = "success";
                $data['msg'] = "Created New user Record Success!";
            } else {
                $data['status'] = "error";
                $data['msg'] = $ress;
            }
        } elseif ($this->model()->comm()->rqstr('sopt') == 'CheckValid') {
            $ress = $this->model()->user_CheckValid();
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
        return $this->model()->get_userEditData()[0];
    }

    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ress = $this->model()->useredited_data_save();
            if ($ress == 1) {
                $data['status'] = "success";
                $data['msg'] = "Successfully Saved!";
            } else {
                $data['status'] = "error";
                $data['msg'] = $ress;
            }
        } elseif ($this->model()->comm()->rqstr('sopt') == 'CheckValid') {
            $ress = $this->model()->user_CheckValid();
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

    function view()
    {
        $data['data'] = $this->model()->userViewdata();
        return $data;
    }
    function data_for_select()
    {
        return $this->model()->get_for_select();
    }
    function delete()
    {
        $msg = $this->model()->userDeleteSql();
        if ($msg == true) {
            $data['status'] = "success";
            $data['msg'] = "user Record Deleted!";
        } else {
            $data['status'] = "error";
            $data['msg'] = "user Record Delete Failed!";
        }
    }

    function user_restore()
    {

        $msg = $this->model()->userRestoreSql();
        if ($msg == true) {
            $data['status'] = "success";
            $data['msg'] = "user Record Restored!";
        } else {
            $data['status'] = "error";
            $data['msg'] = "user Record Restore Failed!";
        }
    }

    function nationality_options()
    {
        return $this->model()->get_nationality_options_data();
    }

    function country_options()
    {
        return $this->model()->get_country_options_data();
    }
}
