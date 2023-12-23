<?php

class dt
{

    public static function fields($arr, $tablename = null)
    {
        $rt = '';
        foreach ($arr as $value) {
            if ($tablename == null) {
                $rt .= "{$value},";
            } else {
                $rt .= "{$tablename}.{$value},";
            }
        }
        return rtrim($rt, ",");
    }

    public static function dt_search($fields, $addcondition = "deleted=0", $tablename = null)
    {
        //print_r($_POST);
        if (isset($_REQUEST['search']['value'])) {
            $rt = '';
            foreach ($_POST['columns'] as $value) {

                if ($value['searchable'] == "true" && $_POST['search']['value']) {
                    if ($tablename == null) {
                        $rt .= $fields[$value['data']] . " like '%{$_POST['search']['value']}%' OR ";
                    } else {
                        $rt .= $tablename . "." . $fields[$value['data']] . " like '%{$_POST['search']['value']}%' OR ";
                    }
                }
            }
            $rtx = rtrim($rt, "OR ");
            //echo $rtx;
        } else {
            $rtx = '';
        }
        //$addcondition=$tablename==null?$addcondition:$addcondition;
        return $rtx ? $rtx . " AND {$addcondition}" : " {$addcondition}";
    }

    public static function orderby($selectdata, $tablename = null)
    {
        $pd = $_POST;
        $orderbyfield = isset($selectdata[$pd['order'][0]['column']]) ? $selectdata[$pd['order'][0]['column']] : 'ID';
        $orderbyDir = isset($pd['order'][0]['dir']) ? $pd['order'][0]['dir'] : "desc";

        return $tablename == null ? "ORDER BY {$orderbyfield} {$orderbyDir}" : "ORDER BY {$tablename}.{$orderbyfield} {$orderbyDir}";
    }
    public static function orderbyV($selectdata, $tablename = null)
    {
        $pd = json_decode(file_get_contents('php://input'), true);
        $orderbyfield = isset($pd['order']) ? $pd['order'] : 'ID';
        $orderbyDir = isset($pd['dir']) ? $pd['dir'] : "desc";

        return $tablename == null ? "ORDER BY {$orderbyfield} {$orderbyDir}" : "ORDER BY {$tablename}.{$orderbyfield} {$orderbyDir}";
    }
}
