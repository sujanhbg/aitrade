<?php

use kring\database as db;
use kring\utilities\comm;

class Model_blogs_content
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

    function baseurl()
    {
        return $this->kring()->conf('baseurl');
    }

    //return My sql real escape string
    function escStr($str)
    {
        return $this->dbal()->conn()->real_escape_string($str);
    }

    function getblogs_contentHeader()
    {
        return ['ID', 'categoryID', 'title', 'blog_content', 'short_desc', 'defimage', 'alternative_title', 'keywords', 'publisher', 'date_Created', 'date_Modified', 'date_Published', 'inLanguage', 'isFamilyFriendly', 'copyrightYear', 'genre', 'deleted', 'published'];
    }

    function blogs_contentViewdata()
    {
        return $this->dbal()->query("SELECT 
				`ID`,
				`categoryID`,
				`title`,
				`blog_content`,
				`short_desc`,
				`defimage`,
				`alternative_title`,
				`keywords`,
				`publisher`,
				`date_Created`,
				`date_Modified`,
				`date_Published`,
				`inLanguage`,
				`isFamilyFriendly`,
				`copyrightYear`,
				`genre`,
				`deleted`,
				`published`
                                FROM blogs_content
                                WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }

    function blogs_contentValidationRules()
    {
        return [

            'title' => 'required|min_len,1',
            'blog_content' => 'required|min_len,1',
            'short_desc' => 'required|min_len,1'
        ];
    }

    function blogs_contentValidationMessage()
    {
        return [
            'title' => ['required' => 'Title  is required.', 'min_len' => 'Invalid title'],
            'blog_content' => ['required' => 'Blog Content  is required.', 'min_len' => 'Invalid blog_content'],
            'short_desc' => ['required' => 'Short Desc  is required.', 'min_len' => 'Invalid short_desc']

        ];
    }

    function blogs_contentFilterRules()
    {
        return [
            'ID' => 'trim|sanitize_string|basic_tags',
            'categoryID' => 'trim|sanitize_string|basic_tags',
            'title' => 'trim|sanitize_string|basic_tags',
            'blog_content' => 'trim|sanitize_string|basic_tags',
            'short_desc' => 'trim|sanitize_string|basic_tags',
            'defimage' => 'trim|sanitize_string|basic_tags',
            'alternative_title' => 'trim|sanitize_string|basic_tags',
            'keywords' => 'trim|sanitize_string|basic_tags',
            'publisher' => 'trim|sanitize_string|basic_tags',
            'date_Created' => 'trim|sanitize_string|basic_tags',
            'date_Modified' => 'trim|sanitize_string|basic_tags',
            'date_Published' => 'trim|sanitize_string|basic_tags',
            'inLanguage' => 'trim|sanitize_string|basic_tags',
            'isFamilyFriendly' => 'trim|sanitize_string|basic_tags',
            'copyrightYear' => 'trim|sanitize_string|basic_tags',
            'genre' => 'trim|sanitize_string|basic_tags',
            'deleted' => 'trim|sanitize_string|basic_tags',
            'published' => 'trim|sanitize_string|basic_tags'
        ];
    }

    function datasource()
    {
        $pd = json_decode(file_get_contents('php://input'), true);
        $data['draw'] = isset($pd['draw']) ? $pd['draw'] : 1;
        $start = isset($pd['start']) ? $pd['start'] : 0;
        $length = isset($pd['length']) ? $pd['length'] : 10;
        $table = "blogs_content";
        $selectdata = ['ID', 'title', 'short_desc', 'defimage'];
        $orderby = dtv::orderby($selectdata, $table);
        $search = dtv::dt_search($selectdata, "deleted=0", $table);
        $fields = dtv::fields($selectdata, $table);
        $sql = "SELECT {$fields} FROM blogs_content WHERE {$search} {$orderby}";
        $limit = " LIMIT {$start},{$length}";
        $data['recordsTotal'] = $this->dbal()->num_of_row($sql);
        $data['recordsFiltered'] = $this->dbal()->num_of_row($sql);
        $data['data'] = $this->dbal()->querydt($sql . $limit);
        //print_r($_REQUEST);
        return $data;
    }

    function blogs_content_dbvalid($data)
    {
        $cond = "SELECT ID FROM blogs_content WHERE ";
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

    function blogs_content_CheckValid()
    {

        $gump = new \GUMP();
        $gump->set_fields_error_messages($this->blogs_contentValidationMessage());
        $data = array($_REQUEST['fname'] => $_REQUEST['fval']);
        $validated = $gump->is_valid($data, array_intersect_key($this->blogs_contentValidationRules(), array_flip(array($_REQUEST['fname']))));
        $dbvalid = $this->blogs_content_dbvalid([$_REQUEST['fname'] => $_REQUEST['fval']]);

        if ($validated === true) {
            if ($_REQUEST['fname'] == "email" && $dbvalid == false) {
                $return = "<span style='color:red'><i class='fa fa-times' aria-hidden='true'></i>"
                    . " {$_REQUEST['fval']} already exists</span>";
            } else {
                $return = "<span style='color:green'><i class='fa fa-check-square' aria-hidden='true'></i>"
                    . " Valid!</span>";
            }
        } else {

            $return = "<span style='color:red'><i class='fa fa-times' aria-hidden='true'></i> ";
            $return .= $validated[0] . "</span>";
        }
        echo $return;
    }

    function blogs_contentnew__record_create()
    {
        $gump = new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->blogs_contentValidationRules());
        $gump->filter_rules($this->blogs_contentFilterRules());
        $gump->set_fields_error_messages($this->blogs_contentValidationMessage());
        $validated_data = $gump->run($_POST);
        $dbvalidation = null;
        //if($this->check_exits("students", "title={$validated_data['title']}")){$dbvalidation.="Data Already Exits";}
        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            if ($dbvalidation == null) {
                //$return= $validated_data['cellnumber'];
                $insertsql = "INSERT INTO  `blogs_content` (
`categoryID`,
`title`,
`blog_content`,
`short_desc`,
`defimage`,
`alternative_title`,
`keywords`,
`publisher`,
`date_Created`,
`date_Modified`)
            VALUES
            (
'1',
'{$this->escStr($validated_data['title'])}',
'{$this->escStr($validated_data['blog_content'])}',
'{$this->escStr($validated_data['short_desc'])}',
'{$this->escStr($validated_data['defimage'])}',
'{$this->escStr($validated_data['alternative_title'])}',
'{$this->escStr($validated_data['keywords'])}',
'{$this->escStr($validated_data['publisher'])}',
 NOW(),
 NOW());";

                if ($this->dbal()->query_exc($insertsql)) {
                    $return = 1;
                    /*
                      $this->dbal()->editLog("blogs_content", "ID", $this->escStr($validated_data['ID']));
                      $this->dbal()->editLog("blogs_content", "categoryID", $this->escStr($validated_data['categoryID']));
                      $this->dbal()->editLog("blogs_content", "title", $this->escStr($validated_data['title']));
                      $this->dbal()->editLog("blogs_content", "blog_content", $this->escStr($validated_data['blog_content']));
                      $this->dbal()->editLog("blogs_content", "short_desc", $this->escStr($validated_data['short_desc']));
                      $this->dbal()->editLog("blogs_content", "defimage", $this->escStr($validated_data['defimage']));
                      $this->dbal()->editLog("blogs_content", "alternative_title", $this->escStr($validated_data['alternative_title']));
                      $this->dbal()->editLog("blogs_content", "keywords", $this->escStr($validated_data['keywords']));
                      $this->dbal()->editLog("blogs_content", "publisher", $this->escStr($validated_data['publisher']));
                      $this->dbal()->editLog("blogs_content", "date_Created", $this->escStr($validated_data['date_Created']));
                      $this->dbal()->editLog("blogs_content", "date_Modified", $this->escStr($validated_data['date_Modified']));
                      $this->dbal()->editLog("blogs_content", "date_Published", $this->escStr($validated_data['date_Published']));
                      $this->dbal()->editLog("blogs_content", "inLanguage", $this->escStr($validated_data['inLanguage']));
                      $this->dbal()->editLog("blogs_content", "isFamilyFriendly", $this->escStr($validated_data['isFamilyFriendly']));
                      $this->dbal()->editLog("blogs_content", "copyrightYear", $this->escStr($validated_data['copyrightYear']));
                      $this->dbal()->editLog("blogs_content", "genre", $this->escStr($validated_data['genre']));
                      $this->dbal()->editLog("blogs_content", "deleted", $this->escStr($validated_data['deleted']));
                      $this->dbal()->editLog("blogs_content", "published", $this->escStr($validated_data['published']));

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

    function get_blogs_contentEditData()
    {
        return $this->dbal()->query("SELECT * FROM blogs_content WHERE `ID`='{$this->comm()->rqstr('ID')}' LIMIT 1");
    }

    function blogs_contentedited_data_save()
    {
        $gump = new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->blogs_contentValidationRules());
        $gump->filter_rules($this->blogs_contentFilterRules());
        $gump->set_fields_error_messages($this->blogs_contentValidationMessage());
        $validated_data = $gump->run($_POST);

        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            $dbvalidation = true; //$this->blogs_content_dbvalid(['email' => $validated_data['email'], 'cell' => $validated_data['cell']]);
            if ($dbvalidation == true) {
                //$return= $validated_data['cellnumber'];
                $editsql = "UPDATE  blogs_content SET 
				`title` =  '{$this->escStr($validated_data['title'])}',
				`blog_content` =  '{$this->escStr($validated_data['blog_content'])}',
				`short_desc` =  '{$this->escStr($validated_data['short_desc'])}',
				`defimage` =  '{$this->escStr($validated_data['defimage'])}',
				`alternative_title` =  '{$this->escStr($validated_data['alternative_title'])}',
				`keywords` =  '{$this->escStr($validated_data['keywords'])}',
				`publisher` =  '{$this->escStr($validated_data['publisher'])}',
				`date_Modified` =  NOW() WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1";

                if ($this->dbal()->update_database($editsql)) {
                    $return = 1;
                    /*
                      $this->dbal()->editLog("blogs_content", "ID", $this->escStr($validated_data['ID']));
                      $this->dbal()->editLog("blogs_content", "categoryID", $this->escStr($validated_data['categoryID']));
                      $this->dbal()->editLog("blogs_content", "title", $this->escStr($validated_data['title']));
                      $this->dbal()->editLog("blogs_content", "blog_content", $this->escStr($validated_data['blog_content']));
                      $this->dbal()->editLog("blogs_content", "short_desc", $this->escStr($validated_data['short_desc']));
                      $this->dbal()->editLog("blogs_content", "defimage", $this->escStr($validated_data['defimage']));
                      $this->dbal()->editLog("blogs_content", "alternative_title", $this->escStr($validated_data['alternative_title']));
                      $this->dbal()->editLog("blogs_content", "keywords", $this->escStr($validated_data['keywords']));
                      $this->dbal()->editLog("blogs_content", "publisher", $this->escStr($validated_data['publisher']));
                      $this->dbal()->editLog("blogs_content", "date_Created", $this->escStr($validated_data['date_Created']));
                      $this->dbal()->editLog("blogs_content", "date_Modified", $this->escStr($validated_data['date_Modified']));
                      $this->dbal()->editLog("blogs_content", "date_Published", $this->escStr($validated_data['date_Published']));
                      $this->dbal()->editLog("blogs_content", "inLanguage", $this->escStr($validated_data['inLanguage']));
                      $this->dbal()->editLog("blogs_content", "isFamilyFriendly", $this->escStr($validated_data['isFamilyFriendly']));
                      $this->dbal()->editLog("blogs_content", "copyrightYear", $this->escStr($validated_data['copyrightYear']));
                      $this->dbal()->editLog("blogs_content", "genre", $this->escStr($validated_data['genre']));
                      $this->dbal()->editLog("blogs_content", "deleted", $this->escStr($validated_data['deleted']));
                      $this->dbal()->editLog("blogs_content", "published", $this->escStr($validated_data['published']));

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
        return $this->dbal()->query("SELECT * FROM `blogs_content` WHERE `deleted`=0");
    }

    function blogs_contentDeleteSql()
    {
        return $this->dbal()->query_exc("UPDATE  blogs_content SET `deleted` =  '1'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }

    function blogs_contentRestoreSql()
    {
        return $this->dbal()->query_exc("UPDATE  blogs_content SET `deleted` =  '0'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }
}
