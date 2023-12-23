<?php

use kring\core\Controller;
                
class Erp_return extends Controller {

    public $adminarea;

    function __construct() {
        parent::__construct();
        $this->adminarea = 1;

    }

    function model(){
        return $this->loadESmodel('erp_return','Erp_return');
    }

function index() {
    return $this->model()->datasource();
    }

function datasource() {
        return $this->model()->datasource();
    }
                    
    function new() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ress = $this->model()->erp_returnnew__record_create();
            if ($ress == 1 ) {
                $data['status'] = "success";
                $data['msg'] = "Created New erp_return Record Success!";
            } else {
                $data['status'] = "error";
                $data['msg'] = $ress;
            }
        } elseif ($this->model()->comm()->rqstr('sopt') == 'CheckValid') {
            $ress = $this->model()->erp_return_CheckValid();
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
    return $this->model()->get_erp_returnEditData()[0];
    }

function edit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ress = $this->model()->erp_returnedited_data_save();
            if ($ress == 1) {
                $data['status'] = "success";
                $data['msg'] = "Successfully Saved!";
            } else {
                $data['status'] = "error";
                $data['msg'] = $ress;
            }
        } elseif ($this->model()->comm()->rqstr('sopt') == 'CheckValid') {
            $ress = $this->model()->erp_return_CheckValid();
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
        $data['data']= $this->model()->erp_returnViewdata($pr[2]);
        return $data;
       
    }
function data_for_select() {
        return $this->model()->get_for_select();
    }
function delete() {
        $msg=$this->model()->erp_returnDeleteSql();
        if($msg==true){
            $data['status']="success";
            $data['msg']="erp_return Record Deleted!";
        }else{
            $data['status']="error";
            $data['msg']="erp_return Record Delete Failed!";
        }
    }

    function erp_return_restore() {
        
           $msg= $this->model()->erp_returnRestoreSql();
           if($msg==true){
            $data['status']="success";
            $data['msg']="erp_return Record Restored!";
        }else{
            $data['status']="error";
            $data['msg']="erp_return Record Restore Failed!";
        }
            
        
    }
}



?>