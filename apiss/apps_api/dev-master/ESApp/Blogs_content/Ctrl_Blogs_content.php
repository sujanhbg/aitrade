<?php

use kring\core\Controller;

class Blogs_content extends Controller
{

    public $adminarea;

    function __construct()
    {
        parent::__construct();
        $this->adminarea = 1;
    }

    function model()
    {
        return $this->loadESmodel('blogs_content', 'Blogs_content');
    }

    function index()
    {
        $data['title'] = "All blogs_content";
        $data['app'] = get_class($this);
        if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
            $this->model()->blogs_content_source();
        } else {
            $this->tg('home/dashboard.html', $data);
        }
    }

    function datasource()
    {
        return $this->model()->datasource();
    }

    function new()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ress = $this->model()->blogs_contentnew__record_create();
            if ($ress == 1) {
                $data['status'] = "success";
                $data['msg'] = "Post Created Success";
            } else {
                $data['status'] = "error";
                $data['msg'] = $ress;
            }
            return $data;
        }
    }

    function editdata()
    {
        return $this->model()->get_blogs_contentEditData()[0];
        // return $data;
    }

    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $edt = $this->model()->blogs_contentedited_data_save();
            if ($edt == 1) {
                $data['status'] = "success";
                $data['msg'] = "Edited Data Saved Success";
            } else {
                $data['status'] = "error";
                $data['msg'] = $edt;
            }
        } else {
            $data['status'] = "error";
            $data['msg'] = "Data Not Submitted";
        }
        return $data;
    }

    function view($pr)
    {
        if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
            $data['blogs_contentdata'] = $this->model()->blogs_contentViewdata($pr[2]);
            $data['title'] = "View blogs_content " . $pr[2];
            $data['ID'] = $_GET['ID'];
            $data['app'] = get_class($this);
            $this->lv('blogs_content/view', $data);
        } elseif (isset($_GET['json']) && $_GET['json'] == "json") {
            $this->rendJson(json_encode($this->model()->blogs_contentViewdata($pr[2])));
        } else {
            $data['title'] = "Admin Dashboard";
            $this->tg('home/dashboard.html', $data);
        }
    }

    function data_for_select()
    {
        $this->rendJson(json_encode($this->model()->get_for_select()));
    }

    function delete($pr)
    {
        if ($this->model()->comm()->rqstr('confirmed') == "yes") {
            $this->model()->blogs_contentDeleteSql();
            echo "<script>blogs_contentdl();</script>";
        } else {
            echo "";
            echo <<<EOTEE
        <div class="w3-large">
            <h1>Are you Sure?</h1>
    <a href="javascript:void();" onclick="loadurl('?app=blogs_content&opt=delete&ID={$pr[4]}&confirmed=yes','mainbody');document.getElementById('id01').style.display='none';" class="w3-btn w3-red">Yes Delete</a>

        <a href="javascript:void();" onclick="document.getElementById('id01').style.display='none';" class="w3-btn w3-green">No! Go Back</a>

    </div>
EOTEE;
        }
    }

    function blogs_content_restore()
    {
        if ($this->model()->comm()->rqstr('confirmed') == "yes") {
            $this->mode()->blogs_contentRestoreSql();
            echo "<script>lr('?app=blogs_content&opt=index&fd=fd','mainbody');</script>";
        } else {
            echo "";
            echo <<<EOTEE
        <div class="w3-large w3-center">
            <h1>You are goind to restore this! </h1>
    <a href="javascript:void();" onclick="loadurl('?app=$this->appname&opt=blogs_content_restore_confirm&static_page_ID={$this->comm()->rqstr('static_page_ID')}&ID={$this->comm()->rqstr('ID')}','mainbody');document.getElementById('id01').style.display='none';" class="w3-btn w3-red">Yes Restore</a>

        <a href="javascript:void();" onclick="document.getElementById('id01').style.display='none';" class="w3-btn w3-green">No! Go Back</a>

    </div>
EOTEE;
        }
    }
}
