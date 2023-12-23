<?php

namespace kring\database;

/*
 * It need to define the file location with same format
 */

use NilPortugues\Sql\QueryBuilder\Builder\MySqlBuilder;
use kring\core;

/*
 * The MIT License
 *
 * Copyright 2021 sjnx.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

class kdbal
{

    function appname()
    {
        return explode(".", $_SERVER['SERVER_NAME'])[0];
    }

    function qb()
    {
        return new MySqlBuilder();
    }

    function dbalv()
    {
        return "Version 1.0.0";
    }

    function conn()
    {
        $kring = new core\Kring();
        return new \mysqli(
            $kring->dbconf('host'),
            $kring->dbconf('user'),
            $kring->dbconf('password'),
            'kringlab_main'
        );
    }

    function query($qry)
    {
        $mysqli = $this->conn();
        $result = $mysqli->query($qry);
        if (!$mysqli->query($qry)) {
            echo ("Error in Query:: <i><u>$qry</u></i> " . $mysqli->error);
        }
        $returnArray = array();
        $i = 0;
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
            if ($row)
                $returnArray[$i++] = $row;
        return $returnArray;
    }

    function get($table, $fields = "*", $where = null)
    {
        $mysqli = $this->conn();
        $wheresql = !$where || $where == null ? "" : "WHERE " . $where;
        $qry = "SELECT {$fields} FROM {$table} {$wheresql}";
        $result = $mysqli->query($qry);
        if (!$mysqli->query($qry)) {
            echo ("Error in Query:: <i><u>$qry</u></i> " . $mysqli->error);
        }
        $returnArray = array();
        $i = 0;
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
            if ($row)
                $returnArray[$i++] = $row;
        return $returnArray;
    }

    function get_single_result($sql)
    {
        $mysqli = $this->conn();
        $result = $mysqli->query($sql);
        $value = $result->fetch_array(MYSQLI_NUM);
        return is_array($value) ? $value[0] : "";
    }

    function get_single_row($sql)
    {
        foreach ($this->query($sql) as $value) {
            return $value;
        }
    }

    function query_exc($qry)
    {

        $mysqli = $this->conn();
        $result = $mysqli->query($qry);
        if (!$result) {
            echo ("Error in Query:: <i><u>$qry</u></i> " . $mysqli->error);
        }
        if ($result) {
            return $mysqli->insert_id;
        } else {
            return false;
        }
    }

    function get_current_db()
    {
        $sql = "SELECT DATABASE();";
        return $this->get_single_result($sql);
    }

    function get_count($table, $colmn = "ID", $condition = null)
    {
        $where = isset($condition) ? "WHERE $condition" : null;
        return $this->query("SELECT COUNT('{$colmn}') as num FROM `{$table}` {$where};")[0]['num'];
        //print_r($return[0]['num']);
    }

    function num_of_row($qry)
    {
        $mysqli = $this->conn();
        if (isset($qry)) {
            $result = $mysqli->query($qry);
            if (!$result) {
                echo ("Error in Query:: <i><b style=\"text-color:red;\">$qry</b></i> " . $mysqli->error);
            }
            $temp = $result->num_rows;
        } else {
            $temp = null;
        }
        return $temp;
    }

    function update_database($sql)
    {
        $mysqli = $this->conn();
        $result = $mysqli->query($sql);
        if ($result) {
            return true;
        } else {
            print_r($mysqli->error);
            return FALSE;
        }
    }

    function db_switch_val($table, $coloumn, $ID)
    {
        $sqlx = "SELECT `{$table}`.`{$coloumn}` FROM `{$table}` WHERE `{$table}`.`ID`={$ID} LIMIT 1";
        $val = $this->get_single_result($sqlx);
        if ($val == 1) {
            $getcols = "`{$table}`." . $coloumn . "=0";
        } else {
            $getcols = "`{$table}`." . $coloumn . "=1";
        }

        $query = "UPDATE `{$table}` SET $getcols WHERE `{$table}`.`ID`={$ID} LIMIT 1;";
        return $this->update_database($query);
    }

    function insert($table, $data, $outsql = false)
    {
        $sql = "INSERT INTO `{$table}` ";
        $keys = "(";
        $vals = "(";
        foreach ($data as $key => $value) {
            $keys .= "`{$key}`,";
            $vals .= "'" . mysqli_real_escape_string($this->conn(), $value) . "',";
        }
        $keys = rtrim($keys, ",") . ") VALUES ";
        $vals = rtrim($vals, ",") . ");";
        if ($outsql == 1) {
            return $sql . $keys . $vals;
        } else {
            return $this->query_exc($sql . $keys . $vals);
        }
    }

    function update($table, $data, $where, $outsql = false)
    {
        $sql = "UPDATE `{$table}` SET ";
        $val = null;
        foreach ($data as $key => $value) {
            $val .= "`{$key}`='" . mysqli_real_escape_string($this->conn(), $value) . "',";
        }
        foreach ($where as $keya => $valuea) {
            $keyret = "`{$keya}`" . "= '" . $valuea . "'";
        }
        $val = rtrim($val, ",");
        $sql .= $val . " WHERE " . $keyret . " LIMIT 1;";
        if ($outsql == 1) {
            return $sql;
        } else {
            return $this->update_database($sql);
        }
    }

    function kbiz_data()
    {
        $app = $this->appname();
        $qry = "SELECT `ID`, `userid`, `appname`, `subscription_date`, `expire_date`, `company_name`, `phone_number`, `email_address`, `company_address`, `currency`, `currency_symble`, `symble_position`, `country`, `footer_text`, `vat_registration_number`, `logo_url`, `icon_url`, `custom_domain`, `domain_active`,`package`,`ecommerce`,`template`,`site_title`,`metadesc`,`main_image`,`img_alt`,`metakeywords`,`mejor_application`,`show_appointment_in_inv`,`show_age`,`offline`,`offline_html` FROM `kbiz_subscription` WHERE `appname`='{$app}' LIMIT 1";
        return $this->query($qry)[0];
    }
    function conf($conf_name)
    {
        $app = $this->appname();
        return $this->get_single_result("SELECT {$conf_name} FROM `kbiz_subscription` WHERE `appname`='{$app}' LIMIT 1 ");
    }
    function kbiz_status()
    {
        return $this->get_single_result("SELECT `status` FROM `kbiz_subscription` WHERE `appname`='{$this->appname()}';");
    }

    function make_accounting_database()
    {
        return 0;
    }

    function jid($trdate, $trdesc)
    {
        if ($trdate == 'NOW' || $trdate == "NOW()") {
            return $this->query_exc("INSERT INTO `acc_journal`(`transection_date`, `tr_desc`, `deleted`, `entry_by`) VALUES (NOW(),'{$trdesc}','0','{$_SESSION['UsrID']}')");
        } else {
            return $this->query_exc("INSERT INTO `acc_journal`(`transection_date`, `tr_desc`, `deleted`, `entry_by`) VALUES ('{$trdate}','{$trdesc}','0','{$_SESSION['UsrID']}')");
        }
    }

    function add_journal($jid, $accountCode, $amount)
    {
        if ($amount > 0) {
            $this->query_exc("INSERT INTO `acc_journal_data`(`jid`, `account_code`, `amount`, `tr_type`, `deleted`) VALUES ('{$jid}','{$accountCode}','{$amount}','1','0')");
            //ledger Entry for Dr account
            $drcodebalance = $this->get_single_result("SELECT SUM(`amount`) AS amt FROM `acc_ledger_book` WHERE `acc_code`={$accountCode} AND `deleted`=0");
            $this->query_exc("INSERT INTO `acc_ledger_book`(`acc_code`, `tr_date`, `tr_desc`, `amount`, `current_balance`, `deleted`) VALUES ('{$accountCode}',NOW(),'JID: {$jid}','{$amount}','{$drcodebalance}','0')");
        } else {
            $amout = abs($amount);
            $this->query_exc("INSERT INTO `acc_journal_data`(`jid`, `account_code`, `amount`, `tr_type`, `deleted`) VALUES ('{$jid}','{$accountCode}','{$amout}','2','0')");
            //entry for ledger book for credit account
            $crcodebalance = $this->get_single_result("SELECT SUM(`amount`) AS amt FROM `acc_ledger_book` WHERE `acc_code`={$accountCode} AND `deleted`=0");
            $this->query_exc("INSERT INTO `acc_ledger_book`(`acc_code`, `tr_date`, `tr_desc`, `amount`, `current_balance`, `deleted`) VALUES ('{$accountCode}',NOW(),'JID: {$jid}','{$amout}','{$crcodebalance}','0')");
        }
    }

    //---------------------------------------User Usages
    function insert_log()
    {
        $hostname = explode(".", $_SERVER['SERVER_NAME'])[0];
        $appid = $this->get_single_result("SELECT ID FROM `kbiz_subscription` WHERE `appname`='{$hostname}' ");
        if ($hostname == "phplab") {
        } else {

            $memory = memory_get_usage() / 1024 / 1024 / 1024;
            //DATE(`timestamp`) = CURDATE()
            $userid = isset($_SESSION['UsrID']) ? $_SESSION['UsrID'] : 0;
            if ($this->num_of_row("SELECT * FROM `kbiz_usages` WHERE DATE(`rdate`) = CURDATE() AND `appid`={$appid} AND `userid`='{$userid}'")) {
                $this->update_database("UPDATE `kbiz_usages` SET `ramusages`=ramusages+{$memory} WHERE DATE(`rdate`) = CURDATE() AND `appid`={$appid} AND `userid`='{$userid}'");
            } else {
                $this->query_exc("INSERT INTO `kbiz_usages`(`appid`, `rdate`, `userid`, `ramusages`) VALUES ('{$appid}',NOW(),'{$userid}','{$memory}')");
            }
        }


        //echo "INSERT INTO `kbiz_usages`(`appid`, `rdate`, `userid`, `ramusages`) VALUES ('{$appid}',NOW(),'{$_SESSION['UsrID']}','{$memory}'\n";
        //echo "        SELECT ID FROM `kbiz_subscription` WHERE `appname`='{$hostname}'";
    }

    function querydt($qry, $showquery = 0)
    {
        if ($showquery == 1) {
            exit($qry);
        }
        $mysqli = $this->conn();
        $result = $mysqli->query($qry);
        if (!$mysqli->query($qry)) {
            echo ("Error in Query:: <i><u>$qry</u></i> " . $mysqli->error);
        }
        $returnArray = array();
        $i = 0;
        while ($row = $result->fetch_row())
            if ($row)
                $returnArray[$i++] = $row;
        return $returnArray;
    }
}
