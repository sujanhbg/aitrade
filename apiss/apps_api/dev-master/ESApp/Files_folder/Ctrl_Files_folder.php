<?php

use kring\core\Controller;

class Files_folder extends Controller
{

    public $adminarea;

    function __construct()
    {
        parent::__construct();
        $this->adminarea = 1;
    }

    function model()
    {
        return $this->loadESmodel('files_folder', 'Files_folder');
    }

    function index()
    {

        return $this->model()->get_all_folder();
    }
    function subfolder()
    {
        $perentID = $_GET['pid'];

        return $this->model()->get_sub_folder($perentID);
    }
    function files()
    {
        $folder = isset($_GET['folder']) ? $_GET['folder'] : 1;
        return $this->model()->get_all_files($folder);
    }
    function datasource()
    {
        $this->rendJson($this->model()->datasource());
    }

    function new()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->model()->files_foldernew__record_create();
            echo $data;
        } elseif ($this->model()->comm()->rqstr('sopt') == 'CheckValid') {
            $this->model()->files_folder_CheckValid();
        } else {

            $data['title'] = "Admin Dashboard";
            $data['app'] = get_class($this);
            if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
                $data['title'] = "Edit files_folders_content";
                $this->lv('files_folder/new_files_folder', $data);
            } else {
                $this->tg('home/dashboard.html', $data);
            }
        }
    }

    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->model()->files_folderedited_data_save();
            echo $data;
        } elseif ($this->model()->comm()->rqstr('sopt') == 'CheckValid') {
            $this->model()->files_folder_CheckValid();
        } else {
            $data['title'] = "Admin Dashboard";
            $data['app'] = get_class($this);
            if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
                $data['title'] = "Edit files_folders_content";
                $data['files_folderEditData'] = $this->model()->get_files_folderEditData();
                $this->lv('files_folder/edit_files_folder', $data);
            } else {
                $this->tg('home/dashboard.html', $data);
            }
        }
    }
}
