<?php

class dtv
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
        $pd = json_decode(file_get_contents('php://input'), true);
        if (isset($pd['search']) && strlen($pd['search']) > 0) {
            $rt = '';
            foreach ($fields as $fl) {
                if ($tablename == null) {
                    $rt .= $fl . " like '%{$pd['search']}%' OR ";
                } else {
                    $rt .= $tablename . "." . $fl . " like '%{$pd['search']}%' OR ";
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
        $pd = json_decode(file_get_contents('php://input'), true);
        $orderbyfield = isset($pd['order']) ? $pd['order'] : 'ID';
        $orderbyDir = isset($pd['dir']) ? $pd['dir'] : "desc";

        return $tablename == null ? "ORDER BY {$orderbyfield} {$orderbyDir}" : "ORDER BY {$tablename}.{$orderbyfield} {$orderbyDir}";
    }
}
