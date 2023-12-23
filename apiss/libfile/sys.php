<?php

use kring\database;

class sys {

    public static function mf($paramin = 0, $rowNumber = 0) {
        $param = is_null($paramin) ? 0 : $paramin;
        $kdbal = new database\kdbal();
        $dt = $kdbal->kbiz_data();
        $currencySymble = $dt['currency_symble'];

        if ($rowNumber == 0) {
            if ($param > 0) {
                if ($dt['symble_position'] == 1) {
                    $return = $currencySymble . "" . number_format($param, 2);
                } else {
                    $return = number_format($param, 2) . "" . $currencySymble;
                }
            } else {
                if ($dt['symble_position'] == 1) {
                    $return = '' . $currencySymble . "" . number_format(abs($param), 2) . '';
                } else {
                    $return = '' . number_format(abs($param), 2) . "" . $currencySymble . '';
                }
            }
        } else {
            $return = $paramin;
        }
        return $return;
    }

    public static function nf($param = 0) {
        if ($param > 0) {
            return number_format($param, 2);
        } else {
            return 0;
        }
    }

    public static function df_full($date) {
        return date('l d F Y', strtotime($date));
    }

    public static function df($date) {
        $formatdt = !$date ? "16-12-1971" : $date;
        return date('d-m-Y', strtotime($formatdt));
    }

    public static function save_doc($docname, $doc_content, $doc_type = "inv") {

        $db = new database\dbal();
        return $db->query_exc("INSERT INTO `saved_documents`( `doc_type`, `doc_name`, `doc_content`, `add_date`, `add_by`, `deleted`) VALUES ('{$doc_type}','{$docname}','{$doc_content}',NOW(),'{$_SESSION['UsrID']}','0')");
    }

}
