<?php        
        
     use kring\database AS db;
use kring\utilities\comm;
    class Model_notice{

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
   
        
    function noticeViewdata() {
        return $this->dbal()->query("SELECT 
				`ID`,
				`notice_to`,
				`notice_body`,
				`readed`,
				`sent_time`,
				`read_time`,
				`sent_from`,
				`deleted`
                                FROM notice
                                WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
   }

                                
                                

    function noticeValidationRules(){
        return [
'ID'  =>  'required|min_len,1',
'notice_to'  =>  'required|min_len,1',
'notice_body'  =>  'required|min_len,1',
'readed'  =>  'required|min_len,1',
'sent_time'  =>  'required|min_len,1',
'read_time'  =>  'required|min_len,1',
'sent_from'  =>  'required|min_len,1',
'deleted'  =>  'required|min_len,1'];
    }


        

    function noticeValidationMessage(){
        return [ 
             'ID' => ['required' => 'ID  is required.','min_len'=>'Invalid ID'], 
             'notice_to' => ['required' => 'Notice To  is required.','min_len'=>'Invalid notice_to'], 
             'notice_body' => ['required' => 'Notice Body  is required.','min_len'=>'Invalid notice_body'], 
             'readed' => ['required' => 'Readed  is required.','min_len'=>'Invalid readed'], 
             'sent_time' => ['required' => 'Sent Time  is required.','min_len'=>'Invalid sent_time'], 
             'read_time' => ['required' => 'Read Time  is required.','min_len'=>'Invalid read_time'], 
             'sent_from' => ['required' => 'Sent From  is required.','min_len'=>'Invalid sent_from'], 
             'deleted' => ['required' => 'Deleted  is required.','min_len'=>'Invalid deleted']];
            }
        
        
    function noticeFilterRules(){
        return [
'ID'  =>  'trim|sanitize_string|basic_tags',
'notice_to'  =>  'trim|sanitize_string|basic_tags',
'notice_body'  =>  'trim|sanitize_string|basic_tags',
'readed'  =>  'trim|sanitize_string|basic_tags',
'sent_time'  =>  'trim|sanitize_string|basic_tags',
'read_time'  =>  'trim|sanitize_string|basic_tags',
'sent_from'  =>  'trim|sanitize_string|basic_tags',
'deleted'  =>  'trim|sanitize_string|basic_tags'];
    }

    function datasource() {
        $pd = json_decode(file_get_contents('php://input'), true);
        $data['draw'] = isset($pd['draw']) ? $pd['draw'] : 1;
        $start = isset($pd['start']) ? $pd['start'] : 0;
        $length = isset($pd['length']) ? $pd['length'] : 10;
        $table = "notice";
        $selectdata = ['ID','notice_to','notice_body','readed','sent_time','read_time','sent_from','deleted'];
        $orderby = dtv::orderby($selectdata,$table);
        $search = dtv::dt_search($selectdata, "deleted=0",$table);
        $fields = dtv::fields($selectdata,$table);
        $sql = "SELECT {$fields} FROM notice WHERE {$search} {$orderby}";
        $limit=" LIMIT {$start},{$length}";
        $data['recordsTotal'] = $this->dbal()->num_of_row($sql);
        $data['recordsFiltered'] = $this->dbal()->num_of_row($sql);
        $data['data'] = $this->dbal()->querydt($sql.$limit);
        return $data;

    }    
        
        function notice_dbvalid($data) {
        $cond = "SELECT ID FROM notice WHERE ";
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
        
        
        
 function notice_CheckValid() {

            $gump = new \GUMP();
            $gump->set_fields_error_messages($this->noticeValidationMessage());
            $data = array($_REQUEST['fname'] => $_REQUEST['fval']);
            $validated = $gump->is_valid($data, array_intersect_key($this->noticeValidationRules(), array_flip(array($_REQUEST['fname']))));
            $dbvalid = $this->notice_dbvalid([$_REQUEST['fname'] => $_REQUEST['fval']]);

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
            
            
            
            
            
   function noticenew__record_create()
    {
        $gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->noticeValidationRules());
        $gump->filter_rules($this->noticeFilterRules());
        $gump->set_fields_error_messages($this->noticeValidationMessage());
        $validated_data = $gump->run($_POST);
        $dbvalidation=null;
        //if($this->check_exits("students", "title={$validated_data['title']}")){$dbvalidation.="Data Already Exits";}
        $return="";
        if($validated_data === false) {
            $return= $gump->get_readable_errors(true);
        } else {
            if($dbvalidation==null){
                //$return= $validated_data['cellnumber'];
        $insertsql="INSERT INTO  `notice` (
            `ID`,
`notice_to`,
`notice_body`,
`readed`,
`sent_time`,
`read_time`,
`sent_from`,
`deleted`)
            VALUES
            ('{$this->escStr($validated_data['ID'])}',
'{$this->escStr($validated_data['notice_to'])}',
'{$this->escStr($validated_data['notice_body'])}',
'{$this->escStr($validated_data['readed'])}',
'{$this->escStr($validated_data['sent_time'])}',
'{$this->escStr($validated_data['read_time'])}',
'{$this->escStr($validated_data['sent_from'])}',
'{$this->escStr($validated_data['deleted'])}');";

                if($this->dbal()->query_exc($insertsql)){ 
						$return= 1;
						/*
						$this->dbal()->editLog("notice", "ID", $this->escStr($validated_data['ID']));
$this->dbal()->editLog("notice", "notice_to", $this->escStr($validated_data['notice_to']));
$this->dbal()->editLog("notice", "notice_body", $this->escStr($validated_data['notice_body']));
$this->dbal()->editLog("notice", "readed", $this->escStr($validated_data['readed']));
$this->dbal()->editLog("notice", "sent_time", $this->escStr($validated_data['sent_time']));
$this->dbal()->editLog("notice", "read_time", $this->escStr($validated_data['read_time']));
$this->dbal()->editLog("notice", "sent_from", $this->escStr($validated_data['sent_from']));
$this->dbal()->editLog("notice", "deleted", $this->escStr($validated_data['deleted']));

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
        
        
        
        
        
function get_noticeEditData() {
        return $this->dbal()->query("SELECT * FROM notice WHERE `ID`='{$this->comm()->rqstr('ID')}' LIMIT 1");
    }
        
        
        
        
    function noticeedited_data_save()
    {
$gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->noticeValidationRules());
        $gump->filter_rules($this->noticeFilterRules());
        $gump->set_fields_error_messages($this->noticeValidationMessage());
        $validated_data = $gump->run($_POST);

        $return="";
if($validated_data === false) {
    $return= $gump->get_readable_errors(true);
} else {
        $dbvalidation = true; //$this->notice_dbvalid(['email' => $validated_data['email'], 'cell' => $validated_data['cell']]);
    if($dbvalidation==true){
    //$return= $validated_data['cellnumber'];
$editsql="UPDATE  notice SET 
				`ID` =  '{$this->escStr($validated_data['ID'])}',
				`notice_to` =  '{$this->escStr($validated_data['notice_to'])}',
				`notice_body` =  '{$this->escStr($validated_data['notice_body'])}',
				`readed` =  '{$this->escStr($validated_data['readed'])}',
				`sent_time` =  '{$this->escStr($validated_data['sent_time'])}',
				`read_time` =  '{$this->escStr($validated_data['read_time'])}',
				`sent_from` =  '{$this->escStr($validated_data['sent_from'])}',
				`deleted` =  '{$this->escStr($validated_data['deleted'])}' WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1";

        if($this->dbal()->update_database($editsql)){ 
			$return= 1;
			/*
			$this->dbal()->editLog("notice", "ID", $this->escStr($validated_data['ID']));
$this->dbal()->editLog("notice", "notice_to", $this->escStr($validated_data['notice_to']));
$this->dbal()->editLog("notice", "notice_body", $this->escStr($validated_data['notice_body']));
$this->dbal()->editLog("notice", "readed", $this->escStr($validated_data['readed']));
$this->dbal()->editLog("notice", "sent_time", $this->escStr($validated_data['sent_time']));
$this->dbal()->editLog("notice", "read_time", $this->escStr($validated_data['read_time']));
$this->dbal()->editLog("notice", "sent_from", $this->escStr($validated_data['sent_from']));
$this->dbal()->editLog("notice", "deleted", $this->escStr($validated_data['deleted']));

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
        return $this->dbal()->query("SELECT * FROM `notice` WHERE `deleted`=0");
    }        
        
   function noticeDeleteSql(){
                return $this->dbal()->update_database("UPDATE  notice SET `deleted` =  '1'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
                }

                
                
   function noticeRestoreSql(){
            return $this->dbal()->update_database("UPDATE  notice SET `deleted` =  '0'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");

   }

            }   