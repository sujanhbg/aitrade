<?php

use kring\database as db;
use kring\utilities\comm;

class Model_user
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

    function escStr($str)
    {
        return $this->dbal()->conn()->real_escape_string($str);
    }


    function userViewdata()
    {
        return $this->dbal()->query("SELECT 
				`ID`,
				`firstname`,
				`lastname`,
				`email`,
				`createdate`,
				`role`,
				`active`,
				`create_by`,
				`create_from`,
				`photo`,
				`cell`,
				`username`,
				`gender`,
				`nationality`,
				`telephone`,
				`streetaddr`,
				`city`,
				`region`,
				`country`,
				`postalcode`,
				`birthdate`,
				`deleted`,
				`cell_verified`,
				`companyname`,
				`disignation`,
				`billingaddress`
                                FROM user
                                WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }




    function userValidationRules()
    {
        return [
            'firstname'  =>  'required|min_len,1',
            'lastname'  =>  'required|min_len,1',
            'photo'  =>  'required|min_len,1',
            'cell'  =>  'required|min_len,1',
            'birthdate' => 'required|date|min_age,18',
            'postalcode' => 'required|min_len,4'
        ];
    }




    function userValidationMessage()
    {
        return [
            'ID' => ['required' => 'ID  is required.', 'min_len' => 'Invalid ID'],
            'firstname' => ['required' => 'Firstname  is required.', 'min_len' => 'Invalid firstname'],
            'lastname' => ['required' => 'Lastname  is required.', 'min_len' => 'Invalid lastname'],
            'password' => ['required' => 'Password  is required.', 'min_len' => 'Invalid password'],
            'email' => ['required' => 'Email  is required.', 'min_len' => 'Invalid email'],
            'createdate' => ['required' => 'Createdate  is required.', 'min_len' => 'Invalid createdate'],
            'role' => ['required' => 'Role  is required.', 'min_len' => 'Invalid role'],
            'active' => ['required' => 'Active  is required.', 'min_len' => 'Invalid active'],
            'create_by' => ['required' => 'Create By  is required.', 'min_len' => 'Invalid create_by'],
            'create_from' => ['required' => 'Create From  is required.', 'min_len' => 'Invalid create_from'],
            'active_code' => ['required' => 'Active Code  is required.', 'min_len' => 'Invalid active_code'],
            'photo' => ['required' => 'Photo  is required.', 'min_len' => 'Invalid photo'],
            'cell' => ['required' => 'Cell  is required.', 'min_len' => 'Invalid cell'],
            'username' => ['required' => 'Username  is required.', 'min_len' => 'Invalid username'],
            'gender' => ['required' => 'Gender  is required.', 'min_len' => 'Invalid gender'],
            'nationality' => ['required' => 'Nationality  is required.', 'min_len' => 'Invalid nationality'],
            'telephone' => ['required' => 'Telephone  is required.', 'min_len' => 'Invalid telephone'],
            'streetaddr' => ['required' => 'Streetaddr  is required.', 'min_len' => 'Invalid streetaddr'],
            'city' => ['required' => 'City  is required.', 'min_len' => 'Invalid city'],
            'region' => ['required' => 'Region  is required.', 'min_len' => 'Invalid region'],
            'country' => ['required' => 'Country  is required.', 'min_len' => 'Invalid country'],
            'postalcode' => ['required' => 'Postalcode  is required.', 'min_len' => 'Invalid postalcode'],
            'birthdate' => ['required' => 'Birthdate  is required.', 'min_len' => 'Invalid birthdate'],
            'deleted' => ['required' => 'Deleted  is required.', 'min_len' => 'Invalid deleted'],
            'cell_verified' => ['required' => 'Cell Verified  is required.', 'min_len' => 'Invalid cell_verified'],
            'cellotp' => ['required' => 'Cellotp  is required.', 'min_len' => 'Invalid cellotp'],
            'companyname' => ['required' => 'Companyname  is required.', 'min_len' => 'Invalid companyname'],
            'disignation' => ['required' => 'Disignation  is required.', 'min_len' => 'Invalid disignation'],
            'billingaddress' => ['required' => 'Billingaddress  is required.', 'min_len' => 'Invalid billingaddress'],
            'authkey' => ['required' => 'Authkey  is required.', 'min_len' => 'Invalid authkey']
        ];
    }


    function userFilterRules()
    {
        return [
            'ID'  =>  'trim|sanitize_string|basic_tags',
            'firstname'  =>  'trim|sanitize_string|basic_tags',
            'lastname'  =>  'trim|sanitize_string|basic_tags',
            'password'  =>  'trim|sanitize_string|basic_tags',
            'email'  =>  'trim|sanitize_string|basic_tags',
            'createdate'  =>  'trim|sanitize_string|basic_tags',
            'role'  =>  'trim|sanitize_string|basic_tags',
            'active'  =>  'trim|sanitize_string|basic_tags',
            'create_by'  =>  'trim|sanitize_string|basic_tags',
            'create_from'  =>  'trim|sanitize_string|basic_tags',
            'active_code'  =>  'trim|sanitize_string|basic_tags',
            'photo'  =>  'trim|sanitize_string|basic_tags',
            'cell'  =>  'trim|sanitize_string|basic_tags',
            'username'  =>  'trim|sanitize_string|basic_tags',
            'gender'  =>  'trim|sanitize_string|basic_tags',
            'nationality'  =>  'trim|sanitize_string|basic_tags',
            'telephone'  =>  'trim|sanitize_string|basic_tags',
            'streetaddr'  =>  'trim|sanitize_string|basic_tags',
            'city'  =>  'trim|sanitize_string|basic_tags',
            'region'  =>  'trim|sanitize_string|basic_tags',
            'country'  =>  'trim|sanitize_string|basic_tags',
            'postalcode'  =>  'trim|sanitize_string|basic_tags',
            'birthdate'  =>  'trim|sanitize_string|basic_tags',
            'deleted'  =>  'trim|sanitize_string|basic_tags',
            'cell_verified'  =>  'trim|sanitize_string|basic_tags',
            'cellotp'  =>  'trim|sanitize_string|basic_tags',
            'companyname'  =>  'trim|sanitize_string|basic_tags',
            'disignation'  =>  'trim|sanitize_string|basic_tags',
            'billingaddress'  =>  'trim|sanitize_string|basic_tags',
            'authkey'  =>  'trim|sanitize_string|basic_tags'
        ];
    }

    function datasource()
    {
        $pd = json_decode(file_get_contents('php://input'), true);
        $data['draw'] = isset($pd['draw']) ? $pd['draw'] : 1;
        $start = isset($pd['start']) ? $pd['start'] : 0;
        $length = isset($pd['length']) ? $pd['length'] : 10;
        $table = "user";
        $selectdata = ['ID', 'firstname', 'lastname', 'password', 'email', 'createdate', 'role', 'active', 'create_by', 'create_from', 'active_code', 'photo', 'cell', 'username', 'gender', 'nationality', 'telephone', 'streetaddr', 'city', 'region', 'country', 'postalcode', 'birthdate', 'deleted', 'cell_verified', 'cellotp', 'companyname', 'disignation', 'billingaddress', 'authkey'];
        $orderby = dtv::orderby($selectdata, $table);
        $search = dtv::dt_search($selectdata, "deleted=0", $table);
        $fields = dtv::fields($selectdata, $table);
        $sql = "SELECT {$fields} FROM user WHERE {$search} {$orderby}";
        $limit = " LIMIT {$start},{$length}";
        $data['recordsTotal'] = $this->dbal()->num_of_row($sql);
        $data['recordsFiltered'] = $this->dbal()->num_of_row($sql);
        $data['data'] = $this->dbal()->querydt($sql . $limit);
        return $data;
    }

    function user_dbvalid($data)
    {
        $cond = "SELECT ID FROM user WHERE ";
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



    function user_CheckValid()
    {

        $gump = new \GUMP();
        $gump->set_fields_error_messages($this->userValidationMessage());
        $data = array($_REQUEST['fname'] => $_REQUEST['fval']);
        $validated = $gump->is_valid($data, array_intersect_key($this->userValidationRules(), array_flip(array($_REQUEST['fname']))));
        $dbvalid = $this->user_dbvalid([$_REQUEST['fname'] => $_REQUEST['fval']]);

        if ($validated === true) {
            if ($_REQUEST['fname'] == "email" && $dbvalid == false) {
                $return = " {$_REQUEST['fval']} already exists!";
            } else {
                $return = 1;
            }
        } else {

            $return = $validated[0];
        }
        return $return;
    }





    function usernew__record_create()
    {
        $gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->userValidationRules());
        $gump->filter_rules($this->userFilterRules());
        $gump->set_fields_error_messages($this->userValidationMessage());
        $validated_data = $gump->run($_POST);
        $dbvalidation = null;
        //if($this->check_exits("students", "title={$validated_data['title']}")){$dbvalidation.="Data Already Exits";}
        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            if ($dbvalidation == null) {
                //$return= $validated_data['cellnumber'];
                $insertsql = "INSERT INTO  `user` (
            `ID`,
`firstname`,
`lastname`,
`password`,
`email`,
`createdate`,
`role`,
`active`,
`create_by`,
`create_from`,
`active_code`,
`photo`,
`cell`,
`username`,
`gender`,
`nationality`,
`telephone`,
`streetaddr`,
`city`,
`region`,
`country`,
`postalcode`,
`birthdate`,
`deleted`,
`cell_verified`,
`cellotp`,
`companyname`,
`disignation`,
`billingaddress`,
`authkey`)
            VALUES
            ('{$this->escStr($validated_data['ID'])}',
'{$this->escStr($validated_data['firstname'])}',
'{$this->escStr($validated_data['lastname'])}',
'{$this->escStr($validated_data['password'])}',
'{$this->escStr($validated_data['email'])}',
'{$this->escStr($validated_data['createdate'])}',
'{$this->escStr($validated_data['role'])}',
'{$this->escStr($validated_data['active'])}',
'{$this->escStr($validated_data['create_by'])}',
'{$this->escStr($validated_data['create_from'])}',
'{$this->escStr($validated_data['active_code'])}',
'{$this->escStr($validated_data['photo'])}',
'{$this->escStr($validated_data['cell'])}',
'{$this->escStr($validated_data['username'])}',
'{$this->escStr($validated_data['gender'])}',
'{$this->escStr($validated_data['nationality'])}',
'{$this->escStr($validated_data['telephone'])}',
'{$this->escStr($validated_data['streetaddr'])}',
'{$this->escStr($validated_data['city'])}',
'{$this->escStr($validated_data['region'])}',
'{$this->escStr($validated_data['country'])}',
'{$this->escStr($validated_data['postalcode'])}',
'{$this->escStr($validated_data['birthdate'])}',
'{$this->escStr($validated_data['deleted'])}',
'{$this->escStr($validated_data['cell_verified'])}',
'{$this->escStr($validated_data['cellotp'])}',
'{$this->escStr($validated_data['companyname'])}',
'{$this->escStr($validated_data['disignation'])}',
'{$this->escStr($validated_data['billingaddress'])}',
'{$this->escStr($validated_data['authkey'])}');";

                if ($this->dbal()->query_exc($insertsql)) {
                    $return = 1;
                    /*
						$this->dbal()->editLog("user", "ID", $this->escStr($validated_data['ID']));
$this->dbal()->editLog("user", "firstname", $this->escStr($validated_data['firstname']));
$this->dbal()->editLog("user", "lastname", $this->escStr($validated_data['lastname']));
$this->dbal()->editLog("user", "password", $this->escStr($validated_data['password']));
$this->dbal()->editLog("user", "email", $this->escStr($validated_data['email']));
$this->dbal()->editLog("user", "createdate", $this->escStr($validated_data['createdate']));
$this->dbal()->editLog("user", "role", $this->escStr($validated_data['role']));
$this->dbal()->editLog("user", "active", $this->escStr($validated_data['active']));
$this->dbal()->editLog("user", "create_by", $this->escStr($validated_data['create_by']));
$this->dbal()->editLog("user", "create_from", $this->escStr($validated_data['create_from']));
$this->dbal()->editLog("user", "active_code", $this->escStr($validated_data['active_code']));
$this->dbal()->editLog("user", "photo", $this->escStr($validated_data['photo']));
$this->dbal()->editLog("user", "cell", $this->escStr($validated_data['cell']));
$this->dbal()->editLog("user", "username", $this->escStr($validated_data['username']));
$this->dbal()->editLog("user", "gender", $this->escStr($validated_data['gender']));
$this->dbal()->editLog("user", "nationality", $this->escStr($validated_data['nationality']));
$this->dbal()->editLog("user", "telephone", $this->escStr($validated_data['telephone']));
$this->dbal()->editLog("user", "streetaddr", $this->escStr($validated_data['streetaddr']));
$this->dbal()->editLog("user", "city", $this->escStr($validated_data['city']));
$this->dbal()->editLog("user", "region", $this->escStr($validated_data['region']));
$this->dbal()->editLog("user", "country", $this->escStr($validated_data['country']));
$this->dbal()->editLog("user", "postalcode", $this->escStr($validated_data['postalcode']));
$this->dbal()->editLog("user", "birthdate", $this->escStr($validated_data['birthdate']));
$this->dbal()->editLog("user", "deleted", $this->escStr($validated_data['deleted']));
$this->dbal()->editLog("user", "cell_verified", $this->escStr($validated_data['cell_verified']));
$this->dbal()->editLog("user", "cellotp", $this->escStr($validated_data['cellotp']));
$this->dbal()->editLog("user", "companyname", $this->escStr($validated_data['companyname']));
$this->dbal()->editLog("user", "disignation", $this->escStr($validated_data['disignation']));
$this->dbal()->editLog("user", "billingaddress", $this->escStr($validated_data['billingaddress']));
$this->dbal()->editLog("user", "authkey", $this->escStr($validated_data['authkey']));

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





    function get_userEditData()
    {
        return $this->dbal()->query("SELECT * FROM user WHERE `ID`='{$this->comm()->rqstr('ID')}' LIMIT 1");
    }




    function useredited_data_save()
    {
        $gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->userValidationRules());
        $gump->filter_rules($this->userFilterRules());
        $gump->set_fields_error_messages($this->userValidationMessage());
        $validated_data = $gump->run($_POST);

        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            $dbvalidation = true; //$this->user_dbvalid(['email' => $validated_data['email'], 'cell' => $validated_data['cell']]);
            if ($dbvalidation == true) {
                //$return= $validated_data['cellnumber'];
                $postalCode = strlen($this->escStr($validated_data['postalcode'])) > 3 ? $this->escStr($validated_data['postalcode']) : 0;
                $editsql = "UPDATE  user SET 
				`firstname` =  '{$this->escStr($validated_data['firstname'])}',
				`lastname` =  '{$this->escStr($validated_data['lastname'])}',
				`photo` =  '{$this->escStr($validated_data['photo'])}',
				`cell` =  '{$this->escStr($validated_data['cell'])}',
				`gender` =  '{$this->escStr($validated_data['gender'])}',
				`nationality` =  '{$this->escStr($validated_data['nationality'])}',
				`telephone` =  '{$this->escStr($validated_data['telephone'])}',
				`streetaddr` =  '{$this->escStr($validated_data['streetaddr'])}',
				`city` =  '{$this->escStr($validated_data['city'])}',
				`region` =  '{$this->escStr($validated_data['region'])}',
				`country` =  '{$this->escStr($validated_data['country'])}',
				`postalcode` =  '{$postalCode}',
				`birthdate` =  '{$this->escStr($validated_data['birthdate'])}',
				`companyname` =  '{$this->escStr($validated_data['companyname'])}',
				`disignation` =  '{$this->escStr($validated_data['disignation'])}',
				`billingaddress` =  '{$this->escStr($validated_data['billingaddress'])}' WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1";

                if ($this->dbal()->update_database($editsql)) {
                    $return = 1;
                    /*
			$this->dbal()->editLog("user", "ID", $this->escStr($validated_data['ID']));
$this->dbal()->editLog("user", "firstname", $this->escStr($validated_data['firstname']));
$this->dbal()->editLog("user", "lastname", $this->escStr($validated_data['lastname']));
$this->dbal()->editLog("user", "password", $this->escStr($validated_data['password']));
$this->dbal()->editLog("user", "email", $this->escStr($validated_data['email']));
$this->dbal()->editLog("user", "createdate", $this->escStr($validated_data['createdate']));
$this->dbal()->editLog("user", "role", $this->escStr($validated_data['role']));
$this->dbal()->editLog("user", "active", $this->escStr($validated_data['active']));
$this->dbal()->editLog("user", "create_by", $this->escStr($validated_data['create_by']));
$this->dbal()->editLog("user", "create_from", $this->escStr($validated_data['create_from']));
$this->dbal()->editLog("user", "active_code", $this->escStr($validated_data['active_code']));
$this->dbal()->editLog("user", "photo", $this->escStr($validated_data['photo']));
$this->dbal()->editLog("user", "cell", $this->escStr($validated_data['cell']));
$this->dbal()->editLog("user", "username", $this->escStr($validated_data['username']));
$this->dbal()->editLog("user", "gender", $this->escStr($validated_data['gender']));
$this->dbal()->editLog("user", "nationality", $this->escStr($validated_data['nationality']));
$this->dbal()->editLog("user", "telephone", $this->escStr($validated_data['telephone']));
$this->dbal()->editLog("user", "streetaddr", $this->escStr($validated_data['streetaddr']));
$this->dbal()->editLog("user", "city", $this->escStr($validated_data['city']));
$this->dbal()->editLog("user", "region", $this->escStr($validated_data['region']));
$this->dbal()->editLog("user", "country", $this->escStr($validated_data['country']));
$this->dbal()->editLog("user", "postalcode", $this->escStr($validated_data['postalcode']));
$this->dbal()->editLog("user", "birthdate", $this->escStr($validated_data['birthdate']));
$this->dbal()->editLog("user", "deleted", $this->escStr($validated_data['deleted']));
$this->dbal()->editLog("user", "cell_verified", $this->escStr($validated_data['cell_verified']));
$this->dbal()->editLog("user", "cellotp", $this->escStr($validated_data['cellotp']));
$this->dbal()->editLog("user", "companyname", $this->escStr($validated_data['companyname']));
$this->dbal()->editLog("user", "disignation", $this->escStr($validated_data['disignation']));
$this->dbal()->editLog("user", "billingaddress", $this->escStr($validated_data['billingaddress']));
$this->dbal()->editLog("user", "authkey", $this->escStr($validated_data['authkey']));

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
        return $this->dbal()->query("SELECT * FROM `user` WHERE `deleted`=0");
    }

    function userDeleteSql()
    {
        return $this->dbal()->update_database("UPDATE  user SET `deleted` =  '1'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }



    function userRestoreSql()
    {
        return $this->dbal()->update_database("UPDATE  user SET `deleted` =  '0'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }

    function get_nationality_options_data()
    {
        return $this->dbal()->query('SELECT `en_short_name`,`en_short_name` FROM countries WHERE `deleted`=0');
    }

    function get_country_options_data()
    {
        return $this->dbal()->query('SELECT `en_short_name`,`en_short_name` FROM countries WHERE `deleted`=0');
    }
}
