<?php

use kring\database as db;
use kring\utilities\comm;

class Model_product_unit
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


    function product_unitViewdata()
    {
        return $this->dbal()->query("SELECT 
				`ID`,
				`unit_name`
                                FROM product_unit
                                WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }




    function product_unitValidationRules()
    {
        return [
            'unit_name'  =>  'required|min_len,1'
        ];
    }




    function product_unitValidationMessage()
    {
        return [
            'unit_name' => ['required' => 'Unit Name  is required.', 'min_len' => 'Invalid unit_name']
        ];
    }


    function product_unitFilterRules()
    {
        return [
            'unit_name'  =>  'trim|sanitize_string|basic_tags'
        ];
    }

    function datasource()
    {
        $pd = json_decode(file_get_contents('php://input'), true);
        $data['draw'] = isset($pd['draw']) ? $pd['draw'] : 1;
        $start = isset($pd['start']) ? $pd['start'] : 0;
        $length = isset($pd['length']) ? $pd['length'] : 10;
        $table = "product_unit";
        $selectdata = ['ID', 'unit_name'];
        $orderby = dtv::orderby($selectdata, $table);
        $search = dtv::dt_search($selectdata, "deleted=0", $table);
        $fields = dtv::fields($selectdata, $table);
        $sql = "SELECT {$fields} FROM product_unit WHERE {$search} {$orderby}";
        $limit = " LIMIT {$start},{$length}";
        $data['recordsTotal'] = $this->dbal()->num_of_row($sql);
        $data['recordsFiltered'] = $this->dbal()->num_of_row($sql);
        $data['data'] = $this->dbal()->querydt($sql . $limit);
        return $data;
    }

    function product_unit_dbvalid($data)
    {
        $cond = "SELECT ID FROM product_unit WHERE ";
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



    function product_unit_CheckValid()
    {

        $gump = new \GUMP();
        $gump->set_fields_error_messages($this->product_unitValidationMessage());
        $data = array($_REQUEST['fname'] => $_REQUEST['fval']);
        $validated = $gump->is_valid($data, array_intersect_key($this->product_unitValidationRules(), array_flip(array($_REQUEST['fname']))));
        $dbvalid = $this->product_unit_dbvalid([$_REQUEST['fname'] => $_REQUEST['fval']]);

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





    function product_unitnew__record_create()
    {
        $gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->product_unitValidationRules());
        $gump->filter_rules($this->product_unitFilterRules());
        $gump->set_fields_error_messages($this->product_unitValidationMessage());
        $validated_data = $gump->run($_POST);
        $dbvalidation = null;
        //if($this->check_exits("students", "title={$validated_data['title']}")){$dbvalidation.="Data Already Exits";}
        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            if ($dbvalidation == null) {
                //$return= $validated_data['cellnumber'];
                $insertsql = "INSERT INTO  `product_unit` (
            `unit_name`)
            VALUES
            ('{$this->escStr($validated_data['unit_name'])}');";

                if ($this->dbal()->query_exc($insertsql)) {
                    $return = 1;
                    /*
						$this->dbal()->editLog("product_unit", "unit_name", $this->escStr($validated_data['unit_name']));

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





    function get_product_unitEditData()
    {
        return $this->dbal()->query("SELECT * FROM product_unit WHERE `ID`='{$this->comm()->rqstr('ID')}' LIMIT 1");
    }




    function product_unitedited_data_save()
    {
        $gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->product_unitValidationRules());
        $gump->filter_rules($this->product_unitFilterRules());
        $gump->set_fields_error_messages($this->product_unitValidationMessage());
        $validated_data = $gump->run($_POST);

        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            $dbvalidation = true; //$this->product_unit_dbvalid(['email' => $validated_data['email'], 'cell' => $validated_data['cell']]);
            if ($dbvalidation == true) {
                //$return= $validated_data['cellnumber'];
                $editsql = "UPDATE  product_unit SET 
				`unit_name` =  '{$this->escStr($validated_data['unit_name'])}' WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1";

                if ($this->dbal()->update_database($editsql)) {
                    $return = 1;
                    /*
			$this->dbal()->editLog("product_unit", "unit_name", $this->escStr($validated_data['unit_name']));

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
        return $this->dbal()->query("SELECT * FROM `product_unit` WHERE `deleted`=0");
    }

    function product_unitDeleteSql()
    {
        return $this->dbal()->update_database("UPDATE  product_unit SET `deleted` =  '1'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }



    function product_unitRestoreSql()
    {
        return $this->dbal()->update_database("UPDATE  product_unit SET `deleted` =  '0'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }
}
