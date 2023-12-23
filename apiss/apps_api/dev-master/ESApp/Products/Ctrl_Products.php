<?php

use kring\core\Controller;

class Products extends Controller
{

    public $adminarea;

    function __construct()
    {
        parent::__construct();
        $this->adminarea = 1;
    }

    function model()
    {
        return $this->loadESmodel('products', 'Products');
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
            $ress = $this->model()->productsnew__record_create();
            if ($ress == 1) {
                $data['status'] = "success";
                $data['msg'] = "Post Created Success";
            } else {
                $data['status'] = "error";
                $data['msg'] = $ress;
            }
        } elseif ($this->model()->comm()->rqstr('sopt') == 'CheckValid') {
            $ress = $this->model()->products_CheckValid();
            if ($ress == 1) {
                $data['status'] = "success";
                $data['msg'] = "Valid";
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
        return $this->model()->get_productsEditData()[0];
    }

    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ress = $this->model()->productsedited_data_save();
            if ($ress == 1) {
                $data['status'] = "success";
                $data['msg'] = "Post Created Success";
            } else {
                $data['status'] = "error";
                $data['msg'] = $ress;
            }
        } elseif ($this->model()->comm()->rqstr('sopt') == 'CheckValid') {
            $ress = $this->model()->products_CheckValid();
            $data['status'] = "init";
            $data['msg'] = $ress;
        } else {
            $data['status'] = "error";
            $data['msg'] = "No Data Posted";
        }
        return $data;
    }

    function view($pr)
    {
        $data['ID'] = "success";
        $data['data'] = $this->model()->productsViewdata($pr[2]);
        return $data;
    }
    function data_for_select()
    {
        return $this->model()->get_for_select();
    }
    function delete()
    {
        $msg = $this->model()->productsDeleteSql();
        if ($msg == true) {
            $data['status'] = "success";
            $data['msg'] = "products Record Deleted!";
        } else {
            $data['status'] = "error";
            $data['msg'] = "products Record Delete Failed!";
        }
    }

    function products_restore()
    {

        $msg = $this->model()->productsRestoreSql();
        if ($msg == true) {
            $data['status'] = "success";
            $data['msg'] = "products Record Restored!";
        } else {
            $data['status'] = "error";
            $data['msg'] = "products Record Restore Failed!";
        }
    }

    function categorydataset()
    {
        return $this->model()->get_categorydataset();
    }

    function supplier_ID_options()
    {
        return $this->model()->get_supplier_ID_options_data();
    }

    function unit_name_options()
    {
        return $this->model()->get_unit_name_options_data();
    }
    function images()
    {
        return $this->model()->image_links();
    }
    function upload()
    {
        return $this->model()->uploadFile();
    }
    function setmainimage()
    {
        return $this->model()->setmainimage();
    }
    function inventory_history()
    {
        return $this->model()->inventory_history();
    }
    function suppliers()
    {
        return $this->model()->get_supplier();
    }
}
