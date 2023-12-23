<?php

use kring\database as db;
use kring\utilities\comm;

class Model_customers
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


    function customersViewdata()
    {
        return $this->dbal()->query("SELECT 
				`ID`,
				`customer_name`,
				`contact_number`,
				`email_address`,
				`company_name`,
				`contact_address`,
				`phone_number`,
				`fax_number`,
				`city`,
				`country`,
				`post_code`,
				`VAT_number`,
				`whatsapp_number`,
				`is_app_access`
                                FROM customers
                                WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }




    function customersValidationRules()
    {
        return [
            'customer_name'  =>  'required|min_len,1',
            'contact_number'  =>  'required|min_len,1'
        ];
    }




    function customersValidationMessage()
    {
        return [
            'ID' => ['required' => 'ID  is required.', 'min_len' => 'Invalid ID'],
            'customer_name' => ['required' => 'Customer Name  is required.', 'min_len' => 'Invalid customer_name'],
            'contact_number' => ['required' => 'Contact Number  is required.', 'min_len' => 'Invalid contact_number'],
            'email_address' => ['required' => 'Email Address  is required.', 'min_len' => 'Invalid email_address'],
            'contact_address' => ['required' => 'Contact Address  is required.', 'min_len' => 'Invalid contact_address'],
            'phone_number' => ['required' => 'Phone Number  is required.', 'min_len' => 'Invalid phone_number']
        ];
    }


    function customersFilterRules()
    {
        return [
            'ID'  =>  'trim|sanitize_string|basic_tags',
            'customer_name'  =>  'trim|sanitize_string|basic_tags',
            'contact_number'  =>  'trim|sanitize_string|basic_tags',
            'email_address'  =>  'trim|sanitize_string|basic_tags',
            'contact_address'  =>  'trim|sanitize_string|basic_tags',
            'phone_number'  =>  'trim|sanitize_string|basic_tags'
        ];
    }

    function datasource()
    {
        $pd = json_decode(file_get_contents('php://input'), true);
        $data['draw'] = isset($pd['draw']) ? $pd['draw'] : 1;
        $start = isset($pd['start']) ? $pd['start'] : 0;
        $length = isset($pd['length']) ? $pd['length'] : 10;
        $table = "customers";
        $selectdata = ['ID', 'customer_name', 'contact_number', 'email_address', 'contact_address', 'phone_number'];
        $orderby = dtv::orderby($selectdata, $table);
        $search = dtv::dt_search($selectdata, "deleted=0", $table);
        $fields = dtv::fields($selectdata, $table);
        $sql = "SELECT {$fields} FROM customers WHERE {$search} {$orderby}";
        $limit = " LIMIT {$start},{$length}";
        $data['recordsTotal'] = $this->dbal()->num_of_row($sql);
        $data['recordsFiltered'] = $this->dbal()->num_of_row($sql);
        $data['data'] = $this->dbal()->querydt($sql . $limit);
        return $data;
    }

    function customers_dbvalid($data)
    {
        $cond = "SELECT ID FROM customers WHERE ";
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



    function customers_CheckValid()
    {

        $gump = new \GUMP();
        $gump->set_fields_error_messages($this->customersValidationMessage());
        $data = array($_REQUEST['fname'] => $_REQUEST['fval']);
        $validated = $gump->is_valid($data, array_intersect_key($this->customersValidationRules(), array_flip(array($_REQUEST['fname']))));
        $dbvalid = $this->customers_dbvalid([$_REQUEST['fname'] => $_REQUEST['fval']]);

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





    function customersnew__record_create()
    {
        $gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->customersValidationRules());
        $gump->filter_rules($this->customersFilterRules());
        $gump->set_fields_error_messages($this->customersValidationMessage());
        $validated_data = $gump->run($_POST);
        $dbvalidation = $this->dbal()->num_of_row("SELECT ID FROM customers WHERE contact_number='{$_POST['contact_number']}'") ? "Contact Number Exists!" : null;
        //if($this->check_exits("students", "title={$validated_data['title']}")){$dbvalidation.="Data Already Exits";}
        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            if ($dbvalidation == null) {
                //$return= $validated_data['cellnumber'];
                $insertsql = "INSERT INTO  `customers` (
            `customer_name`,
`contact_number`,
`email_address`,
`company_name`,
`contact_address`,
`phone_number`,
`fax_number`,
`city`,
`country`,
`post_code`,
`VAT_number`,
`whatsapp_number`,
`is_app_access`)
            VALUES
            ('{$this->escStr($validated_data['customer_name'])}',
'{$this->escStr($validated_data['contact_number'])}',
'{$this->escStr($validated_data['email_address'])}',
'{$this->escStr($validated_data['company_name'])}',
'{$this->escStr($validated_data['contact_address'])}',
'{$this->escStr($validated_data['phone_number'])}',
'{$this->escStr($validated_data['fax_number'])}',
'{$this->escStr($validated_data['city'])}',
'{$this->escStr($validated_data['country'])}',
'{$this->escStr($validated_data['post_code'])}',
'{$this->escStr($validated_data['VAT_number'])}',
'{$this->escStr($validated_data['whatsapp_number'])}',
'1');";

                $insertID = $this->dbal()->query_exc($insertsql);
                if ($insertID) {
                    $return = [1, $insertID];
                    /*
						$this->dbal()->editLog("customers", "customer_name", $this->escStr($validated_data['customer_name']));
$this->dbal()->editLog("customers", "contact_number", $this->escStr($validated_data['contact_number']));
$this->dbal()->editLog("customers", "email_address", $this->escStr($validated_data['email_address']));
$this->dbal()->editLog("customers", "company_name", $this->escStr($validated_data['company_name']));
$this->dbal()->editLog("customers", "contact_address", $this->escStr($validated_data['contact_address']));
$this->dbal()->editLog("customers", "phone_number", $this->escStr($validated_data['phone_number']));
$this->dbal()->editLog("customers", "fax_number", $this->escStr($validated_data['fax_number']));
$this->dbal()->editLog("customers", "city", $this->escStr($validated_data['city']));
$this->dbal()->editLog("customers", "country", $this->escStr($validated_data['country']));
$this->dbal()->editLog("customers", "post_code", $this->escStr($validated_data['post_code']));
$this->dbal()->editLog("customers", "VAT_number", $this->escStr($validated_data['VAT_number']));
$this->dbal()->editLog("customers", "whatsapp_number", $this->escStr($validated_data['whatsapp_number']));
$this->dbal()->editLog("customers", "is_app_access", $this->escStr($validated_data['is_app_access']));

						*/
                } else {
                    $return = ["<span class=\"validerror\">"
                        . "We are Sorry; We can not record your Input to our Database Server</span>"];
                }
            } else {
                $return = ["<span class=\"validerror\">$dbvalidation</span>"];
            }
        }
        return $return;
    }





    function get_customersEditData()
    {
        return $this->dbal()->query("SELECT * FROM customers WHERE `ID`='{$this->comm()->rqstr('ID')}' LIMIT 1");
    }




    function customersedited_data_save()
    {
        $gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->customersValidationRules());
        $gump->filter_rules($this->customersFilterRules());
        $gump->set_fields_error_messages($this->customersValidationMessage());
        $validated_data = $gump->run($_POST);

        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            $dbvalidation = true; //$this->customers_dbvalid(['email' => $validated_data['email'], 'cell' => $validated_data['cell']]);
            if ($dbvalidation == true) {
                //$return= $validated_data['cellnumber'];
                $editsql = "UPDATE  customers SET 
				`customer_name` =  '{$this->escStr($validated_data['customer_name'])}',
				`contact_number` =  '{$this->escStr($validated_data['contact_number'])}',
				`email_address` =  '{$this->escStr($validated_data['email_address'])}',
				`company_name` =  '{$this->escStr($validated_data['company_name'])}',
				`contact_address` =  '{$this->escStr($validated_data['contact_address'])}',
				`phone_number` =  '{$this->escStr($validated_data['phone_number'])}',
				`fax_number` =  '{$this->escStr($validated_data['fax_number'])}',
				`city` =  '{$this->escStr($validated_data['city'])}',
				`country` =  '{$this->escStr($validated_data['country'])}',
				`post_code` =  '{$this->escStr($validated_data['post_code'])}',
				`VAT_number` =  '{$this->escStr($validated_data['VAT_number'])}',
				`whatsapp_number` =  '{$this->escStr($validated_data['whatsapp_number'])}',
				`is_app_access` =  '{$this->escStr($validated_data['is_app_access'])}' WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1";

                if ($this->dbal()->update_database($editsql)) {
                    $return = 1;
                    /*
			$this->dbal()->editLog("customers", "customer_name", $this->escStr($validated_data['customer_name']));
$this->dbal()->editLog("customers", "contact_number", $this->escStr($validated_data['contact_number']));
$this->dbal()->editLog("customers", "email_address", $this->escStr($validated_data['email_address']));
$this->dbal()->editLog("customers", "company_name", $this->escStr($validated_data['company_name']));
$this->dbal()->editLog("customers", "contact_address", $this->escStr($validated_data['contact_address']));
$this->dbal()->editLog("customers", "phone_number", $this->escStr($validated_data['phone_number']));
$this->dbal()->editLog("customers", "fax_number", $this->escStr($validated_data['fax_number']));
$this->dbal()->editLog("customers", "city", $this->escStr($validated_data['city']));
$this->dbal()->editLog("customers", "country", $this->escStr($validated_data['country']));
$this->dbal()->editLog("customers", "post_code", $this->escStr($validated_data['post_code']));
$this->dbal()->editLog("customers", "VAT_number", $this->escStr($validated_data['VAT_number']));
$this->dbal()->editLog("customers", "whatsapp_number", $this->escStr($validated_data['whatsapp_number']));
$this->dbal()->editLog("customers", "is_app_access", $this->escStr($validated_data['is_app_access']));

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
        return $this->dbal()->query("SELECT * FROM `customers` WHERE `deleted`=0");
    }

    function customersDeleteSql()
    {
        return $this->dbal()->update_database("UPDATE  customers SET `deleted` =  '1'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }



    function customersRestoreSql()
    {
        return $this->dbal()->update_database("UPDATE  customers SET `deleted` =  '0'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }

    function get_country_options_data()
    {
        return $this->dbal()->query('SELECT `en_short_name`,`en_short_name` FROM countries WHERE `deleted`=0');
    }
}
