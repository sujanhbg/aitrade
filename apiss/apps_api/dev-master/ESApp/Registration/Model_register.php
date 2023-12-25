<?php

use kring\database\dbal;
use kring\utilities\comm;
use kring\database\kdbal;
//use kring;

class Model_register
{

    public function __construct()
    {
    }

    public function comm()
    {
        return new comm();
    }

    public function dbal()
    {
        return new dbal();
    }


    function escStr($str)
    {
        return $this->dbal()->conn()->real_escape_string($str);
    }
    function userValidationRules()
    {
        return [
            'firstname'  =>  'required|min_len,1',
            'lastname'  =>  'required|min_len,1',
            'password'  =>  'required|min_len,1',
            'email'  =>  'required|min_len,1'
        ];
    }




    function userValidationMessage()
    {
        return [
            'firstname' => ['required' => 'Firstname  is required.<br>', 'min_len' => 'Invalid firstname<br>'],
            'lastname' => ['required' => 'Lastname  is required.<br>', 'min_len' => 'Invalid lastname<br>'],
            'password' => ['required' => 'Password  is required.<br>', 'min_len' => 'Invalid password<br>'],
            'email' => ['required' => 'Email  is required.<br>', 'min_len' => 'Invalid email<br>']
        ];
    }


    function userFilterRules()
    {
        return [
            'ID'  =>  'trim|sanitize_string|basic_tags',
            'firstname'  =>  'trim|sanitize_string|basic_tags',
            'lastname'  =>  'trim|sanitize_string|basic_tags',
            'password'  =>  'trim|sanitize_string|basic_tags',
            'email'  =>  'trim|sanitize_string|basic_tags'
        ];
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





    function usernew__record_create()
    {
        //echo "o";
        $postdata = json_decode(file_get_contents('php://input'), true);
        //print_r($postdata);
        //exit;
        $gump =  new GUMP();
        $postdatass = $gump->sanitize($postdata);
        $gump->validation_rules($this->userValidationRules());
        $gump->filter_rules($this->userFilterRules());
        $gump->set_fields_error_messages($this->userValidationMessage());
        $validated_data = $gump->run($postdatass);
        $dbvalidation = $this->dbal()->num_of_row("SELECT `ID` FROM user WHERE `email`='{$validated_data['email']}'") ? "The email already exists, Try with a different email or login." : null;
        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            if ($dbvalidation == null) {
                //$return= $validated_data['cellnumber'];
                $pass = substr(md5($validated_data['password']), 8, 23);
                $create_from = $_SERVER['REMOTE_ADDR'];
                $verifycode = substr(md5(time()), 0, 8);
                $insertsql = "INSERT INTO  `user` (
            `ID`,
`firstname`,
`lastname`,
`password`,
`email`,
`create_from`,
`active_code`)
            VALUES
            (NULL,
'{$this->escStr($validated_data['firstname'])}',
'{$this->escStr($validated_data['lastname'])}',
'{$this->escStr($pass)}',
'{$this->escStr($validated_data['email'])}',
'{$create_from}',
'{$verifycode}');";

                if ($this->dbal()->query_exc($insertsql)) {
                    $mail = new kring\core\mail();
                    $mail->send_mail_now(1, ['[firstname]', '[lastname]', '[code]'], [$validated_data['firstname'], $validated_data['lastname'], $verifycode], $validated_data['email'], "{$validated_data['firstname']} {$validated_data['lastname']}");
                    $_SESSION['wv'] = 1;
                    $_SESSION['wvmail'] = $validated_data['email'];
                    $return = 1;
                } else {
                    $return = ""
                        . "We are Sorry; We can not record your Input to our Database Server";
                }
            } else {
                $return = "$dbvalidation";
            }
        }
        return $return;
    }
    function usernew__record_create_phone()
    {
        $gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules([
            'firstname'  =>  'required|min_len,1',
            'lastname'  =>  'required|min_len,1',
            'password'  =>  'required|min_len,8',
            'cell'  =>  'required|min_len,11'
        ]);
        $gump->filter_rules($this->userFilterRules());
        $gump->set_fields_error_messages($this->userValidationMessage());
        $validated_data = $gump->run($_POST);
        $dbvalidation = $this->dbal()->num_of_row("SELECT `ID` FROM user WHERE `cell`='{$validated_data['cell']}'") ? "The Mobile Number already exists, Try with a different email or login." : null;
        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            if ($dbvalidation == null) {
                //$return= $validated_data['cellnumber'];
                $pass = substr(md5($validated_data['password']), 8, 23);
                $create_from = $_SERVER['REMOTE_ADDR'];
                $verifycode = substr(md5(time()), 0, 8);
                $insertsql = "INSERT INTO  `user` (
            `ID`,
`firstname`,
`lastname`,
`password`,
`cell`,
`create_from`,
`active_code`)
            VALUES
            (NULL,
'{$this->escStr($validated_data['firstname'])}',
'{$this->escStr($validated_data['lastname'])}',
'{$this->escStr($pass)}',
'{$this->escStr($validated_data['cell'])}',
'{$create_from}',
'{$verifycode}');";

                if ($this->dbal()->query_exc($insertsql)) {
                    //Send SMS with Verification Code
                    $return = 1;
                } else {
                    $return = ""
                        . "We are Sorry; We can not record your Input to our Database Server";
                }
            } else {
                $return = "$dbvalidation";
            }
        }
        return $return;
    }

    function verify()
    {
        if (isset($_POST['email'])) {
            $eml = $_POST['email'];
            $vcode = $_POST['code'];
            if ($this->dbal()->num_of_row("SELECT ID FROM user WHERE active_code='{$vcode}' AND email='{$eml}'")) {
                $ress = $this->dbal()->update_database("UPDATE `user` SET `active`=1 WHERE `active_code`='{$vcode}' AND `email`='{$eml}' ") ? 1 : "Wrong Verification Code!";
                echo $ress;
                echo "\n\n\n";
                if ($ress == 1) {
                    $usn = $this->dbal()->query("SELECT firstname, lastname FROM user WHERE email='{$eml}' LIMIT 1")[0];
                    $mail = new kring\core\mail();
                    $mail->send_mail_now(3, ['[firstname]', '[lastname]', '[code]'], [$usn['firstname'], $usn['lastname']], $eml, "{$usn['firstname']} {$usn['lastname']}");
                    //session_unset();
                }
                return $ress;
            } else {
                return "Wrong code submitted!";
            }
        } else {
            return "Email Address is not found";
        }
    }
}
