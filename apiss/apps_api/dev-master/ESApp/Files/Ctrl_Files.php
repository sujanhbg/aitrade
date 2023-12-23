<?php

use kring\core\Controller;

class Files extends Controller
{

    public $adminarea;

    function __construct()
    {
        parent::__construct();
        $this->adminarea = 1;
    }

    function model()
    {
        return $this->loadESmodel('files', 'Files');
    }

    function index()
    {
        return $this->model()->datasource();
    }

    function datasource()
    {
        return $this->model()->datasource();
    }
    function upload()
    {
        $perentID = $this->comm()->rqstr('folderid');
        $currentfolderid = $perentID ? $perentID : 1;
        return $this->model()->uploadFile($currentfolderid);
    }
    function renameFolder()
    {
        $updated = $this->model()->rename_folder($_POST['folderid']);
        if ($updated) {
            $data['status'] = "success";
            $data['msg'] = "Update Success";
        } else {
            $data['status'] = "error";
            $data['msg'] = $updated;
        }
        return $data;
    }
}
