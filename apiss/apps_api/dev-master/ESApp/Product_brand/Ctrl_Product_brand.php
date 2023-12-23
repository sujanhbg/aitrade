<?php

use kring\core\Controller;
                
class Product_brand extends Controller {

    public $adminarea;

    function __construct() {
        parent::__construct();
        $this->adminarea = 1;

    }

    function model(){
        return $this->loadESmodel('product_brand','Product_brand');
    }

function index() {
    return $this->model()->datasource();
    }

function datasource() {
        return $this->model()->datasource();
    }
                    
    function new() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ress = $this->model()->product_brandnew__record_create();
            if ($ress == 1 ) {
                $data['status'] = "success";
                $data['msg'] = "Created New product_brand Record Success!";
            } else {
                $data['status'] = "error";
                $data['msg'] = $ress;
            }
        } elseif ($this->model()->comm()->rqstr('sopt') == 'CheckValid') {
            $ress = $this->model()->product_brand_CheckValid();
            if ($ress == 1  && strlen($_REQUEST['fval']) > 0) {
                $data['status'] = "success";
                $data['msg'] = "Valid!";
            }elseif ($ress == 1 && strlen($_REQUEST['fval']) == 0) {
                $data['status'] = "init";
                $data['msg'] = "Optional, Not Required";
            }  else {
                $data['status'] = "error";
                $data['msg'] = $ress;
            }
        } else {
            $data['status'] = "error";
            $data['msg'] = "No Data Posted";
        }
        return $data;
    }

function editdata(){
    return $this->model()->get_product_brandEditData()[0];
    }

function edit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ress = $this->model()->product_brandedited_data_save();
            if ($ress == 1) {
                $data['status'] = "success";
                $data['msg'] = "Successfully Saved!";
            } else {
                $data['status'] = "error";
                $data['msg'] = $ress;
            }
        } elseif ($this->model()->comm()->rqstr('sopt') == 'CheckValid') {
            $ress = $this->model()->product_brand_CheckValid();
            if ($ress == 1  && strlen($_REQUEST['fval']) > 0) {
                $data['status'] = "success";
                $data['msg'] = "Valid!";
            }elseif ($ress == 1 && strlen($_REQUEST['fval']) == 0) {
                $data['status'] = "init";
                $data['msg'] = "Optional, Not Required";
            }  else {
                $data['status'] = "error";
                $data['msg'] = $ress;
            }
        } else {
            $data['status'] = "error";
            $data['msg'] = "No Data Posted";
        }
        return $data;
    }

    function view($pr){
        $data['ID'] = "success";
        $data['data']= $this->model()->product_brandViewdata($pr[2]);
        return $data;
       
    }
function data_for_select() {
        return $this->model()->get_for_select();
    }
function delete() {
        $msg=$this->model()->product_brandDeleteSql();
        if($msg==true){
            $data['status']="success";
            $data['msg']="product_brand Record Deleted!";
        }else{
            $data['status']="error";
            $data['msg']="product_brand Record Delete Failed!";
        }
    }

    function product_brand_restore() {
        
           $msg= $this->model()->product_brandRestoreSql();
           if($msg==true){
            $data['status']="success";
            $data['msg']="product_brand Record Restored!";
        }else{
            $data['status']="error";
            $data['msg']="product_brand Record Restore Failed!";
        }
            
        
    }
}



?>