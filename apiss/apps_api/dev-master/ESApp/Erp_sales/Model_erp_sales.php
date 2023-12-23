<?php

use kring\database as db;
use kring\utilities\comm;

class Model_erp_sales
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
    function jwt()
    {
        return new \kring\auth\jwt;
    }
    function escStr($str)
    {
        return $this->dbal()->conn()->real_escape_string($str);
    }

    function get_erp_salesEditData()
    {
        /* echo "SELECT
          `erp_sales`.*,
          `customers`.`customer_name`,
          `customers`.`contact_number`,
          `customers`.`company_name`,
          `customers`.`contact_address`,
          `user`.`firstname`,
          `user`.`lastname`,
          `user`.`cell`
          FROM
          `erp_sales`
          INNER JOIN `customers` ON `erp_sales`.`customer_id` = `customers`.`ID`
          INNER JOIN `user` ON `erp_sales`.`employee_id` = `user`.`ID`
          WHERE
          `erp_sales`.`ID` = '{$this->get_sid()}'
          LIMIT 1";
          exit(); */
        return $this->dbal()->query("SELECT
    `erp_sales`.*,
    `customers`.`customer_name`,
    `customers`.`contact_number` as customer_contact,
    `customers`.`company_name`,
    `customers`.`contact_address`,
    `customers`.`email_address` as customer_email,
    `user`.`firstname`,
    `user`.`lastname`,
    `user`.`cell`
FROM
    `erp_sales`
INNER JOIN `customers` ON `erp_sales`.`customer_id` = `customers`.`ID`
INNER JOIN `user` ON `erp_sales`.`employee_id` = `user`.`ID`
WHERE
    `erp_sales`.`ID` = '{$this->get_sid()}'
LIMIT 1");
    }

    function erp_salesViewdata()
    {
        return $this->dbal()->query("SELECT 
				`ID`,
				`sales_date`,
				`customer_id`,
				`employee_id`,
				`comment`,
				`deleted`,
				`status`,
				`totalamount`,
				`online`,
				`market_place`,
				`page_name`,
				`delivery_charge`,
				`dc_paid`,
				`shipping_address`,
				`shipping_note`
                                FROM erp_sales
                                WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }

    function datasource()
    {
        $pd = json_decode(file_get_contents('php://input'), true);
        $data['draw'] = isset($pd['draw']) ? $pd['draw'] : 1;
        $start = isset($pd['start']) ? $pd['start'] : 0;
        $length = isset($pd['length']) ? $pd['length'] : 10;
        $table = "erp_sales";
        $selectdata = ['ID', 'sales_date', 'customer_id', 'employee_id', 'comment', 'deleted', 'status', 'totalamount', 'online', 'market_place', 'page_name', 'delivery_charge', 'dc_paid', 'shipping_address', 'shipping_note'];
        $orderby = dtv::orderby($selectdata, $table);
        $search = dtv::dt_search($selectdata, "deleted=0 AND status=3", $table);
        $fields = dtv::fields($selectdata, $table);
        $sql = "SELECT {$fields} FROM erp_sales WHERE {$search} {$orderby}";
        $limit = " LIMIT {$start},{$length}";
        $data['recordsTotal'] = $this->dbal()->num_of_row($sql);
        $data['recordsFiltered'] = $this->dbal()->num_of_row($sql);
        $data['data'] = $this->dbal()->querydt($sql . $limit);
        return $data;
    }
    function get_autoData()
    {
        $sval = $this->comm()->rqstr('sval');
        if (strlen($sval) > 0) {
            return $this->dbal()->query("SELECT `products`.`ID`,`products`.`product_name`,(SELECT SUM(`erp_inventory`.`tr_qty`) FROM `erp_inventory` WHERE `erp_inventory`.`item_id`=`products`.`ID` ) AS `stock` FROM `products` WHERE `products`. `ID` LIKE '%{$sval}%' OR `products`.`product_name` LIKE '%{$sval}%' OR `products`.`barcode` LIKE '%{$sval}%' AND `products`.`deleted`=0 LIMIT 15");
        } else {
            return [];
        }
    }
    function get_sid()
    {
        if (isset($_REQUEST['sid'])) {
            $return = $this->comm()->filtertxt($_REQUEST['sid']);
        } else {
            if ($this->dbal()->num_of_row("SELECT * FROM `erp_sales` WHERE `employee_id`={$this->jwt()->juid()} AND `status`=0")) {
                $return = $this->dbal()->get_single_result("SELECT `ID` FROM `erp_sales` WHERE `employee_id`={$this->jwt()->juid()} AND `status`=0");
            } else {
                $return = $this->dbal()->query_exc("INSERT INTO `erp_sales`(`sales_date`,`employee_id`,`customer_id`) VALUES (CURDATE(),'{$this->jwt()->juid()}',1)");
            }
        }
        return $return;
    }
    function get_itemIdByBarcode($code)
    {
        return $this->dbal()->get_single_result("SELECT `ID` FROM `products` WHERE `barcode`='{$code}' OR `ID`={$code}");
    }
    function add_to_cart()
    {
        $sid = $this->get_sid();
        $itemid = isset($_GET['barcode']) ? $this->get_itemIdByBarcode($this->comm()->rqstr('pid')) : $this->comm()->rqstr('pid');
        return $this->item_add_to_cart($itemid);
    }
    function item_add_to_cart($itemid, $qty = 1)
    {
        $sid = $this->get_sid();
        if ($this->inventory($itemid) > 0) {
            $itm = $this->dbal()->query("SELECT `sale_price`,`discounted_price`,`vat` FROM `products` WHERE `ID`=$itemid")[0];
            $itmName = $this->dbal()->get_single_result("SELECT `product_name` FROM `products` WHERE `ID`={$itemid}");
            $salesprice = $itm['sale_price'];
            $discounted_price = $itm['discounted_price'];
            $vat = $itm['vat'];

            $discount = $discounted_price - $salesprice;
            $discount_percent = abs($discount / $salesprice * 100);
            if ($this->dbal()->num_of_row("SELECT `ID` FROM `erp_sales_items` WHERE `sid`={$sid} AND `product_id`={$itemid}")) {
                if (intval($this->added_qty($sid, $itemid)) < $this->inventory($itemid)) {
                    $this->dbal()->update_database("UPDATE `erp_sales_items` SET `quantity`=`quantity`+1 WHERE `sid`={$sid} AND `product_id`={$itemid}");
                    return ["error" => false, "msg" => "More 1 '{$itmName}' added "];
                } else {
                    return ["error" => true, "msg" => "You can not sale item [{$itemid}] more then " . $this->inventory($itemid) . ";"];
                }
            } else {

                $this->dbal()->query_exc("INSERT INTO `erp_sales_items`(
    `sid`,
    `product_id`,
    `sales_price`,
    `unit_price`,
    `discount`,
    `vat`,
    `quantity`
)
VALUES(
    '{$sid}',
    '{$itemid}',
    '{$salesprice}',
    '{$salesprice}',
    '{$discount_percent}',
    '{$vat}',
    '{$qty}'
)");
                return ["error" => false, "msg" => "Item '{$itmName}' Added Success"];
            }
        } else {
            return ["error" => true, "msg" => "Item in Stock: {$this->inventory($itemid)}; Please Check your Inventory!"];
        }
    }

    function get_total_amount_on_invoice($inv)
    {
        $return = 0;
        foreach ($this->dbal()->query("SELECT * FROM `erp_sales_items` WHERE `sid`={$inv}") as $value) {
            $sales_price = $value['sales_price'];
            $discount = $value['discount'];
            $vat = $value['vat'];
            $quantity = $value['quantity'];
            $discounted_Price = $sales_price - ($sales_price * ($discount / 100));
            $vat_amount = $discounted_Price * ($vat / 100);
            $total = $discounted_Price + $vat_amount;
            $return += $total * $quantity;
        }
        return $return;
    }

    function get_sales_items($sid = null)
    {
        if ($sid == null) {
            $sidd = $this->get_sid();
        } else {
            $sidd = $sid;
        }
        return $this->dbal()->query("SELECT `erp_sales_items`.*,"
            . "`products`.`product_name`,`products`.`product_description`,`products`.`brand_name`,`products`.`cf1`,`products`.`cf2`,`products`.`cf3`,`products`.`cf4`,`products`.`cf5`,`products`.`cf6`,`products`.`cf7`,`products`.`cf8`,`products`.`cf9`,`products`.`cf10`,`products`.`has_sl`,`products`.`has_warranty`,`products`.`def_warranty_perion`,"
            . "(SELECT SUM(`erp_inventory`.`tr_qty`) FROM `erp_inventory` WHERE `erp_inventory`.`item_id`=`erp_sales_items`.`product_id` ) AS `stock` "
            . "FROM `erp_sales_items` "
            . "INNER JOIN `products` ON `erp_sales_items`.`product_id`=`products`.`ID` "
            . "WHERE `sid`={$sidd} ORDER BY `erp_sales_items`.`ID` DESC");
    }

    function get_other_info()
    {
        $return['total'] = $this->dbal()->get_single_result("SELECT SUM(sales_price*quantity) AS rss FROM `erp_sales_items` WHERE `sid`={$this->get_sid()}");

        return $return;
    }

    function category_list()
    {
        return $this->dbal()->query("SELECT `ID`,`subfor`,`category_name` FROM `product_catagory` WHERE `deleted`=0");
    }

    function brand_list()
    {
        return $this->dbal()->query("SELECT `ID`,`brand_name` FROM `product_brand` WHERE `deleted`=0");
    }

    function item_list()
    {
        $ctg = $this->comm()->get('ctg');
        $ctgsearch = $ctg == "0" ? "" : "`category`='{$ctg}' AND ";
        return $this->dbal()->query("SELECT `ID`,`product_name`,`pic_url` "
            . ",(SELECT SUM(`erp_inventory`.`tr_qty`) FROM `erp_inventory` WHERE `erp_inventory`.`item_id`=`products`.`ID` ) AS `stock` "
            . " FROM `products` WHERE {$ctgsearch} `deleted`=0 LIMIT 100");
    }

    function item_list_brand()
    {
        $bnd = $this->comm()->get('bnd');
        $bndsearch = $bnd == "0" ? "" : "`brand_name`='{$bnd}' AND";
        return $this->dbal()->query("SELECT `ID`,`product_name`,`pic_url` "
            . ",(SELECT SUM(`erp_inventory`.`tr_qty`) FROM `erp_inventory` WHERE `erp_inventory`.`item_id`=`products`.`ID` ) AS `stock` "
            . " FROM `products` WHERE {$bndsearch} `deleted`=0 LIMIT 100");
    }

    function get_customer_autoC()
    {
        $term = $this->comm()->rqstr('term');
        return $this->dbal()->query("SELECT `ID`,`customer_name`,`contact_number` FROM `customers` WHERE `ID` LIKE '%{$term}%' OR `customer_name` LIKE '%{$term}%' OR `contact_number` LIKE '%{$term}%' AND `deleted`=0 LIMIT 5");
    }
    function added_qty($sid, $itemid)
    {
        return $this->dbal()->get_single_result("SELECT SUM(quantity) FROM `erp_sales_items` WHERE `sid`={$sid} AND `product_id`={$itemid}");
    }

    function add_customer()
    {
        return $this->dbal()->update_database("UPDATE
                `erp_sales`
            SET
                `customer_id` = '{$_REQUEST['cid']}'
            WHERE
                ID={$this->get_sid()}");
    }

    function update_items()
    {
        $qty = $_POST['quantity'];
        $sid = $this->get_sid();
        $itemid = $_POST['product_id'];
        //echo $this->added_qty($sid, $itemid) . " " . $this->inventory($itemid);
        if ($qty < 0) {
            return "Negative values are not permitted;";
        } elseif (intval($qty) > intval($this->inventory($itemid))) {
            return "You can not sale item more then " . intval($this->inventory($itemid));
        } else {
            //`sl`,`waranty`,`w_start_date`,`w_end_date`
            $sl = isset($_POST['sl']) ? $_POST['sl'] : null;
            $waranty = isset($_POST['wa']) ? $_POST['wa'] : null;
            $w_start_date = isset($_POST['sdt']) ? $_POST['sdt'] :  date('Y-m-d h:i:s');
            $w_end_date = isset($_POST['edt']) ? $_POST['edt'] : date('Y-m-d h:i:s');
            $this->dbal()->update_database("UPDATE
    `erp_sales_items`
SET
    `quantity` = '$qty',
    `sl`='{$sl}',
    `waranty`='{$waranty}',
    `w_start_date`='{$w_start_date}',
    `w_end_date`='{$w_end_date}'
WHERE
    ID={$this->comm()->filtertxt($_POST['ID'])}");
            return 1;
        }
    }

    function remove_from_cart()
    {
        //echo "DELETE FROM `erp_sales_items` WHERE `ID`={$this->comm()->rqstr("pid")}";
        return $this->dbal()->query_exc("DELETE FROM `erp_sales_items` WHERE `ID`={$this->comm()->rqstr("pid")}") ? 1 : 0;
    }

    function inventory($item_id)
    {
        return $this->dbal()->get_single_result("SELECT SUM(`tr_qty`) AS qty FROM `erp_inventory` WHERE `item_id`={$item_id} AND `deleted`=0");
    }

    function info_vat($sid = null)
    {
        $sidd = $sid == null ? $this->get_sid() : $sid;
        return $this->dbal()->get_single_result("SELECT SUM((`sales_price`-(`sales_price`*(`discount`/100)))*(`vat`/100)*`quantity`) AS totalvat FROM `erp_sales_items` WHERE `sid`={$sidd}");
    }

    function info_discount()
    {
        return $this->dbal()->get_single_row("SELECT SUM(`sales_price`*(`discount`/100)*`quantity`) AS v FROM `erp_sales_items` WHERE `sid`={$this->get_sid()}");
    }

    function info_payments()
    {
        return $this->dbal()->query("SELECT erp_sales_payment.*,
                        `acc_accounts`.`account_name` as method_name
                         FROM `erp_sales_payment` 
                         INNER JOIN `acc_accounts` ON `erp_sales_payment`.`method`=`acc_accounts`.`code`
                         WHERE `erp_sales_payment`.`sid`={$this->get_sid()}");
    }

    function get_paid_amount()
    {
        return $this->dbal()->get_single_result("SELECT SUM(`amount`) FROM `erp_sales_payment` WHERE `sid`={$this->get_sid()};");
    }

    function get_total_amount()
    {
        return $this->dbal()->get_single_result("SELECT SUM((( `sales_price`-(`sales_price` * `discount` / 100) ) * `quantity`)+(( `sales_price`-(`sales_price` * `discount` / 100) ) * `quantity`)*`vat`/100) AS total FROM `erp_sales_items` WHERE `sid`={$this->get_sid()};");
    }

    function get_methodSelectData()
    {
        return $this->dbal()->query('SELECT `account_code`,`method_name` FROM acc_payment_method WHERE `deleted`=0 AND `type`=1 OR `type`=2');
    }

    function add_payment()
    {
        $amount = $_POST['amount'];
        $salesdata = $this->get_erp_salesEditData()[0];
        $invoiceAmout = floatval($this->get_total_amount());
        if ($amount == 0) {
            return "0 Amount can't be recorded";
        } elseif ($salesdata['customer_id'] == 1 && $_POST['method'] == 105) {
            return "Please Select Customer";
        } elseif ($_POST['method'] == "balance" && $salesdata['customer_id'] != 1 && $amount > abs(floatval($this->acc_balance("105" . $salesdata['customer_id'])))) {
            return "Customer balance is less then Total Amount";
        } else {

            $due = $this->get_total_amount() - $this->get_paid_amount();
            if ($amount < 0) {
                $tendAm = 0;
                $payamt = $amount;
            } elseif ($amount > $due) {
                $tendAm = $amount - $due;
                $payamt = $due;
            } else {
                $tendAm = 0;
                $payamt = $amount;
            }
            if ($_POST['method'] == "balance" && $salesdata['customer_id'] != 1) {
                $method = "105" . $salesdata['customer_id'];
            } else {
                $method = $_POST['method'];
            }
            $insertsql = "INSERT INTO  `erp_sales_payment` (
`sid`,
`method`,
`amount`,
`tendered`)
            VALUES
            (
'{$this->get_sid()}',
'{$this->escStr($method)}',
'{$payamt}',{$tendAm});";
            return $this->dbal()->query_exc($insertsql) ? 1 : "Error:";
        }
    }

    function remove_payment()
    {
        $this->dbal()->query_exc("DELETE FROM erp_sales_payment WHERE `erp_sales_payment`.`ID` = {$this->comm()->rqstr('IID')}");
    }

    function finish()
    {
        foreach ($this->get_sales_items() as $items) {
            //print_r($items);
            if ($items['quantity'] > 0) {
                $qty = "-{$items['quantity']}";
                $salesType = "Sales: ";
            } else {
                $qty = "{$items['quantity']}";
                $salesType = "Sales Return:";
            }
            $this->dbal()->query_exc("INSERT INTO `erp_inventory`(
                                    `item_id`,
                                    `tr_qty`,
                                    `tr_comment`,
                                    `employe_id`,
                                    `shopid`
                                )
                                VALUES(
                                    '{$items['product_id']}',
                                    '{$qty}',
                                    '{$salesType} {$this->get_sid()}',
                                    '{$this->jwt()->juid()}',
                                    '1'
                                )");
        }

        //-------------Transections
        //Get Journal ID
        $jid = $this->dbal()->jid("NOW", "Sales Invoice: {$this->get_sid()}");
        //insert from Credit user account as debit and Cash+other method as debit       
        foreach ($this->dbal()->query("SELECT * FROM `erp_sales_payment` WHERE `sid`={$this->get_sid()}") as $pm) {
            if ($pm['method'] == 105) {
                $method = $pm['method'] . $this->get_erp_salesEditData()[0]['customer_id'];
            } else {
                $method = $pm['method'];
            }
            if ($this->acc_balance("105" . $this->get_erp_salesEditData()[0]['customer_id']) < 0 and abs($this->acc_balance("105" . $this->get_erp_salesEditData()[0]['customer_id'])) >= $pm['amount']) {
                $this->dbal()->query_exc("INSERT INTO `erp_due_payments`( `sid`, `amount`, `method_coed`, `pay_date`) VALUES ('{$this->get_sid()}','{$pm['amount']}','{$method}',NOW());");
            } elseif ($this->acc_balance("105" . $this->get_erp_salesEditData()[0]['customer_id']) < 0 and abs($this->acc_balance("105" . $this->get_erp_salesEditData()[0]['customer_id'])) < $pm['amount']) {
                $duepayamt = $pm['amount'] + $this->acc_balance("105" . $this->get_erp_salesEditData()[0]['customer_id']);
                //$this->dbal()->query_exc("INSERT INTO `erp_due_payments`( `sid`, `amount`, `method_coed`, `pay_date`) VALUES ('{$this->get_sid()}','{$duepayamt}','{$method}',NOW());");
                $this->record_due($this->get_sid(), $this->get_erp_salesEditData()[0]['customer_id'], $duepayamt, $method);
            }
            $this->dbal()->add_journal($jid, $method, $pm['amount']);
        }
        //insert in sales Account code:401

        if ($this->info_vat() > 0) {
            $cashAcmt = $this->get_total_amount() - ($this->info_vat());
            //insert in cash ac
            $this->dbal()->add_journal($jid, 401, -$cashAcmt);
            //insert in tax payable
            $this->dbal()->add_journal($jid, 2002, - ($this->info_vat()));
        } else {
            $this->dbal()->add_journal($jid, 401, - ($this->get_total_amount()));
        }


        //Now insert the cost of good sold and ruduce inventory

        $cost = 0;
        foreach ($this->dbal()->query("SELECT `product_id`,`quantity` FROM `erp_sales_items` WHERE `sid`={$this->get_sid()}") as $qitem) {
            $cost += ($this->dbal()->get_single_result("SELECT SUM((`cost_price`+(`cost_price`*(`vat`/100))-(`cost_price`*(`discount`/100)))*`quantity`)/SUM(`quantity`) AS produc_cost FROM `erp_receiving_items` WHERE `product_id`={$qitem['product_id']}")) * $qitem['quantity'];
        }
        //cost of goods sold item
        $this->dbal()->add_journal($jid, 503, $cost);
        //$then add to inventory as credit
        $this->dbal()->add_journal($jid, 104, - ($cost));

        //.............Update the Sales 
        $sid = $this->get_sid();
        $this->dbal()->update_database("UPDATE `erp_sales` SET `status`='3',`sales_date`=CURDATE(),`totalamount` = '{$this->get_total_amount()}' WHERE `ID`={$sid}");
        return $sid;
    }

    function return_idata()
    {
    }

    function record_return()
    {
        //Array ( [selecteditem] => Array ( [0] => 40 [1] => 41 [2] => 42 ) [quantity] => 1 [reason] => 0p9 )
        //$this->dbal()->query_exc("");
        echo "<pre>";
        foreach ($_POST['selecteditem'] as $selecteditm) {
            echo "//....................Inventory Data";
            $quantity = $_POST['quantity'];
            echo "INSERT INTO `erp_inventory`(
                                    `item_id`,
                                    `tr_qty`,
                                    `tr_comment`,
                                    `employe_id`,
                                    `shopid`
                                )
                                VALUES(
                                    '{$selecteditm}',
                                    '{$quantity}',
                                    'Sales: {$this->get_sid()}',
                                    '{$this->jwt()->juid()}',
                                    '1'
                                );<br><br>";

            foreach ($this->dbal()->query("SELECT * FROM `erp_sales_items` WHERE `sid`={$_POST['invID']} AND `product_id`={$selecteditm}") as $qitem) {
                //`ID`, `sid`, `product_id`, `sales_price`, `unit_price`, `discount`, `vat`, `quantity`
                print_r($qitem);
            }
        }
    }

    function get_due_payments($sid)
    {
        return $this->dbal()->query("SELECT * FROM `erp_due_payments` WHERE `sid`={$sid}");
    }

    function invoice_due($sid)
    {
        $invoiceDue = $this->dbal()->get_single_result("SELECT SUM(amount) FROM `erp_sales_payment` WHERE `sid`=$sid AND `method`=105");
        $duepaid = $this->dbal()->get_single_result("SELECT SUM(amount) FROM `erp_due_payments` WHERE `sid`=$sid");
        return $invoiceDue - $duepaid;
    }

    function acc_balance($acc)
    {
        $dr_transection = $this->dbal()->get_single_result("SELECT SUM(`amount`) FROM `acc_journal_data` WHERE `account_code`={$acc} AND `tr_type`=1;");
        $tr_transection = $this->dbal()->get_single_result("SELECT SUM(`amount`) FROM `acc_journal_data` WHERE `account_code`={$acc} AND `tr_type`=2;");
        return $dr_transection - $tr_transection;
    }

    //record due is balance recode that customer has a credit note or previus due on acount
    function record_due($sid, $cid, $amount, $method)
    {
        $jid = $this->dbal()->jid('NOW', "Due Payments for Invoice: i{$sid}");
        $this->dbal()->add_journal($jid, '105' . $cid, - ($amount));
        $this->dbal()->add_journal($jid, $method, ($amount));
        //Add to due table
        $this->dbal()->query_exc("INSERT INTO `erp_due_payments`( `sid`, `amount`, `method_coed`, `pay_date`) VALUES ('{$sid}','{$amount}','{$method}',NOW());");
    }

    //--------------------------------------------------Sales suspension actions
    function suspend()
    {
        $note = isset($_REQUEST['note']) ? $this->comm()->rqstr('note') : null;
        $sdata = $this->dbal()->query("SELECT `ID`, `sales_date`, `customer_id`, `employee_id`, `comment`, `deleted`, `status` FROM `erp_sales` WHERE `ID`={$this->get_sid()}")[0];
        //print_r($sdata);
        if ($this->dbal()->num_of_row("SELECT `ID`, `sid`, `product_id`, `sales_price`, `unit_price`, `discount`, `vat`, `quantity`, `rq`, `rtid` FROM `erp_sales_items` WHERE `sid`={$this->get_sid()}") > 0) {
            $susid = $this->dbal()->query_exc("INSERT INTO `erp_sales_suspend`(`customer_id`, `sales_time`, `employee_id`,`note`) VALUES ('{$sdata['customer_id']}',NOW(),'{$this->jwt()->juid()}','{$note}')");
            foreach ($this->dbal()->query("SELECT `ID`, `sid`, `product_id`, `sales_price`, `unit_price`, `discount`, `vat`, `quantity`, `rq`, `rtid` FROM `erp_sales_items` WHERE `sid`={$this->get_sid()}") as $value) {
                $this->dbal()->query_exc("INSERT INTO `erp_sales_suspend_items`(`susid`, `item_id`, `qty`, `price`) VALUES ('{$susid}','{$value['product_id']}','{$value['quantity']}','{$value['sales_price']}')");
            }
            $this->dbal()->query_exc("DELETE FROM erp_sales_items WHERE `erp_sales_items`.`sid` = {$this->get_sid()}");
            $this->dbal()->update_database("UPDATE `erp_sales` SET `customer_id` = '1' WHERE `erp_sales`.`ID` = {$this->get_sid()};");
            return 1;
        } else {
            return "No items in cart, So suspend oparation is not executed";
        }
    }

    function suspended()
    {
        return $this->dbal()->query("SELECT `erp_sales_suspend`.*, `customers`.`customer_name`,`customers`.`contact_number` FROM `erp_sales_suspend` INNER JOIN `customers`
ON `customers`.`ID`=`erp_sales_suspend`.`customer_id` WHERE  `erp_sales_suspend`.`deleted`=0 AND `erp_sales_suspend`.`employee_id`={$this->jwt()->juid()}");
    }

    function unsuspend()
    {
        $itemsincart = $this->dbal()->num_of_row("SELECT `ID` FROM `erp_sales_items` WHERE `sid`={$this->get_sid()}");
        if ($itemsincart == 0) {
            $susid = $this->comm()->get('susid');
            $susdata = $this->dbal()->query("SELECT * FROM `erp_sales_suspend` WHERE `ID`={$susid}")[0];
            $susitem = $this->dbal()->query("SELECT * FROM `erp_sales_suspend_items` WHERE `susid`={$susid}");
            foreach ($susitem as $value) {
                $this->item_add_to_cart($value['item_id'], $value['qty']);
            }
            $this->dbal()->update_database("UPDATE `erp_sales` SET `customer_id` = '{$susdata['customer_id']}' WHERE `erp_sales`.`ID` = {$this->get_sid()};");
            $this->dbal()->update_database("UPDATE `erp_sales_suspend` SET `deleted` = '1' WHERE `erp_sales_suspend`.`ID` ={$susid};");
            $return = 1;
        } else {
            $return = "You can not restore while the current cart has item/s";
        }
        return $return;
    }

    function cancle_sales()
    {
        $this->dbal()->query_exc("DELETE FROM erp_sales_items WHERE `erp_sales_items`.`sid` = {$this->get_sid()}");
        $this->dbal()->query_exc("DELETE FROM erp_sales_payment WHERE `erp_sales_payment`.`sid` = {$this->get_sid()}");
        $this->dbal()->update_database("UPDATE `erp_sales` SET `customer_id` = '1' WHERE `erp_sales`.`ID` = {$this->get_sid()};");
    }

    //---------------------------------------------------
    function sales_data_by_employee()
    {
        $sdt = $_GET['sdt'];
        $edt = $_GET['edt'];
        $empid = $this->jwt()->juid();
        $this->dbal()->update_jdata();
        return $this->dbal()->query("
SELECT
    `erp_sales`.*,
`customers`.`customer_name`,
`customers`.`contact_number`,
(SELECT SUM(((`sales_price`-(`sales_price`*`discount`/100))+(`sales_price`*`vat`/100))*`quantity`) AS total FROM `erp_sales_items` WHERE `sid`=`erp_sales`.`ID`) AS invtotal
FROM
    `erp_sales`
INNER JOIN `customers` ON `erp_sales`.`customer_id` = `customers`.`ID`
WHERE
    `sales_date` BETWEEN '{$sdt}' AND '{$edt}' AND `status`=3 AND `employee_id`={$empid}");
    }

    function employee_data($empid)
    {
        return $this->dbal()->query("SELECT ID,firstname,lastname FROM user WHERE deleted=0 AND ID={$empid}");
    }

    function get_payments()
    {
        $sdt = $_GET['sdt'];
        $edt = $_GET['edt'];
        $empid = $this->jwt()->juid();
        $sids = $this->dbal()->query("
SELECT `acc_payment_method`.`method_name`, `erp_sales_payment`.`method`,SUM(`erp_sales_payment`.`amount`) AS pamt FROM `erp_sales` INNER JOIN `erp_sales_payment` ON `erp_sales`.`ID`=`erp_sales_payment`.`sid` INNER JOIN `acc_payment_method` ON `erp_sales_payment`.`method`=`acc_payment_method`.`account_code` WHERE `sales_date` BETWEEN '{$sdt}' AND '{$edt}' AND `status`=3 AND `employee_id`={$empid} GROUP BY `erp_sales_payment`.`method`;
    ");
        return $sids;
    }
}
