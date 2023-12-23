<?php

use kring\database as db;
use kring\utilities\comm;

class Model_suppliers
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


    function suppliersViewdata()
    {
        return $this->dbal()->query("SELECT 
				`ID`,
				`supplier_name`,
				`company_name`,
				`mobile_number`,
				`email_address`,
				`address`,
				`address2`,
				`fax`,
				`city`,
				`country`,
				`zip`
                                FROM suppliers
                                WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }




    function suppliersValidationRules()
    {
        return [
            'ID'  =>  'required|min_len,1',
            'supplier_name'  =>  'required|min_len,1',
            'company_name'  =>  'required|min_len,1',
            'mobile_number'  =>  'required|min_len,1',
            'email_address'  =>  'required|min_len,1',
            'city'  =>  'required|min_len,1',
            'zip'  =>  'required|min_len,1'
        ];
    }




    function suppliersValidationMessage()
    {
        return [
            'ID' => ['required' => 'ID  is required.', 'min_len' => 'Invalid ID'],
            'supplier_name' => ['required' => 'Supplier Name  is required.', 'min_len' => 'Invalid supplier_name'],
            'company_name' => ['required' => 'Company Name  is required.', 'min_len' => 'Invalid company_name'],
            'mobile_number' => ['required' => 'Mobile Number  is required.', 'min_len' => 'Invalid mobile_number'],
            'email_address' => ['required' => 'Email Address  is required.', 'min_len' => 'Invalid email_address'],
            'city' => ['required' => 'City  is required.', 'min_len' => 'Invalid city'],
            'zip' => ['required' => 'Zip  is required.', 'min_len' => 'Invalid zip']
        ];
    }


    function suppliersFilterRules()
    {
        return [
            'ID'  =>  'trim|sanitize_string|basic_tags',
            'supplier_name'  =>  'trim|sanitize_string|basic_tags',
            'company_name'  =>  'trim|sanitize_string|basic_tags',
            'mobile_number'  =>  'trim|sanitize_string|basic_tags',
            'email_address'  =>  'trim|sanitize_string|basic_tags',
            'city'  =>  'trim|sanitize_string|basic_tags',
            'zip'  =>  'trim|sanitize_string|basic_tags'
        ];
    }

    function datasource()
    {
        $pd = json_decode(file_get_contents('php://input'), true);
        $data['draw'] = isset($pd['draw']) ? $pd['draw'] : 1;
        $start = isset($pd['start']) ? $pd['start'] : 0;
        $length = isset($pd['length']) ? $pd['length'] : 10;
        $table = "suppliers";
        $selectdata = ['ID', 'supplier_name', 'company_name', 'mobile_number', 'email_address', 'city', 'zip'];
        $orderby = dtv::orderby($selectdata, $table);
        $search = dtv::dt_search($selectdata, "deleted=0", $table);
        $fields = dtv::fields($selectdata, $table);
        $sql = "SELECT {$fields} FROM suppliers WHERE {$search} {$orderby}";
        $limit = " LIMIT {$start},{$length}";
        $data['recordsTotal'] = $this->dbal()->num_of_row($sql);
        $data['recordsFiltered'] = $this->dbal()->num_of_row($sql);
        $data['data'] = $this->dbal()->querydt($sql . $limit);
        return $data;
    }

    function suppliers_dbvalid($data)
    {
        $cond = "SELECT ID FROM suppliers WHERE ";
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



    function suppliers_CheckValid()
    {

        $gump = new \GUMP();
        $gump->set_fields_error_messages($this->suppliersValidationMessage());
        $data = array($_REQUEST['fname'] => $_REQUEST['fval']);
        $validated = $gump->is_valid($data, array_intersect_key($this->suppliersValidationRules(), array_flip(array($_REQUEST['fname']))));
        $dbvalid = $this->suppliers_dbvalid([$_REQUEST['fname'] => $_REQUEST['fval']]);

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





    function suppliersnew__record_create()
    {
        $gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->suppliersValidationRules());
        $gump->filter_rules($this->suppliersFilterRules());
        $gump->set_fields_error_messages($this->suppliersValidationMessage());
        $validated_data = $gump->run($_POST);
        $dbvalidation = null;
        //if($this->check_exits("students", "title={$validated_data['title']}")){$dbvalidation.="Data Already Exits";}
        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            if ($dbvalidation == null) {
                //$return= $validated_data['cellnumber'];
                $insertsql = "INSERT INTO  `suppliers` (
            `ID`,
`account_code`,
`supplier_name`,
`company_name`,
`mobile_number`,
`email_address`,
`address`,
`address2`,
`fax`,
`city`,
`country`,
`zip`,
`deleted`)
            VALUES
            ('{$this->escStr($validated_data['ID'])}',
'{$this->escStr($validated_data['account_code'])}',
'{$this->escStr($validated_data['supplier_name'])}',
'{$this->escStr($validated_data['company_name'])}',
'{$this->escStr($validated_data['mobile_number'])}',
'{$this->escStr($validated_data['email_address'])}',
'{$this->escStr($validated_data['address'])}',
'{$this->escStr($validated_data['address2'])}',
'{$this->escStr($validated_data['fax'])}',
'{$this->escStr($validated_data['city'])}',
'{$this->escStr($validated_data['country'])}',
'{$this->escStr($validated_data['zip'])}',
'{$this->escStr($validated_data['deleted'])}');";

                if ($this->dbal()->query_exc($insertsql)) {
                    $return = 1;
                    /*
						$this->dbal()->editLog("suppliers", "ID", $this->escStr($validated_data['ID']));
$this->dbal()->editLog("suppliers", "account_code", $this->escStr($validated_data['account_code']));
$this->dbal()->editLog("suppliers", "supplier_name", $this->escStr($validated_data['supplier_name']));
$this->dbal()->editLog("suppliers", "company_name", $this->escStr($validated_data['company_name']));
$this->dbal()->editLog("suppliers", "mobile_number", $this->escStr($validated_data['mobile_number']));
$this->dbal()->editLog("suppliers", "email_address", $this->escStr($validated_data['email_address']));
$this->dbal()->editLog("suppliers", "address", $this->escStr($validated_data['address']));
$this->dbal()->editLog("suppliers", "address2", $this->escStr($validated_data['address2']));
$this->dbal()->editLog("suppliers", "fax", $this->escStr($validated_data['fax']));
$this->dbal()->editLog("suppliers", "city", $this->escStr($validated_data['city']));
$this->dbal()->editLog("suppliers", "country", $this->escStr($validated_data['country']));
$this->dbal()->editLog("suppliers", "zip", $this->escStr($validated_data['zip']));
$this->dbal()->editLog("suppliers", "deleted", $this->escStr($validated_data['deleted']));

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





    function get_suppliersEditData()
    {
        return $this->dbal()->query("SELECT * FROM suppliers WHERE `ID`='{$this->comm()->rqstr('ID')}' LIMIT 1");
    }




    function suppliersedited_data_save()
    {
        $gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->suppliersValidationRules());
        $gump->filter_rules($this->suppliersFilterRules());
        $gump->set_fields_error_messages($this->suppliersValidationMessage());
        $validated_data = $gump->run($_POST);

        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            $dbvalidation = true; //$this->suppliers_dbvalid(['email' => $validated_data['email'], 'cell' => $validated_data['cell']]);
            if ($dbvalidation == true) {
                //$return= $validated_data['cellnumber'];
                $editsql = "UPDATE  suppliers SET 
				`ID` =  '{$this->escStr($validated_data['ID'])}',
				`account_code` =  '{$this->escStr($validated_data['account_code'])}',
				`supplier_name` =  '{$this->escStr($validated_data['supplier_name'])}',
				`company_name` =  '{$this->escStr($validated_data['company_name'])}',
				`mobile_number` =  '{$this->escStr($validated_data['mobile_number'])}',
				`email_address` =  '{$this->escStr($validated_data['email_address'])}',
				`address` =  '{$this->escStr($validated_data['address'])}',
				`address2` =  '{$this->escStr($validated_data['address2'])}',
				`fax` =  '{$this->escStr($validated_data['fax'])}',
				`city` =  '{$this->escStr($validated_data['city'])}',
				`country` =  '{$this->escStr($validated_data['country'])}',
				`zip` =  '{$this->escStr($validated_data['zip'])}',
				`deleted` =  '{$this->escStr($validated_data['deleted'])}' WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1";

                if ($this->dbal()->update_database($editsql)) {
                    $return = 1;
                    /*
			$this->dbal()->editLog("suppliers", "ID", $this->escStr($validated_data['ID']));
$this->dbal()->editLog("suppliers", "account_code", $this->escStr($validated_data['account_code']));
$this->dbal()->editLog("suppliers", "supplier_name", $this->escStr($validated_data['supplier_name']));
$this->dbal()->editLog("suppliers", "company_name", $this->escStr($validated_data['company_name']));
$this->dbal()->editLog("suppliers", "mobile_number", $this->escStr($validated_data['mobile_number']));
$this->dbal()->editLog("suppliers", "email_address", $this->escStr($validated_data['email_address']));
$this->dbal()->editLog("suppliers", "address", $this->escStr($validated_data['address']));
$this->dbal()->editLog("suppliers", "address2", $this->escStr($validated_data['address2']));
$this->dbal()->editLog("suppliers", "fax", $this->escStr($validated_data['fax']));
$this->dbal()->editLog("suppliers", "city", $this->escStr($validated_data['city']));
$this->dbal()->editLog("suppliers", "country", $this->escStr($validated_data['country']));
$this->dbal()->editLog("suppliers", "zip", $this->escStr($validated_data['zip']));
$this->dbal()->editLog("suppliers", "deleted", $this->escStr($validated_data['deleted']));

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
        return $this->dbal()->query("SELECT * FROM `suppliers` WHERE `deleted`=0");
    }

    function suppliersDeleteSql()
    {
        return $this->dbal()->update_database("UPDATE  suppliers SET `deleted` =  '1'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }



    function suppliersRestoreSql()
    {
        return $this->dbal()->update_database("UPDATE  suppliers SET `deleted` =  '0'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }
}
