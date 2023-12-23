<?php        
        
     use kring\database AS db;
use kring\utilities\comm;
    class Model_product_brand{

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
   
        
    function product_brandViewdata() {
        return $this->dbal()->query("SELECT 
				`ID`,
				`brand_name`,
				`brand_logo`
                                FROM product_brand
                                WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
   }

                                
                                

    function product_brandValidationRules(){
        return [
'brand_name'  =>  'required|min_len,1'];
    }


        

    function product_brandValidationMessage(){
        return [ 
             'ID' => ['required' => 'ID  is required.','min_len'=>'Invalid ID'], 
             'brand_name' => ['required' => 'Brand Name  is required.','min_len'=>'Invalid brand_name'], 
             'brand_logo' => ['required' => 'Brand Logo  is required.','min_len'=>'Invalid brand_logo']];
            }
        
        
    function product_brandFilterRules(){
        return [
'brand_name'  =>  'trim|sanitize_string|basic_tags',
'brand_logo'  =>  'trim|sanitize_string|basic_tags'];
    }

    function datasource() {
        $pd = json_decode(file_get_contents('php://input'), true);
        $data['draw'] = isset($pd['draw']) ? $pd['draw'] : 1;
        $start = isset($pd['start']) ? $pd['start'] : 0;
        $length = isset($pd['length']) ? $pd['length'] : 10;
        $table = "product_brand";
        $selectdata = ['ID','brand_name','brand_logo'];
        $orderby = dtv::orderby($selectdata,$table);
        $search = dtv::dt_search($selectdata, "deleted=0",$table);
        $fields = dtv::fields($selectdata,$table);
        $sql = "SELECT {$fields} FROM product_brand WHERE {$search} {$orderby}";
        $limit=" LIMIT {$start},{$length}";
        $data['recordsTotal'] = $this->dbal()->num_of_row($sql);
        $data['recordsFiltered'] = $this->dbal()->num_of_row($sql);
        $data['data'] = $this->dbal()->querydt($sql.$limit);
        return $data;

    }    
        
        function product_brand_dbvalid($data) {
        $cond = "SELECT ID FROM product_brand WHERE ";
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
        
        
        
 function product_brand_CheckValid() {

            $gump = new \GUMP();
            $gump->set_fields_error_messages($this->product_brandValidationMessage());
            $data = array($_REQUEST['fname'] => $_REQUEST['fval']);
            $validated = $gump->is_valid($data, array_intersect_key($this->product_brandValidationRules(), array_flip(array($_REQUEST['fname']))));
            $dbvalid = $this->product_brand_dbvalid([$_REQUEST['fname'] => $_REQUEST['fval']]);

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
            
            
            
            
            
   function product_brandnew__record_create()
    {
        $gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->product_brandValidationRules());
        $gump->filter_rules($this->product_brandFilterRules());
        $gump->set_fields_error_messages($this->product_brandValidationMessage());
        $validated_data = $gump->run($_POST);
        $dbvalidation=null;
        //if($this->check_exits("students", "title={$validated_data['title']}")){$dbvalidation.="Data Already Exits";}
        $return="";
        if($validated_data === false) {
            $return= $gump->get_readable_errors(true);
        } else {
            if($dbvalidation==null){
                //$return= $validated_data['cellnumber'];
        $insertsql="INSERT INTO  `product_brand` (
            `brand_name`,
`brand_logo`)
            VALUES
            ('{$this->escStr($validated_data['brand_name'])}',
'{$this->escStr($validated_data['brand_logo'])}');";

                if($this->dbal()->query_exc($insertsql)){ 
						$return= 1;
						/*
						$this->dbal()->editLog("product_brand", "brand_name", $this->escStr($validated_data['brand_name']));
$this->dbal()->editLog("product_brand", "brand_logo", $this->escStr($validated_data['brand_logo']));

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
        
        
        
        
        
function get_product_brandEditData() {
        return $this->dbal()->query("SELECT * FROM product_brand WHERE `ID`='{$this->comm()->rqstr('ID')}' LIMIT 1");
    }
        
        
        
        
    function product_brandedited_data_save()
    {
$gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->product_brandValidationRules());
        $gump->filter_rules($this->product_brandFilterRules());
        $gump->set_fields_error_messages($this->product_brandValidationMessage());
        $validated_data = $gump->run($_POST);

        $return="";
if($validated_data === false) {
    $return= $gump->get_readable_errors(true);
} else {
        $dbvalidation = true; //$this->product_brand_dbvalid(['email' => $validated_data['email'], 'cell' => $validated_data['cell']]);
    if($dbvalidation==true){
    //$return= $validated_data['cellnumber'];
$editsql="UPDATE  product_brand SET 
				`brand_name` =  '{$this->escStr($validated_data['brand_name'])}',
				`brand_logo` =  '{$this->escStr($validated_data['brand_logo'])}' WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1";

        if($this->dbal()->update_database($editsql)){ 
			$return= 1;
			/*
			$this->dbal()->editLog("product_brand", "brand_name", $this->escStr($validated_data['brand_name']));
$this->dbal()->editLog("product_brand", "brand_logo", $this->escStr($validated_data['brand_logo']));

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
        return $this->dbal()->query("SELECT * FROM `product_brand` WHERE `deleted`=0");
    }        
        
   function product_brandDeleteSql(){
                return $this->dbal()->update_database("UPDATE  product_brand SET `deleted` =  '1'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
                }

                
                
   function product_brandRestoreSql(){
            return $this->dbal()->update_database("UPDATE  product_brand SET `deleted` =  '0'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");

   }

            }   