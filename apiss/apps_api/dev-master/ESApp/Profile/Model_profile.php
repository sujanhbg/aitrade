        <?php

        use kring\auth\jwt;
        use kring\database as db;
        use kring\utilities\comm;

        class Model_Profile
        {
            private $jwt;
            function __construct()
            {
                $this->jwt = new jwt();
            }

            function comm()
            {
                return new comm();
            }

            function dbal()
            {
                return new db\dbal();
            }

            function getUserID()
            {
                return $this->jwt->get_uid()['uid'];
            }
            function getUserData()
            {
                return $this->dbal()->query("SELECT  `firstname`, `lastname`,  `email`,  `photo`, `cell`, `username`, `gender`, `nationality`, `telephone`, `streetaddr`, `city`, `region`, `country`, `postalcode` FROM `user` WHERE `ID`={$this->getUserID()} ")[0];
            }

            function userValidationRules()
            {
                return [];
            }




            function userValidationMessage()
            {
                return [];
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
                    'authkey'  =>  'trim|sanitize_string|basic_tags',
                    'hire_date'  =>  'trim|sanitize_string|basic_tags',
                    'designation'  =>  'trim|sanitize_string|basic_tags',
                    'department'  =>  'trim|sanitize_string|basic_tags',
                    'salary'  =>  'trim|sanitize_string|basic_tags',
                    'manager_id'  =>  'trim|sanitize_string|basic_tags',
                    'cv_file'  =>  'trim|sanitize_string|basic_tags',
                    'is_consultant'  =>  'trim|sanitize_string|basic_tags'
                ];
            }
















            function escStr($str)
            {
                return $this->dbal()->conn()->real_escape_string($str);
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
                        $editsql = "UPDATE  user SET 
                        `firstname` =  '{$this->escStr($validated_data['firstname'])}',
                        `lastname` =  '{$this->escStr($validated_data['lastname'])}',
                        `gender` =  '{$this->escStr($validated_data['gender'])}',
                        `nationality` =  '{$this->escStr($validated_data['nationality'])}',
                        `telephone` =  '{$this->escStr($validated_data['telephone'])}',
                        `streetaddr` =  '{$this->escStr($validated_data['streetaddr'])}',
                        `city` =  '{$this->escStr($validated_data['city'])}',
                        `region` =  '{$this->escStr($validated_data['region'])}',
                        `country` =  '{$this->escStr($validated_data['country'])}',
                        `postalcode` =  '{$this->escStr($validated_data['postalcode'])}' WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1";

                        if ($this->dbal()->update_database($editsql)) {
                            $return = 1;
                        } else {
                            $return = ""
                                . "We are Sorry; We can not save your update";
                        }
                    } else {
                        $return = "Data Exists!";
                    }
                }
                return $return;
            }
        }
