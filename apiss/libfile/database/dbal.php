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

class dbal
{

    function qb()
    {
        return new MySqlBuilder();
    }
    function jwt()
    {
        return new \kring\auth\jwt;
    }
    function dbalv()
    {
        return "Version 1.0.0";
    }

    function dbconfig()
    {
        $kring = new core\Kring();
        return array(
            'user' => $kring->dbconf('user'),
            'pass' => $kring->dbconf('password'),
            'db' => $kring->dbconf('database'),
            'host' => $kring->dbconf('host')
        );
    }
    function info()
    {
        $kring = new core\Kring();
        return ['database version' => 'MySql', 'selected database' => $kring->dbconf('database')];
    }
    function conn()
    {
        $kring = new core\Kring();
        return new \mysqli(
            $kring->dbconf('host'),
            $kring->dbconf('user'),
            $kring->dbconf('password'),
            $kring->dbconf('database')
        );
    }

    function query($qry, $viewsql = 0)
    {
        if ($viewsql == 1) {
            exit($qry);
        }
        $mysqli = $this->conn();
        $result = $mysqli->query($qry);
        if (!$result) {
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
        //echo $sql;
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

    function dbtable_exists($tablename)
    {
        if ($this->num_of_row("SHOW TABLES LIKE '%{$tablename}%';")) {
            $return = true;
        } else {
            $return = false;
        }
        return $return;
    }

    function add_table_notice()
    {
        $this->conn()->multi_query('SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `notice` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `notice_to` int(11) NOT NULL,
  `notice_body` text NOT NULL,
  `readed` int(1) NOT NULL DEFAULT 0,
  `sent_time` datetime DEFAULT NULL,
  `read_time` datetime DEFAULT NULL,
  `sent_from` varchar(255) NOT NULL DEFAULT \'system\',
  `deleted` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;');
    }

    function notice($adminid, $noticebody)
    {
        if (!$this->dbtable_exists('notie')) {
            $this->add_table_notice();
        }

        $addsql = "INSERT INTO `notice`( `notice_to`, `notice_body`, `readed`, `sent_time`, `read_time`, `sent_from`) VALUES ('{$adminid}','{$noticebody}',0,NOW(),NULL,'system');";
        $this->query_exc($addsql);
    }

    function notice_to_admin($noticebody)
    {
        if ($_SESSION["UsrRole"] != 22) {
            foreach ($this->query("SELECT `ID` FROM `user` WHERE `role`=22 AND `deleted`=0;") as $adm) {
                $this->notice($adm['ID'], $noticebody);
            }
        }
    }

    function make_accounting_database()
    {
    }

    function jid($trdate, $trdesc)
    {
        $userID = !isset($_SESSION['UsrID']) ? $this->jwt()->juid() : $_SESSION['UsrID'];
        //echo "INSERT INTO `acc_journal`(`transection_date`, `tr_desc`, `deleted`, `entry_by`) VALUES ('{$trdate}','{$trdesc}','0','{$userID}')";

        if ($trdate == 'NOW' || $trdate == "NOW()") {
            return $this->query_exc("INSERT INTO `acc_journal`(`transection_date`, `tr_desc`, `deleted`, `entry_by`) VALUES (NOW(),'{$trdesc}','0','{$userID}')");
        } else {
            return $this->query_exc("INSERT INTO `acc_journal`(`transection_date`, `tr_desc`, `deleted`, `entry_by`) VALUES ('{$trdate}','{$trdesc}','0','{$userID}')");
        }
        //$this->update_jdata();
    }

    function add_journal($jid, $accountCode, $amount)
    {
        $trdate = $this->get_single_result("SELECT `transection_date` FROM `acc_journal` WHERE `ID`={$jid}");
        $balance = $this->get_single_result("SELECT SUM(amount) as amm FROM acc_ledger_book WHERE acc_code={$accountCode}") + $amount;
        $this->query_exc("INSERT INTO `acc_ledger_book`( `acc_code`, `tr_date`, `tr_desc`, `amount`, `current_balance`) VALUES ('{$accountCode}','{$trdate}','{$jid}',{$amount},{$balance})");
        if ($amount > 0) {
            $this->query_exc("INSERT INTO `acc_journal_data`(`jid`, `account_code`, `amount`, `tr_type`, `deleted`, `trd`) VALUES ('{$jid}','{$accountCode}','{$amount}','1','0','{$trdate}')");
        } else {
            $amout = abs($amount);
            $this->query_exc("INSERT INTO `acc_journal_data`(`jid`, `account_code`, `amount`, `tr_type`, `deleted`,`trd`) VALUES ('{$jid}','{$accountCode}','{$amout}','2','0','{$trdate}')");
        }
    }

    function update_jdata()
    {
        //foreach ($this->query("SELECT * FROM `acc_journal`") as $value) {
        //$this->update_database("UPDATE `acc_journal_data` SET `trd`='{$value['transection_date']}' WHERE `jid`={$value['ID']}");
        //}
    }

    //---------------------------------------------Datatable methods
    //Query for Data Table
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
