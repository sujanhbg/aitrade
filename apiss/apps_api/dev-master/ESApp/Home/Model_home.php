<?php

use kring\database\dbal;
use kring\utilities\comm;
use kring\database\kdbal;
//use kring;

class Model_home
{

    public function __construct()
    {
    }

    public function comm()
    {
        return new comm();
    }

    public function dbal()
    {
        return new dbal();
    }

    function kdb()
    {
        return new kdbal();
    }

    function app()
    {
        return isset($_GET['host']) ? $_GET['host'] : explode(".", $_SERVER['SERVER_NAME'])[0];;
    }

    function kring()
    {
        return new \kring\core\kring();
    }


    function get_kring_data()
    {
        $app = $this->app();
        $qry = "SELECT "
            . " `ID`, `userid`, `appname`, `industry_id`, `subscription_date`, `expire_date`, `company_name`, `phone_number`, `email_address`, `company_address`, `currency`, `currency_symble`, `symble_position`, `country`, `footer_text`, `vat_registration_number`, `logo_url`, `icon_url`, `reorder_lebel`, `deleted`, `block`, `custom_domain`, `domain_active`, `admin_email`,  `mejor_application`, `web_admin`, `invoice_template`, `dls_template`, `package`, `ecommerce`, `template`, `resellerid`, `pwby`, `rwebsite`, `status`, `site_title`, `metadesc`, `main_image`, `img_alt`, `metakeywords` "
            . " FROM `kbiz_subscription` WHERE `appname`='{$app}' LIMIT 1";
        return $this->kdb()->query($qry)[0];
    }

    function get_expired()
    {
        $app = $this->app();
        $qry = "SELECT `expire_date` FROM `kbiz_subscription` WHERE `appname`='{$app}' LIMIT 1";
        $expire = $this->kdb()->query($qry)[0]['expire_date'];

        $expire = strtotime($expire . ' + 2 days');
        $today = strtotime("today midnight");

        if ($today >= $expire) {
            return 1;
        } else {
            return 0;
        }
    }

    function get_installed_modules()
    {
        //return ['myprofile', 'testdb', 'customer'];
        $appid = $this->get_kring_data()['ID'];
        $mdl = "";
        foreach ($this->kdb()->query("SELECT `module` FROM `kbiz_grant` WHERE `isgrant`=1 AND `appID`={$appid} ORDER BY `shrt` ASC") as $md) {
            $mdl .= $md['module'] . ",";
        }
        //return [trim($mdl, ",")];
        //print_r(explode(",", trim($mdl, ",")));
        return explode(",", trim($mdl, ","));
    }

    function is_granted($userid, $module)
    {
        return $this->dbal()->get_single_result("SELECT `isgrant` FROM `usergranted_options` WHERE `userID`='{$userid}' AND `appname`='{$module}'");
    }

    function is_installed($module)
    {
        $appid = $this->get_kring_data()['ID'];
        if ($this->kdb()->num_of_row("SELECT `ID` FROM `kbiz_grant` WHERE `appID`={$appid} AND `module`='$module' AND `isgrant`=1")) {
            return true;
        } else {
            return false;
        }
    }

    function get_topmenu_items()
    {
        $r[1] = $this->kdb()->query("SELECT * FROM `kbiz_modules` WHERE `kbiz_modules`.`intop`=1 ORDER BY  def_shrt ASC LIMIT 2");
        $r[2] = $this->kdb()->query("SELECT * FROM `kbiz_modules` WHERE `kbiz_modules`.`intop`=1 ORDER BY  def_shrt ASC LIMIT 2, 20");
        return $r;
    }

    function get_topmenu_items_all()
    {
        $r = $this->kdb()->query("SELECT * FROM `kbiz_modules` WHERE `kbiz_modules`.`intop`=1 ORDER BY  def_shrt ASC LIMIT  20");
        return $r;
    }
    function homemenu()
    {
        $rt = [];
        foreach ($this->kdb()->querydt("SELECT `ID`,`module_name`,`subfor`,`module_title`,`icon`,`intop`,`show_menu` FROM `kbiz_modules` WHERE `appid`={$this->get_kring_data()['mejor_application']}  ORDER BY `def_shrt` ASC") as $value) {

            if ($this->is_installed($value[1])) {
                if ($_SESSION['UsrRole'] == 22) {
                    array_push($rt, $value);
                } else {
                    if ($this->is_granted($_SESSION['UsrID'], $value[1])) {
                        array_push($rt, $value);
                    }
                }
            }
        }
        return $rt;
    }

    function get_leftmenu_items33()
    {
        /* $kdata = $this->get_kring_data();
          return $this->kring_db_query("SELECT * FROM `kbiz_user_modules` WHERE `kbiz_ID`={$kdata['ID']} AND `in_menu`=1 AND `subfor`=0 AND deleted=0  ORDER BY shrt ASC;"); */

        $modules = new kring\core\modules();
        $installedmodule = $this->get_installed_modules();
        $rt = ''; //commont modules can be defined here
        foreach ($installedmodule as $im) {
            if ($_SESSION['UsrRole'] == 22) {
                if (method_exists($modules, $im)) {
                    $rt .= $modules->$im()['menu'];
                }
            } else {
                if (method_exists($modules, $im) && $this->is_granted($_SESSION['UsrID'], $im) == 1) {

                    $rt .= $modules->$im()['menu'];
                }
            }
        }
        return $rt;
    }

    function get_leftmenu_items_sub33($perentID, $returnType = "val")
    {
        $kdata = $this->get_kring_data();
        $qry = "SELECT * FROM `kbiz_user_modules` WHERE `kbiz_ID`={$kdata['ID']} AND `in_menu`=1 AND `subfor`={$perentID} AND deleted=0  ORDER BY shrt ASC";
        if ($returnType == "num") {
            return $this->num_of_row($qry);
        } else {
            return $this->kring_db_query($qry);
        }
    }

    /*
     * We like paint menu from directly from Kring Database
     */

    function is_submenu($perentid)
    {
        return $this->kdb()->num_of_row("SELECT ID FROM `kbiz_modules` WHERE `appid`={$this->get_kring_data()['mejor_application']} AND `subfor`={$perentid} AND `show_menu`=1");
    }

    function get_leftmenu_items_sub($parentID)
    {
        $ret = '';
        foreach ($this->kdb()->query("SELECT * FROM `kbiz_modules` WHERE `appid`={$this->get_kring_data()['mejor_application']} AND `subfor`={$parentID}  AND `show_menu`=1 ORDER BY `def_shrt` ASC") as $value) {

            if ($this->is_installed($value['module_name'])) {
                if ($_SESSION['UsrRole'] == 22) {

                    $ret .= <<<EOT
        <li class="nav-item">
            <a href="{$this->kring()->coreconf('baseurl')}/{$value['module_name']}" class="nav-link">
                <span class="material-symbols-outlined">chevron_right</span>
                    <p>{$value['module_title']}</p>
            </a>
        </li>
EOT;
                } else {

                    if ($this->is_granted($_SESSION['UsrID'], $value['module_name']) == 1) {

                        $ret .= <<<EOT
        <li class="nav-item">
            <a href="{$this->kring()->coreconf('baseurl')}/{$value['module_name']}" class="nav-link">
                <span class="material-symbols-outlined">chevron_right</span>
                    <p>{$value['module_title']}</p>
            </a>
        </li>
EOT;
                    }
                }
            }
        }
        return $ret;
    }

    function get_leftmenu_items()
    {
        return $this->kdb()->query("SELECT `module_name`,`module_title`,`icon` FROM `kbiz_modules` WHERE `appid`={$this->get_kring_data()['mejor_application']} AND `subfor`=0  AND `show_menu`=1 ORDER BY `def_shrt` ASC LIMIT 100");
    }

    function randBgColors()
    {
        $colorset = [
            ['bg' => '#004d4d', 'txt' => '#fff'],
            ['bg' => '#003399', 'txt' => '#fff'],
            ['bg' => '#006699', 'txt' => '#fff'],
            ['bg' => '#efccff', 'txt' => '#000'],
            ['bg' => '#cceeff', 'txt' => '#000'],
            ['bg' => '#e6ffee', 'txt' => '#000'],
            ['bg' => '#ffe6cc', 'txt' => '#000'],
            ['bg' => '#ccffcc', 'txt' => '#000'],
            ['bg' => '#ffebcc', 'txt' => '#000'],
            ['bg' => '#ff99bb', 'txt' => '#000'],
            ['bg' => '#99ccff', 'txt' => '#000'],
            ['bg' => '#ff99bb', 'txt' => '#000'],
            ['bg' => '#ff9933', 'txt' => '#000'],
            ['bg' => '#990000', 'txt' => '#fff']
        ];
        return $colorset;
    }

    function msg_Q()
    {
        return $this->dbal()->num_of_row("SELECT `ID` FROM `contact_message` WHERE `readed`=0 AND `deleted`=0;");
    }

    function appJs_old()
    {
        /* $kd = $this->get_kring_data();
          $sql = "SELECT * FROM `kbiz_user_modules` WHERE `kbiz_ID`={$kd['ID']} AND `in_js`=1 AND deleted=0 ORDER BY shrt ASC;";
          return $this->kring_db_query($sql); */
        $modules = new kring\core\modules();
        $installedmodule = $this->get_installed_modules();
        $rt = ''; //commont modules can be defined here
        foreach ($installedmodule as $im) {
            if (method_exists($modules, $im)) {
                $rt .= $modules->$im()['pagejs'];
            }
        }
        return $rt;
    }

    //----------i create pageJS file from js file
    function appJs()
    {
        $modules = new kring\core\modules();
        $installedmodule = $this->get_installed_modules();
        $rt = ''; //commont modules can be defined here
        if (is_file(dirname(__DIR__) . "/assets/js/app.js")) {
            $rt .= file_get_contents(dirname(__DIR__) . "/assets/js/app.js");
        }
        foreach ($installedmodule as $im) {
            $imd = ucfirst($im);
            if (is_file(dirname(__DIR__) . "/ESApp/" . $imd . "/page.js")) {
                $rt .= file_get_contents(dirname(__DIR__) . "/ESApp/" . $imd . "/page.js");
            } else {
                //$rt .= dirname(__DIR__) . "/ESApp/" . $imd . "/page.js is not found\n\n";
            }
        }
        return $rt;
    }

    function inv()
    {
        //Inventory Transection
    }

    //-------------------------------------Dashboard Counting
    function num_of_products()
    {
        return $this->dbal()->get_single_result("SELECT COUNT(ID) FROM products WHERE deleted=0");
    }

    function num_of_customers()
    {
        return $this->dbal()->get_single_result("SELECT COUNT(ID) FROM customers WHERE deleted=0");
    }

    function num_of_orders()
    {
        return $this->dbal()->get_single_result("SELECT COUNT(ID) FROM suppliers WHERE deleted=0");
    }

    function num_of_sales()
    {
        return $this->dbal()->get_single_result("SELECT COUNT(ID) FROM erp_sales WHERE deleted=0");
    }

    function run_table_alter()
    {
        $this->dbal()->query_exc("ALTER TABLE `erp_sales` ADD COLUMN IF NOT EXISTS  `totalamount`  DOUBLE NULL DEFAULT NULL AFTER `status`;");
    }

    function get_kbiz_subscriptionEditData()
    {
        $app = $this->kdb()->appname();
        return $this->kdb()->query("SELECT `offline`,`offline_html` FROM kbiz_subscription WHERE `appname`='{$app}' LIMIT 1");
    }



    function escStr($str)
    {
        return $this->kdb()->conn()->real_escape_string($str);
    }
    function kbiz_subscriptionedited_data_save()
    {

        $validated_data = $_POST;

        $return = "";

        $dbvalidation = true; //$this->kbiz_subscription_dbvalid(['email' => $validated_data['email'], 'cell' => $validated_data['cell']]);
        if ($dbvalidation == true) {
            $app = $this->kdb()->appname();
            $editsql = "UPDATE  kbiz_subscription SET 
				`offline` =  '{$this->escStr($validated_data['offline'])}',
				`offline_html` =  '{$this->escStr($validated_data['offline_html'])}' WHERE `appname`='{$app}' LIMIT 1";

            if ($this->kdb()->update_database($editsql)) {
                $return = 1;
            } else {
                $return = ""
                    . "We are Sorry; We can not save your update";
            }
        } else {
            $return = "Data Exists!";
        }

        return $return;
    }
    function get_category()
    {

        $pidq = isset($_GET['pid']) ? $this->comm()->rqstr('pid') : 0;
        $sq = $pidq == "*" ? "" : " AND `subfor`={$pidq}";
        //echo "SELECT `ID`,`category_name`,`online` FROM product_catagory WHERE `deleted`=0 AND `subfor`={$pidq}";
        return $this->dbal()->query("SELECT `ID`,`category_name`,`subfor` FROM product_catagory WHERE `deleted`=0 AND `online`=1");
    }
    function get_categoryHome()
    {

        $pidq = isset($_GET['pid']) ? $this->comm()->rqstr('pid') : 0;
        $sq = $pidq == "*" ? "" : " AND `subfor`={$pidq}";
        //echo "SELECT `ID`,`category_name`,`online` FROM product_catagory WHERE `deleted`=0 AND `subfor`={$pidq}";
        return $this->dbal()->query("SELECT `ID`,`category_name`,`image` FROM product_catagory WHERE `deleted`=0 AND `online`=1 AND `subfor`<>0 LIMIT 12");
    }
    function get_productsHome()
    {
        $pidq = isset($_GET['cname']) ? $this->comm()->rqstr('cname') : 0;
        $rt['top'] = $this->dbal()->query("SELECT * FROM `products` WHERE `deleted`=0 AND `pic_url`<>'' ORDER BY RAND() LIMIT 0,24");
        return $rt;
    }
    function get_products()
    {
        $pidq = isset($_GET['cname']) ? $this->comm()->rqstr('cname') : 0;
        //echo "SELECT `ID`,`category_name`,`online` FROM product_catagory WHERE `deleted`=0 AND `subfor`={$pidq}";
        return $this->dbal()->query("SELECT * FROM `products` WHERE `category`='{$pidq}' AND `deleted`=0 ORDER BY ID DESC LIMIT 0,32");
    }
}
