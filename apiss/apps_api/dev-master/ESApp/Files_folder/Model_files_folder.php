<?php

use kring\database as db;
use kring\utilities\comm;

class Model_files_folder
{

    function __construct()
    {
    }
    function comm()
    {
        return new comm();
    }

    function dbal()
    {
        return new db\dbal();
    }
    function kring()
    {
        return new \kring\core\kring();
    }
    function baseurl()
    {
        return $this->kring()->conf('baseurl');
    }
    //return My sql real escape string
    function escStr($str)
    {
        return $this->dbal()->conn()->real_escape_string($str);
    }

    function getfiles_folderHeader()
    {
        return ['ID', 'folder_name', 'sub_id', 'icon'];
    }


    function files_folderViewdata()
    {
        return $this->dbal()->query("SELECT 
				`ID`,
				`folder_name`,
				`sub_id`,
				`icon`
                                FROM files_folder
                                WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }




    function files_folderValidationRules()
    {
        return [
            'folder_name'  =>  'required|min_len,1',
            'icon'  =>  'required|min_len,1'
        ];
    }




    function files_folderValidationMessage()
    {
        return [
            'ID' => ['required' => 'ID  is required.', 'min_len' => 'Invalid ID'],
            'folder_name' => ['required' => 'Folder Name  is required.', 'min_len' => 'Invalid folder_name'],
            'sub_id' => ['required' => 'Sub Id  is required.', 'min_len' => 'Invalid sub_id'],
            'icon' => ['required' => 'Icon  is required.', 'min_len' => 'Invalid icon']
        ];
    }


    function files_folderFilterRules()
    {
        return [
            'ID'  =>  'trim|sanitize_string|basic_tags',
            'folder_name'  =>  'trim|sanitize_string|basic_tags',
            'sub_id'  =>  'trim|sanitize_string|basic_tags',
            'icon'  =>  'trim|sanitize_string|basic_tags'
        ];
    }
    function get_all_folder()
    {
        return $this->dbal()->querydt("SELECT * FROM files_folder WHERE sub_id=0 AND deleted=0 ORDER BY ID DESC");
    }
    function get_sub_folder($perentID)
    {
        return $this->dbal()->querydt("SELECT * FROM files_folder WHERE sub_id={$perentID} AND deleted=0 ORDER BY ID DESC");
    }
    function get_all_files($folderid = 1)
    {
        return $this->dbal()->querydt("SELECT * FROM files WHERE folder_id={$folderid} AND deleted=0 ORDER BY ID DESC");
    }
    function datasource()
    {
        $pd = $_POST;
        $data['draw'] = isset($pd['draw']) ? $pd['draw'] : 1;
        $start = isset($pd['start']) ? $pd['start'] : 0;
        $length = isset($pd['length']) ? $pd['length'] : 10;
        $table = 'files_folder';
        $selectdata = ['ID', 'folder_name', 'sub_id', 'icon'];
        $orderby = dtv::orderby($selectdata, $table);
        $search = dt::dt_search($selectdata, "deleted=0", $table);
        $fields = dt::fields($selectdata, $table);
        $sql = "SELECT {$fields} FROM files_folder WHERE {$search} {$orderby}";
        $limit = " LIMIT {$start},{$length}";
        $data['recordsTotal'] = $this->dbal()->num_of_row($sql);
        $data['recordsFiltered'] = $this->dbal()->num_of_row($sql);
        $data['data'] = $this->dbal()->querydt($sql . $limit);
        return json_encode($data);
    }

    function files_folder_dbvalid($data)
    {
        $cond = "SELECT ID FROM files_folder WHERE ";
        foreach ($data as $serv => $sdata) {
            $cond .= " " . $serv . "='" . $sdata . "' OR";
        }
        $condi = trim($cond, "OR");
        if ($this->dbal()->num_of_row($condi) > 0) {
            return false;
        } else {
            return true;
        }
    }



    function files_folder_CheckValid()
    {

        $gump = new \GUMP();
        $gump->set_fields_error_messages($this->files_folderValidationMessage());
        $data = array($_REQUEST['fname'] => $_REQUEST['fval']);
        $validated = $gump->is_valid($data, array_intersect_key($this->files_folderValidationRules(), array_flip(array($_REQUEST['fname']))));
        $dbvalid = $this->files_folder_dbvalid([$_REQUEST['fname'] => $_REQUEST['fval']]);

        if ($validated === true) {
            if ($_REQUEST['fname'] == "email" && $dbvalid == false) {
                $return = "<span style='color:red'><i class='fa fa-times' aria-hidden='true'></i>"
                    . " {$_REQUEST['fval']} already exists</span>";
            } else {
                $return = "<span style='color:green'><i class='fa fa-check-square' aria-hidden='true'></i>"
                    . " Valid!</span>";
            }
        } else {

            $return = "<span style='color:red'><i class='fa fa-times' aria-hidden='true'></i> ";
            $return .= $validated[0] . "</span>";
        }
        echo $return;
    }





    function files_foldernew__record_create()
    {
        $gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->files_folderValidationRules());
        $gump->filter_rules($this->files_folderFilterRules());
        $gump->set_fields_error_messages($this->files_folderValidationMessage());
        $validated_data = $gump->run($_POST);
        $dbvalidation = null;
        //if($this->check_exits("students", "title={$validated_data['title']}")){$dbvalidation.="Data Already Exits";}
        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            if ($dbvalidation == null) {
                //$return= $validated_data['cellnumber'];
                $insertsql = "INSERT INTO  `files_folder` (
            `folder_name`,
`sub_id`,
`icon`)
            VALUES
            ('{$this->escStr($validated_data['folder_name'])}',
'{$this->escStr($validated_data['sub_id'])}',
'{$this->escStr($validated_data['icon'])}');";

                if ($this->dbal()->query_exc($insertsql)) {
                    $return = 1;
                    /*
						$this->dbal()->editLog("files_folder", "folder_name", $this->escStr($validated_data['folder_name']));
$this->dbal()->editLog("files_folder", "sub_id", $this->escStr($validated_data['sub_id']));
$this->dbal()->editLog("files_folder", "icon", $this->escStr($validated_data['icon']));

						*/
                } else {
                    $return = "<span class=\"validerror\">"
                        . "We are Sorry; We can not record your Input to our Database Server</span>";
                }
            } else {
                $return = "<span class=\"validerror\">$dbvalidation</span>";
            }
        }
        return $return;
    }





    function get_files_folderEditData()
    {
        return $this->dbal()->query("SELECT * FROM files_folder WHERE `ID`='{$this->comm()->rqstr('ID')}' LIMIT 1");
    }




    function files_folderedited_data_save()
    {
        $gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->files_folderValidationRules());
        $gump->filter_rules($this->files_folderFilterRules());
        $gump->set_fields_error_messages($this->files_folderValidationMessage());
        $validated_data = $gump->run($_POST);

        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            $dbvalidation = true; //$this->files_folder_dbvalid(['email' => $validated_data['email'], 'cell' => $validated_data['cell']]);
            if ($dbvalidation == true) {
                //$return= $validated_data['cellnumber'];
                $editsql = "UPDATE  files_folder SET 
				`folder_name` =  '{$this->escStr($validated_data['folder_name'])}',
				`sub_id` =  '{$this->escStr($validated_data['sub_id'])}',
				`icon` =  '{$this->escStr($validated_data['icon'])}' WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1";

                if ($this->dbal()->update_database($editsql)) {
                    $return = 1;
                    /*
			$this->dbal()->editLog("files_folder", "folder_name", $this->escStr($validated_data['folder_name']));
$this->dbal()->editLog("files_folder", "sub_id", $this->escStr($validated_data['sub_id']));
$this->dbal()->editLog("files_folder", "icon", $this->escStr($validated_data['icon']));

			*/
                } else {
                    $return = "<span class=\"validerror\">"
                        . "We are Sorry; We can not save your update</span>";
                }
            } else {
                $return = "<span class=\"validerror\">Data Exists!</span>";
            }
        }
        return $return;
    }


    function get_for_select()
    {
        return $this->dbal()->query("SELECT * FROM `files_folder` WHERE `deleted`=0");
    }

    function files_folderDeleteSql()
    {
        return $this->dbal()->query_exc("UPDATE  files_folder SET `deleted` =  '1'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }



    function files_folderRestoreSql()
    {
        return $this->dbal()->query_exc("UPDATE  files_folder SET `deleted` =  '0'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }
}
