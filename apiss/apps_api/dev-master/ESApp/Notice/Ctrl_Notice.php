<?php

use kring\core\Controller;
                
class Notice extends Controller {

    public $adminarea;

    function __construct() {
        parent::__construct();
        $this->adminarea = 1;

    }

    function model(){
        return $this->loadESmodel('notice','Notice');
    }

function index() {
    return $this->model()->datasource();
    }

function datasource() {
        return $this->model()->datasource();
    }
                    
    function new() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ress = $this->model()->noticenew__record_create();
            if ($ress == 1 ) {
                $data['status'] = "success";
                $data['msg'] = "Created New notice Record Success!";
            } else {
                $data['status'] = "error";
                $data['msg'] = $ress;
            }
        } elseif ($this->model()->comm()->rqstr('sopt') == 'CheckValid') {
            $ress = $this->model()->notice_CheckValid();
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
    return $this->model()->get_noticeEditData()[0];
    }

function edit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ress = $this->model()->noticeedited_data_save();
            if ($ress == 1) {
                $data['status'] = "success";
                $data['msg'] = "Successfully Saved!";
            } else {
                $data['status'] = "error";
                $data['msg'] = $ress;
            }
        } elseif ($this->model()->comm()->rqstr('sopt') == 'CheckValid') {
            $ress = $this->model()->notice_CheckValid();
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
        $data['data']= $this->model()->noticeViewdata($pr[2]);
        return $data;
       
    }
function data_for_select() {
        return $this->model()->get_for_select();
    }
function delete() {
        $msg=$this->model()->noticeDeleteSql();
        if($msg==true){
            $data['status']="success";
            $data['msg']="notice Record Deleted!";
        }else{
            $data['status']="error";
            $data['msg']="notice Record Delete Failed!";
        }
    }

    function notice_restore() {
        
           $msg= $this->model()->noticeRestoreSql();
           if($msg==true){
            $data['status']="success";
            $data['msg']="notice Record Restored!";
        }else{
            $data['status']="error";
            $data['msg']="notice Record Restore Failed!";
        }
            
        
    }
}



?>