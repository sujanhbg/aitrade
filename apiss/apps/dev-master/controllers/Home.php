<?php

use kring\core\Controller;

class Home extends Controller
{

    private $model;
    public $adminarea;

    function __construct()
    {
        parent::__construct();
        $this->adminarea = 0;
        $this->model = $this->loadmodel('home');
    }

    function model()
    {
        return $this->loadmodel("home");
    }

    function ecm()
    {
        return $this->loadmodel("ecom");
    }

    function index($pr)
    {
        if (explode(".", $_SERVER['SERVER_NAME'])[0] == 'kapp') {
            $data['title'] = "Kalni Business Suite";

            $this->lv('mainsite/top', $data);
            $this->lv('mainsite/header', $data);
            $this->lv('mainsite/home', $data);
            $this->lv('mainsite/footer', $data);
        } else {


            $kb = $this->model()->kdbal()->kbiz_data();
            //print_r($kb);
            if ($kb['offline'] == 1) {
                echo $kb['offline_html'];
            } else {
                if ($kb['mejor_application'] == 1) {
                    if ($kb['ecommerce'] == 1) {
                        $data['title'] = $kb['site_title'];
                        $data['metadesc'] = $kb['metadesc'];
                        $data['metakeywords'] = $kb['metakeywords'];
                        $data['kb'] = $kb;
                        $data['categories'] = $this->ecm()->product_categories();
                        echo $this->lvp('index', $data);
                        //echo $this->lvp('top', $data);
                        //echo $this->lvp('main', $data);
                        //echo $this->lvp('footer', $data);
                    } else {
                        $pageid = isset($pr[2]) ? $pr[2] : 1;
                        if (explode(".", $_SERVER['SERVER_NAME'])[0] == "kapp") {
                        } else {
                            $data['title'] = $kb['site_title'];
                            $data['metadesc'] = $kb['metadesc'];
                            $data['metakeywords'] = $kb['metakeywords'];
                            $data['kb'] = $kb;
                            $data['pageid'] = $pageid;
                            $data['menus'] = $this->model->get_menu();
                            $data['theme'] = $kb['template'];
                            echo $this->lvp('head', $data);
                            echo $this->lvp('top', $data);
                            echo $this->lvp('body', $data);
                            echo $this->lvp('footer', $data);
                        }
                    }
                } elseif ($kb['mejor_application'] == 5) {
                    $data['menus'] = $this->model->get_menu();
                    $data['mejorapp'] = "newsroom";
                    $data['title'] = $kb['site_title'];
                    $data['metadesc'] = $kb['metadesc'];
                    $data['metakeywords'] = $kb['metakeywords'];
                    $data['theme'] = "newsroom";
                    $this->tgp("header", $data);
                } else {
                    $pageid = isset($pr[2]) ? $pr[2] : 1;
                    if (explode(".", $_SERVER['SERVER_NAME'])[0] == "phplab") {
                    } else {
                        $data['title'] = '';
                        $data['pageid'] = $pageid;
                        $data['menus'] = $this->model->get_menu();
                        $this->lv('home', $data);
                    }
                }
            }
        }
    }
}
