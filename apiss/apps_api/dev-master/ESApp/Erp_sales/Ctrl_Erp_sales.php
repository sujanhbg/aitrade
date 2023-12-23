<?php

use kring\core\Controller;

class Erp_sales extends Controller
{

    public $adminarea;

    function __construct()
    {
        parent::__construct();
        $this->adminarea = 1;
    }

    function model()
    {
        return $this->loadESmodel('erp_sales', 'Erp_sales');
    }

    function index()
    {
        return $this->model()->datasource();
    }
    function info()
    {
        $data['items'] = $this->model()->get_sales_items();
        $data['invinf'] = $this->model()->get_other_info();
        $data['totalvat'] = $this->model()->info_vat();
        $data['discount'] = $this->model()->info_discount();
        $data['sd'] = isset($this->model()->get_erp_salesEditData()[0]) ? $this->model()->get_erp_salesEditData()[0] : [];
        $data['payments'] = $this->model()->info_payments();
        $data['totalAmount'] = $this->model()->get_total_amount();
        $data['paid'] = $this->model()->get_paid_amount();
        $data['customer_balance'] = $this->model()->acc_balance("105" . $data['sd']['customer_id']);

        return $data;
    }
    function datasource()
    {
        return $this->model()->datasource();
    }
    function autodata()
    {
        return $this->model()->get_autoData();
    }
    function add_to_cart()
    {
        return $this->model()->add_to_cart();
    }
    function remove_item()
    {
        $return = $this->model()->remove_from_cart();
        return $return ? "success" : $return;
    }
    function update_cert()
    {
        $return = $this->model()->update_items();
        return $return == 1 ? "success" : $return;
    }
    function customer_auto()
    {
        return $this->model()->get_customer_autoC();
    }
    function add_customer()
    {
        return $this->model()->add_customer();
    }
    function add_payment()
    {
        return $this->model()->add_payment();
    }
    function remove_payment()
    {
        return $this->model()->remove_payment();
    }

    function suspend()
    {
        return $this->model()->suspend();
    }

    function suspended()
    {
        return $this->model()->suspended();
    }
    function  unsuspend()
    {
        $return = $this->model()->unsuspend();
        return $return == 1 ? ['status' => 'success'] : ['status' => 'error', 'msg' => $return];
    }
    function cancle_sales()
    {
        $this->model()->cancle_sales();
        return ['status' => 'success'];
    }
    function finish_sales()
    {
        $return = $this->model()->finish();
        return $return > 0 ? ['status' => 'success', 'sid' => $return] : ['status' => 'error', 'msg' => "Can not Finish Sales"];
    }

    function item_list()
    {
        return $this->model()->item_list();
    }
}
