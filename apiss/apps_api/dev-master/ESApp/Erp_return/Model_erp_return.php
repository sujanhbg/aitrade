<?php        
        
     use kring\database AS db;
use kring\utilities\comm;
    class Model_erp_return{

    function __construct() {


    }
    function comm() {
        return new comm();
    }

    function dbal() {
        return new db\dbal();
    }
function kring() {
        return new \kring\core\kring();
    }
    
    function escStr($str){
        return $this->dbal()->conn()->real_escape_string($str);
    }
   
        
    function erp_returnViewdata() {
        return $this->dbal()->query("SELECT 
				`ID`,
				`return_type`,
				`return_desc`,
				`return_date`,
				`employee_id`,
				`invoiceid`,
				`invoice_date`
                                FROM erp_return
                                WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
   }

                                
                                

    function erp_returnValidationRules(){
        return [
'ID'  =>  'required|min_len,1',
'return_type'  =>  'required|min_len,1',
'return_desc'  =>  'required|min_len,1',
'return_date'  =>  'required|min_len,1'];
    }


        

    function erp_returnValidationMessage(){
        return [ 
             'ID' => ['required' => 'ID  is required.','min_len'=>'Invalid ID'], 
             'return_type' => ['required' => 'Return Type  is required.','min_len'=>'Invalid return_type'], 
             'return_desc' => ['required' => 'Return Desc  is required.','min_len'=>'Invalid return_desc'], 
             'return_date' => ['required' => 'Return Date  is required.','min_len'=>'Invalid return_date'], 
             'employee_id' => ['required' => 'Employee Id  is required.','min_len'=>'Invalid employee_id'], 
             'invoiceid' => ['required' => 'Invoiceid  is required.','min_len'=>'Invalid invoiceid'], 
             'invoice_date' => ['required' => 'Invoice Date  is required.','min_len'=>'Invalid invoice_date']];
            }
        
        
    function erp_returnFilterRules(){
        return [
'ID'  =>  'trim|sanitize_string|basic_tags',
'return_type'  =>  'trim|sanitize_string|basic_tags',
'return_desc'  =>  'trim|sanitize_string|basic_tags',
'return_date'  =>  'trim|sanitize_string|basic_tags',
'employee_id'  =>  'trim|sanitize_string|basic_tags',
'invoiceid'  =>  'trim|sanitize_string|basic_tags',
'invoice_date'  =>  'trim|sanitize_string|basic_tags'];
    }

    function datasource() {
        $pd = json_decode(file_get_contents('php://input'), true);
        $data['draw'] = isset($pd['draw']) ? $pd['draw'] : 1;
        $start = isset($pd['start']) ? $pd['start'] : 0;
        $length = isset($pd['length']) ? $pd['length'] : 10;
        $table = "erp_return";
        $selectdata = ['ID','return_type','return_desc','return_date','employee_id','invoiceid','invoice_date'];
        $orderby = dtv::orderby($selectdata,$table);
        $search = dtv::dt_search($selectdata, "deleted=0",$table);
        $fields = dtv::fields($selectdata,$table);
        $sql = "SELECT {$fields} FROM erp_return WHERE {$search} {$orderby}";
        $limit=" LIMIT {$start},{$length}";
        $data['recordsTotal'] = $this->dbal()->num_of_row($sql);
        $data['recordsFiltered'] = $this->dbal()->num_of_row($sql);
        $data['data'] = $this->dbal()->querydt($sql.$limit);
        return $data;

    }    
        
        function erp_return_dbvalid($data) {
        $cond = "SELECT ID FROM erp_return WHERE ";
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
        
        
        
 function erp_return_CheckValid() {

            $gump = new \GUMP();
            $gump->set_fields_error_messages($this->erp_returnValidationMessage());
            $data = array($_REQUEST['fname'] => $_REQUEST['fval']);
            $validated = $gump->is_valid($data, array_intersect_key($this->erp_returnValidationRules(), array_flip(array($_REQUEST['fname']))));
            $dbvalid = $this->erp_return_dbvalid([$_REQUEST['fname'] => $_REQUEST['fval']]);

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
            
            
            
            
            
   function erp_returnnew__record_create()
    {
        $gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->erp_returnValidationRules());
        $gump->filter_rules($this->erp_returnFilterRules());
        $gump->set_fields_error_messages($this->erp_returnValidationMessage());
        $validated_data = $gump->run($_POST);
        $dbvalidation=null;
        //if($this->check_exits("students", "title={$validated_data['title']}")){$dbvalidation.="Data Already Exits";}
        $return="";
        if($validated_data === false) {
            $return= $gump->get_readable_errors(true);
        } else {
            if($dbvalidation==null){
                //$return= $validated_data['cellnumber'];
        $insertsql="INSERT INTO  `erp_return` (
            `return_type`,
`return_desc`,
`return_date`,
`employee_id`,
`invoiceid`,
`invoice_date`)
            VALUES
            ('{$this->escStr($validated_data['return_type'])}',
'{$this->escStr($validated_data['return_desc'])}',
'{$this->escStr($validated_data['return_date'])}',
'{$this->escStr($validated_data['employee_id'])}',
'{$this->escStr($validated_data['invoiceid'])}',
'{$this->escStr($validated_data['invoice_date'])}');";

                if($this->dbal()->query_exc($insertsql)){ 
						$return= 1;
						/*
						$this->dbal()->editLog("erp_return", "return_type", $this->escStr($validated_data['return_type']));
$this->dbal()->editLog("erp_return", "return_desc", $this->escStr($validated_data['return_desc']));
$this->dbal()->editLog("erp_return", "return_date", $this->escStr($validated_data['return_date']));
$this->dbal()->editLog("erp_return", "employee_id", $this->escStr($validated_data['employee_id']));
$this->dbal()->editLog("erp_return", "invoiceid", $this->escStr($validated_data['invoiceid']));
$this->dbal()->editLog("erp_return", "invoice_date", $this->escStr($validated_data['invoice_date']));

						*/
						}else{ 
					$return= "<span class=\"validerror\">"
                    . "We are Sorry; We can not record your Input to our Database Server</span>"; }
                }else{
                      $return= "<span class=\"validerror\">$dbvalidation</span>";
                }

        }
        return $return;

    }
        
        
        
        
        
function get_erp_returnEditData() {
        return $this->dbal()->query("SELECT * FROM erp_return WHERE `ID`='{$this->comm()->rqstr('ID')}' LIMIT 1");
    }
        
        
        
        
    function erp_returnedited_data_save()
    {
$gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->erp_returnValidationRules());
        $gump->filter_rules($this->erp_returnFilterRules());
        $gump->set_fields_error_messages($this->erp_returnValidationMessage());
        $validated_data = $gump->run($_POST);

        $return="";
if($validated_data === false) {
    $return= $gump->get_readable_errors(true);
} else {
        $dbvalidation = true; //$this->erp_return_dbvalid(['email' => $validated_data['email'], 'cell' => $validated_data['cell']]);
    if($dbvalidation==true){
    //$return= $validated_data['cellnumber'];
$editsql="UPDATE  erp_return SET 
				`return_type` =  '{$this->escStr($validated_data['return_type'])}',
				`return_desc` =  '{$this->escStr($validated_data['return_desc'])}',
				`return_date` =  '{$this->escStr($validated_data['return_date'])}',
				`employee_id` =  '{$this->escStr($validated_data['employee_id'])}',
				`invoiceid` =  '{$this->escStr($validated_data['invoiceid'])}',
				`invoice_date` =  '{$this->escStr($validated_data['invoice_date'])}' WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1";

        if($this->dbal()->update_database($editsql)){ 
			$return= 1;
			/*
			$this->dbal()->editLog("erp_return", "return_type", $this->escStr($validated_data['return_type']));
$this->dbal()->editLog("erp_return", "return_desc", $this->escStr($validated_data['return_desc']));
$this->dbal()->editLog("erp_return", "return_date", $this->escStr($validated_data['return_date']));
$this->dbal()->editLog("erp_return", "employee_id", $this->escStr($validated_data['employee_id']));
$this->dbal()->editLog("erp_return", "invoiceid", $this->escStr($validated_data['invoiceid']));
$this->dbal()->editLog("erp_return", "invoice_date", $this->escStr($validated_data['invoice_date']));

			*/
			}else{ 
			$return= "<span class=\"validerror\">"
            . "We are Sorry; We can not save your update</span>"; }
        }else{
              $return= "<span class=\"validerror\">Data Exists!</span>";
        }

}
        return $return;
}

        
function get_for_select() {
        return $this->dbal()->query("SELECT * FROM `erp_return` WHERE `deleted`=0");
    }        
        
   function erp_returnDeleteSql(){
                return $this->dbal()->update_database("UPDATE  erp_return SET `deleted` =  '1'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
                }

                
                
   function erp_returnRestoreSql(){
            return $this->dbal()->update_database("UPDATE  erp_return SET `deleted` =  '0'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");

   }

            }   