<?php        
        
     use kring\database AS db;
use kring\utilities\comm;
    class Model_erp_receiveing{

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
   
        
    function erp_receiveingViewdata() {
        return $this->dbal()->query("SELECT 
				`ID`,
				`receiving_time`,
				`supplier`,
				`employe_id`,
				`invoice_number`,
				`status`
                                FROM erp_receiveing
                                WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
   }

                                
                                

    function erp_receiveingValidationRules(){
        return [];
    }


        

    function erp_receiveingValidationMessage(){
        return [ 
             'ID' => ['required' => 'ID  is required.','min_len'=>'Invalid ID'], 
             'receiving_time' => ['required' => 'Receiving Time  is required.','min_len'=>'Invalid receiving_time'], 
             'supplier' => ['required' => 'Supplier  is required.','min_len'=>'Invalid supplier'], 
             'employe_id' => ['required' => 'Employe Id  is required.','min_len'=>'Invalid employe_id'], 
             'invoice_number' => ['required' => 'Invoice Number  is required.','min_len'=>'Invalid invoice_number'], 
             'status' => ['required' => 'Status  is required.','min_len'=>'Invalid status']];
            }
        
        
    function erp_receiveingFilterRules(){
        return [
'ID'  =>  'trim|sanitize_string|basic_tags',
'receiving_time'  =>  'trim|sanitize_string|basic_tags',
'supplier'  =>  'trim|sanitize_string|basic_tags',
'employe_id'  =>  'trim|sanitize_string|basic_tags',
'invoice_number'  =>  'trim|sanitize_string|basic_tags',
'status'  =>  'trim|sanitize_string|basic_tags'];
    }

    function datasource() {
        $pd = json_decode(file_get_contents('php://input'), true);
        $data['draw'] = isset($pd['draw']) ? $pd['draw'] : 1;
        $start = isset($pd['start']) ? $pd['start'] : 0;
        $length = isset($pd['length']) ? $pd['length'] : 10;
        $table = "erp_receiveing";
        $selectdata = ['ID','receiving_time','supplier','employe_id','invoice_number','status'];
        $orderby = dtv::orderby($selectdata,$table);
        $search = dtv::dt_search($selectdata, "deleted=0",$table);
        $fields = dtv::fields($selectdata,$table);
        $sql = "SELECT {$fields} FROM erp_receiveing WHERE {$search} {$orderby}";
        $limit=" LIMIT {$start},{$length}";
        $data['recordsTotal'] = $this->dbal()->num_of_row($sql);
        $data['recordsFiltered'] = $this->dbal()->num_of_row($sql);
        $data['data'] = $this->dbal()->querydt($sql.$limit);
        return $data;

    }    
        
        function erp_receiveing_dbvalid($data) {
        $cond = "SELECT ID FROM erp_receiveing WHERE ";
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
        
        
        
 function erp_receiveing_CheckValid() {

            $gump = new \GUMP();
            $gump->set_fields_error_messages($this->erp_receiveingValidationMessage());
            $data = array($_REQUEST['fname'] => $_REQUEST['fval']);
            $validated = $gump->is_valid($data, array_intersect_key($this->erp_receiveingValidationRules(), array_flip(array($_REQUEST['fname']))));
            $dbvalid = $this->erp_receiveing_dbvalid([$_REQUEST['fname'] => $_REQUEST['fval']]);

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
            
            
            
            
            
   function erp_receiveingnew__record_create()
    {
        $gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->erp_receiveingValidationRules());
        $gump->filter_rules($this->erp_receiveingFilterRules());
        $gump->set_fields_error_messages($this->erp_receiveingValidationMessage());
        $validated_data = $gump->run($_POST);
        $dbvalidation=null;
        //if($this->check_exits("students", "title={$validated_data['title']}")){$dbvalidation.="Data Already Exits";}
        $return="";
        if($validated_data === false) {
            $return= $gump->get_readable_errors(true);
        } else {
            if($dbvalidation==null){
                //$return= $validated_data['cellnumber'];
        $insertsql="INSERT INTO  `erp_receiveing` (
            `receiving_time`,
`supplier`,
`employe_id`,
`invoice_number`,
`status`)
            VALUES
            ('{$this->escStr($validated_data['receiving_time'])}',
'{$this->escStr($validated_data['supplier'])}',
'{$this->escStr($validated_data['employe_id'])}',
'{$this->escStr($validated_data['invoice_number'])}',
'{$this->escStr($validated_data['status'])}');";

                if($this->dbal()->query_exc($insertsql)){ 
						$return= 1;
						/*
						$this->dbal()->editLog("erp_receiveing", "receiving_time", $this->escStr($validated_data['receiving_time']));
$this->dbal()->editLog("erp_receiveing", "supplier", $this->escStr($validated_data['supplier']));
$this->dbal()->editLog("erp_receiveing", "employe_id", $this->escStr($validated_data['employe_id']));
$this->dbal()->editLog("erp_receiveing", "invoice_number", $this->escStr($validated_data['invoice_number']));
$this->dbal()->editLog("erp_receiveing", "status", $this->escStr($validated_data['status']));

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
        
        
        
        
        
function get_erp_receiveingEditData() {
        return $this->dbal()->query("SELECT * FROM erp_receiveing WHERE `ID`='{$this->comm()->rqstr('ID')}' LIMIT 1");
    }
        
        
        
        
    function erp_receiveingedited_data_save()
    {
$gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->erp_receiveingValidationRules());
        $gump->filter_rules($this->erp_receiveingFilterRules());
        $gump->set_fields_error_messages($this->erp_receiveingValidationMessage());
        $validated_data = $gump->run($_POST);

        $return="";
if($validated_data === false) {
    $return= $gump->get_readable_errors(true);
} else {
        $dbvalidation = true; //$this->erp_receiveing_dbvalid(['email' => $validated_data['email'], 'cell' => $validated_data['cell']]);
    if($dbvalidation==true){
    //$return= $validated_data['cellnumber'];
$editsql="UPDATE  erp_receiveing SET 
				`receiving_time` =  '{$this->escStr($validated_data['receiving_time'])}',
				`supplier` =  '{$this->escStr($validated_data['supplier'])}',
				`employe_id` =  '{$this->escStr($validated_data['employe_id'])}',
				`invoice_number` =  '{$this->escStr($validated_data['invoice_number'])}',
				`status` =  '{$this->escStr($validated_data['status'])}' WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1";

        if($this->dbal()->update_database($editsql)){ 
			$return= 1;
			/*
			$this->dbal()->editLog("erp_receiveing", "receiving_time", $this->escStr($validated_data['receiving_time']));
$this->dbal()->editLog("erp_receiveing", "supplier", $this->escStr($validated_data['supplier']));
$this->dbal()->editLog("erp_receiveing", "employe_id", $this->escStr($validated_data['employe_id']));
$this->dbal()->editLog("erp_receiveing", "invoice_number", $this->escStr($validated_data['invoice_number']));
$this->dbal()->editLog("erp_receiveing", "status", $this->escStr($validated_data['status']));

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
        return $this->dbal()->query("SELECT * FROM `erp_receiveing` WHERE `deleted`=0");
    }        
        
   function erp_receiveingDeleteSql(){
                return $this->dbal()->update_database("UPDATE  erp_receiveing SET `deleted` =  '1'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
                }

                
                
   function erp_receiveingRestoreSql(){
            return $this->dbal()->update_database("UPDATE  erp_receiveing SET `deleted` =  '0'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");

   }

            }   