<?php

use kring\database as db;
use kring\utilities\comm;

class Model_product_catagory
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


    function product_catagoryViewdata()
    {
        return $this->dbal()->query("SELECT 
				`ID`,
				`category_name`,
				`subfor`,
				`image`,
				`online`
                                FROM product_catagory
                                WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }




    function product_catagoryValidationRules()
    {
        return [
            'category_name'  =>  'required|min_len,1'
        ];
    }




    function product_catagoryValidationMessage()
    {
        return [
            'ID' => ['required' => 'ID  is required.', 'min_len' => 'Invalid ID'],
            'category_name' => ['required' => 'Category Name  is required.', 'min_len' => 'Invalid category_name'],
            'subfor' => ['required' => 'Subfor  is required.', 'min_len' => 'Invalid subfor'],
            'image' => ['required' => 'Image  is required.', 'min_len' => 'Invalid image'],
            'online' => ['required' => 'Online  is required.', 'min_len' => 'Invalid online']
        ];
    }


    function product_catagoryFilterRules()
    {
        return [
            'ID'  =>  'trim|sanitize_string|basic_tags',
            'category_name'  =>  'trim|sanitize_string|basic_tags',
            'subfor'  =>  'trim|sanitize_string|basic_tags',
            'image'  =>  'trim|sanitize_string|basic_tags',
            'online'  =>  'trim|sanitize_string|basic_tags'
        ];
    }

    function datasource()
    {
        $pd = json_decode(file_get_contents('php://input'), true);
        $data['draw'] = isset($pd['draw']) ? $pd['draw'] : 1;
        $start = isset($pd['start']) ? $pd['start'] : 0;
        $length = isset($pd['length']) ? $pd['length'] : 10;
        $table = "product_catagory";
        $selectdata = ['ID', 'category_name', 'subfor', 'image', 'online'];
        $orderby = dtv::orderby($selectdata, $table);
        $search = dtv::dt_search($selectdata, "deleted=0", $table);
        $fields = dtv::fields($selectdata, $table);
        $sql = "SELECT {$fields} FROM product_catagory WHERE {$search} {$orderby}";
        $limit = " LIMIT {$start},{$length}";
        $data['recordsTotal'] = $this->dbal()->num_of_row($sql);
        $data['recordsFiltered'] = $this->dbal()->num_of_row($sql);
        $data['data'] = $this->dbal()->querydt($sql . $limit);
        return $data;
    }

    function product_catagory_dbvalid($data)
    {
        $cond = "SELECT ID FROM product_catagory WHERE ";
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



    function product_catagory_CheckValid()
    {

        $gump = new \GUMP();
        $gump->set_fields_error_messages($this->product_catagoryValidationMessage());
        $data = array($_REQUEST['fname'] => $_REQUEST['fval']);
        $validated = $gump->is_valid($data, array_intersect_key($this->product_catagoryValidationRules(), array_flip(array($_REQUEST['fname']))));
        $dbvalid = $this->product_catagory_dbvalid([$_REQUEST['fname'] => $_REQUEST['fval']]);

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





    function product_catagorynew__record_create()
    {
        $gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->product_catagoryValidationRules());
        $gump->filter_rules($this->product_catagoryFilterRules());
        $gump->set_fields_error_messages($this->product_catagoryValidationMessage());
        $validated_data = $gump->run($_POST);
        $dbvalidation = null;
        //if($this->check_exits("students", "title={$validated_data['title']}")){$dbvalidation.="Data Already Exits";}
        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            if ($dbvalidation == null) {
                //$return= $validated_data['cellnumber'];
                $insertsql = "INSERT INTO  `product_catagory` (
            `category_name`,
`subfor`,
`image`,
`online`,
`full_desc`)
            VALUES
            ('{$this->escStr($validated_data['category_name'])}',
'{$this->escStr($validated_data['subfor'])}',
'{$this->escStr($validated_data['image'])}',
'{$this->escStr($validated_data['online'])}',
'{$this->escStr($validated_data['full_desc'])}');";

                if ($this->dbal()->query_exc($insertsql)) {
                    $return = 1;
                    /*
						$this->dbal()->editLog("product_catagory", "category_name", $this->escStr($validated_data['category_name']));
$this->dbal()->editLog("product_catagory", "subfor", $this->escStr($validated_data['subfor']));
$this->dbal()->editLog("product_catagory", "image", $this->escStr($validated_data['image']));
$this->dbal()->editLog("product_catagory", "online", $this->escStr($validated_data['online']));
$this->dbal()->editLog("product_catagory", "full_desc", $this->escStr($validated_data['full_desc']));

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





    function get_product_catagoryEditData()
    {
        return $this->dbal()->query("SELECT * FROM product_catagory WHERE `ID`='{$this->comm()->rqstr('ID')}' LIMIT 1");
    }




    function product_catagoryedited_data_save()
    {
        $gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->product_catagoryValidationRules());
        $gump->filter_rules($this->product_catagoryFilterRules());
        $gump->set_fields_error_messages($this->product_catagoryValidationMessage());
        $validated_data = $gump->run($_POST);

        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            $dbvalidation = true; //$this->product_catagory_dbvalid(['email' => $validated_data['email'], 'cell' => $validated_data['cell']]);
            if ($dbvalidation == true) {
                //$return= $validated_data['cellnumber'];
                $editsql = "UPDATE  product_catagory SET 
				`category_name` =  '{$this->escStr($validated_data['category_name'])}',
				`subfor` =  '{$this->escStr($validated_data['subfor'])}',
				`image` =  '{$this->escStr($validated_data['image'])}',
				`online` =  '{$this->escStr($validated_data['online'])}',
				`full_desc` =  '{$this->escStr($validated_data['full_desc'])}' WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1";

                if ($this->dbal()->update_database($editsql)) {
                    $return = 1;
                    /*
			$this->dbal()->editLog("product_catagory", "category_name", $this->escStr($validated_data['category_name']));
$this->dbal()->editLog("product_catagory", "subfor", $this->escStr($validated_data['subfor']));
$this->dbal()->editLog("product_catagory", "image", $this->escStr($validated_data['image']));
$this->dbal()->editLog("product_catagory", "online", $this->escStr($validated_data['online']));
$this->dbal()->editLog("product_catagory", "full_desc", $this->escStr($validated_data['full_desc']));

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
        return $this->dbal()->query("SELECT * FROM `product_catagory` WHERE `deleted`=0");
    }

    function product_catagoryDeleteSql()
    {
        return $this->dbal()->update_database("UPDATE  product_catagory SET `deleted` =  '1'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }



    function product_catagoryRestoreSql()
    {
        return $this->dbal()->update_database("UPDATE  product_catagory SET `deleted` =  '0'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }

    function get_subfor_options_data()
    {
        return $this->dbal()->query('SELECT `ID`,`category_name` FROM product_catagory WHERE `deleted`=0');
    }

    function get_category()
    {

        $pidq = isset($_GET['pid']) ? $this->comm()->rqstr('pid') : 0;
        //echo "SELECT `ID`,`category_name`,`online` FROM product_catagory WHERE `deleted`=0 AND `subfor`={$pidq}";
        return $this->dbal()->query("SELECT `ID`,`category_name`,`online` FROM product_catagory WHERE `deleted`=0 AND `subfor`={$pidq}");
    }
}
