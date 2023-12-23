<?php

namespace kring\core;

class modules {

    private $kring;

    function __construct() {
        $this->kring = new kring();
    }

    function customers() {
        $menuname = "Customers";
        $menuurl = "customers";
        $menumaticon = "person";
        $title = "Manage Customers";
        $rt['menu'] = menumaker::menu_item($menuurl, $menuname, $menumaticon, $title);
        /* $rt['menu'] = menumaker::groupmenu("TestGroup Menu", menumaker::menu_item($menuurl, $menuname, $menumaticon) .
          menumaker::menu_item($menuurl, "Menu2", $menumaticon) .
          menumaker::menu_item($menuurl, "Menu2", $menumaticon) .
          menumaker::menu_item($menuurl, "Menu2", $menumaticon)); */
        $rt['pagejs'] = "page(app + '/{$menuurl
                }

', function () {
                lr(app + '/?app={$menuurl
                }

&opt=index&fd=fd', 'mainbody');
                document.title = '{$menuname
                }

';
            });"
                . "page(app +'/customers/new', function () {
                    lr(app +app + '/?app=customers&opt=new&fd=fd', 'mainbody');
                    document.title = \"Add customer\";
                });

                page(app +'/customers/edit/:id', function (ctx) {
                    lr(app + '/?app=customers&opt=edit&fd=fd&ID=' + ctx.params.id, 'mainbody');
                    document.title =\"Edit customers\";
                });";
        return $rt;
    }

    function suppliers() {
        $menuname = "suppliers";
        $menuurl = "suppliers";
        $menumaticon = "person";
        $title = "Manage suppliers";
        $rt['menu'] = menumaker::menu_item($menuurl, $menuname, $menumaticon, $title);
        /* $rt['menu'] = menumaker::groupmenu("TestGroup Menu", menumaker::menu_item($menuurl, $menuname, $menumaticon) .
          menumaker::menu_item($menuurl, "Menu2", $menumaticon) .
          menumaker::menu_item($menuurl, "Menu2", $menumaticon) .
          menumaker::menu_item($menuurl, "Menu2", $menumaticon)); */
        $rt['pagejs'] = "page(app +'/suppliers', function () {
                    lr(app + '/?app=suppliers&opt=index&fd=fd', 'mainbody');
                    document.title = \"Suppliers\";
                });
                page(app +'/suppliers/new', function () {
                    lr(app +app + '/?app=suppliers&opt=new&fd=fd', 'mainbody');
                    document.title = \"Add suppliers\";
                });

                page(app +'/suppliers/edit/:id', function (ctx) {
                    lr(app + '/?app=suppliers&opt=edit&fd=fd&ID=' + ctx.params.id, 'mainbody');
                    document.title = \"Edit suppliers\";
                });";
        return $rt;
    }

    function product_catagory() {
        $rt['menu'] = null;

        $rt['pagejs'] = "page(app +'/product_catagory', function () {
                    lr(app + '/?app=product_catagory&opt=index&fd=fd', 'mainbody');
                    document.title = \"Product Catagory\";
                });
                page(app +'/product_catagory/new', function () {
                    lr(app +app + '/?app=product_catagory&opt=new&fd=fd', 'mainbody');
                    document.title = \"Add Product Catagory\";
                });

                page(app +'/product_catagory/edit/:id', function (ctx) {
                    lr(app + '/?app=product_catagory&opt=edit&fd=fd&ID=' + ctx.params.id, 'mainbody');
                    document.title = \"Edit Product Catagory\";
                });";
        return $rt;
    }

    function product_unit() {
        $rt['menu'] = null;

        $rt['pagejs'] = "page(app +'/product_unit', function () {
                    lr(app + '/?app=product_unit&opt=index&fd=fd', 'mainbody');
                    document.title = \"Product Unit\";
                });
                page(app +'/product_unit/new', function () {
                    lr(app +app + '/?app=product_unit&opt=new&fd=fd', 'mainbody');
                    document.title = \"Add Product Unit\";
                });

                page(app +'/product_unit/edit/:id', function (ctx) {
                    lr(app + '/?app=product_unit&opt=edit&fd=fd&ID=' + ctx.params.id, 'mainbody');
                    document.title = \"Edit Product Unit\";
                });";
        return $rt;
    }

    function product_brand() {
        $rt['menu'] = null;

        $rt['pagejs'] = "page(app +'/product_brand', function () {
                    lr(app + '/?app=product_brand&opt=index&fd=fd', 'mainbody');
                    document.title = \"Product Brand\";
                });
                page(app +'/product_brand/new', function () {
                    lr(app +app + '/?app=product_brand&opt=new&fd=fd', 'mainbody');
                    document.title = \"Add Product Brand\";
                });

                page(app +'/product_brand/edit/:id', function (ctx) {
                    lr(app + '/?app=product_brand&opt=edit&fd=fd&ID=' + ctx.params.id, 'mainbody');
                    document.title = \"Edit Product Brand\";
                });";
        return $rt;
    }

    function products() {
        $rt['menu'] = menumaker::groupmenu("Products",
                        menumaker::menu_item("products", "Products", "check_circle_outline", "Manage Products") .
                        menumaker::menu_item("product_catagory", "Product Catagory", "check_circle_outline", "Manage Product Catagory") .
                        menumaker::menu_item("product_unit", "Product Unit", "check_circle_outline", "Manage Product Unit") .
                        menumaker::menu_item("product_brand", "Product Brand", "check_circle_outline", "Manage Product Brand")
                        , "category");

        $rt['pagejs'] = "page(app +'/products', function () {
                    lr(app + '/?app=products&opt=index&fd=fd', 'mainbody');
                    document.title = \"Products\";
                });
                page(app +'/products/new', function () {
                    lr(app +app + '/?app=products&opt=new&fd=fd', 'mainbody');
                    document.title = \"Add Products\";
                });

                page(app +'/products/edit/:id', function (ctx) {
                    lr(app + '/?app=products&opt=edit&fd=fd&ID=' + ctx.params.id, 'modalbody');
                    openmodal();
                    document.title = \"Edit Products\";
                });
                page(app +'/products/view/:id', function (ctx) {
                    lr(app + '/?app=products&opt=view&fd=fd&ID=' + ctx.params.id, 'modalbody');
                    openmodal();
                    });
                    
                page(app +'/products/inventory/:id', function (ctx) {
                    lr(app + '/?app=products&opt=inventory&fd=fd&ID=' + ctx.params.id, 'modalbody');
                    openmodal();
                    });
                    

page(app +'/notice', function () {
                    lr(app + '/?app=notice&opt=index&fd=fd', 'mainbody');
                    document.title = \"Notice\";
                });

";

        return $rt;
    }

//-------------------------------------------------------------Inventory
    function erp_inventory() {
        $rt['menu'] = "";
        $rt['pagejs'] = "page(app +'/erp_report/inventory', function () {
                    lr(app + '/?app=erp_report&opt=inventory&fd=fd', 'mainbody');
                    document.title = \"Inventory\";
                });
                page(app +'/erp_inventory/in', function () {
                    lr(app +app + '/?app=erp_inventory&opt=new&fd=fd', 'mainbody');
                    document.title = \"Add Erp Inventory\";
                });

                page(app +'/erp_inventory/out', function () {
                    lr(app +app + '/?app=erp_inventory&opt=new&fd=fd', 'mainbody');
                    document.title = \"Add Erp Inventory\";
                });

                page(app +'/erp_inventory/edit/:id', function (ctx) {
                    lr(app + '/?app=erp_inventory&opt=edit&fd=fd&ID=' + ctx.params.id, 'mainbody');
                    document.title = \"Edit Erp Inventory\";
                });";
        return $rt;
    }

//----------------------------------------------------------------
    function filemanager() {
        $rt['menu'] = menumaker::menu_item("filemanager", "Filemanager", "folder", "Manage files");

        $rt['pagejs'] = "page(app + '/filemanager', function () {
    lr(app + '/?app=filemanager&opt=index&fd=fd', 'mainbody');
    document.title = \"File Manager\";
});
page(app + '/filemanager/folder/:id', function (ctx) {
    lr(app + '/?app=filemanager&opt=index&fd=fd&folderid=' + ctx.params.id, 'mainbody');
    document.title = \"Folder View\";
});";
        return $rt;
    }

    function acc_accounts() {
        $rt['menu'] = null; //menumaker::menu_item("acc_accounts", "Acc Accounts", "check_circle_outline", "Manage Acc Accounts");

        $rt['pagejs'] = "page(app +'/acc_accounts', function () {
                    lr(app + '/?app=acc_accounts&opt=index&fd=fd', 'mainbody');
                    document.title = \"Acc Accounts\";
                });
                page(app +'/acc_accounts/new', function () {
                    lr(app +app + '/?app=acc_accounts&opt=new&fd=fd', 'mainbody');
                    document.title = \"Add Acc Accounts\";
                });

                page(app +'/acc_accounts/edit/:id', function (ctx) {
                    lr(app + '/?app=acc_accounts&opt=edit&fd=fd&ID=' + ctx.params.id, 'mainbody');
                    document.title = \"Edit Acc Accounts\";
                });";
        return $rt;
    }

//-------------------------------------------------Receiving ERP
    function erp_receiveing() {
        $rt['menu'] = menumaker::menu_item("erp_receiveing", "Receiveing/Purchase", "input", "Manage Erp Receiveing");
        $rt['top'] = "<li class=\"nav-item d-none d-sm-inline-block\">"
                . "<a href=\"{$this->kring->coreconf('baseurl')}/erp_receiveing/edit\" class=\"nav-link btn-sm btn-warning\" style=\"margin-right:5px;\" title=\"Alt+R\" id=\"receivedbtn\">"
                . "<span class=\"material-icons\">add_box</span> Purchase</a>"
                . "</li>";
        $rt['pagejs'] = "page(app +'/erp_receiveing', function () {
                    lr(app + '/?app=erp_receiveing&opt=index&fd=fd', 'mainbody');
                    document.title = \"Erp Receiveing\";
                });
                page(app +'/erp_receiveing/edit', function () {
                    lr(app +app + '/?app=erp_receiveing&opt=edit&fd=fd', 'mainbody');
                    document.title = \"Add Erp Receiveing\";
                });

                page(app +'/erp_receiveing/edit/:id', function (ctx) {
                    lr(app + '/?app=erp_receiveing&opt=edit&fd=fd&ID=' + ctx.params.id, 'mainbody');
                    document.title = \"Edit Erp Receiveing\";
                });";
        return $rt;
    }

//----------------------------------------------------
    function accounting() {
        $rt['menu'] = menumaker::groupmenu("Accounting",
                        menumaker::menu_item("erp_expense", "Expenses", "check_circle_outline", "Manage Erp Expense")
                        . menumaker::menu_item("accchartofaccount", "Chart of Accounts", "check_circle_outline", "Manage Products")
                        . menumaker::menu_item("acc_accounts/journal", "Transections", "check_circle_outline", "Journal Entry")
                        . menumaker::menu_item("accchartofaccount/balance", "Accounts Summery", "check_circle_outline", "Journal Entry")
                        , "functions");

        $rt['top'] = "<li class=\"nav-item d-none d-sm-inline-block\">";
        $rt['top'] .= <<<EOTT
                <div class="dropdown">
  <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="material-icons">
note_add
</span>
    Add New
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" href="{$this->kring->coreconf('baseurl')}/erp_transection/new/1">Record Income</a>
    <a class="dropdown-item" href="{$this->kring->coreconf('baseurl')}/erp_transection/new/2">Record Expense</a>
    <a class="dropdown-item" href="{$this->kring->coreconf('baseurl')}/erp_transection/newjarunal">Manual Jaurnal</a>
    <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="{$this->kring->coreconf('baseurl')}/products/new">Add Product</a>
    
  </div>
</div>
                
EOTT;
        $rt['top'] .= "</li>";

        $rt['pagejs'] = <<<EROT
     
                page(app + '/accchartofaccount/balance', function () {
                    lr(app + '/?app=accchartofaccount&opt=balance&fd=fd', 'mainbody');
                });
                page(app + '/accchartofaccount/view/:id', function (ctx) {
                    lr(app + '/?app=accchartofaccount&opt=view&fd=fd&aid='+  ctx.params.id, 'rightblank');
                });
                
                
                
EROT;
        return $rt;
    }

    function accchartofaccount() {
        $menuname = "Chart of accounts";
        $menuurl = "accchartofaccount";
        $menumaticon = "paid";
        $title = "";
        $rt['menu'] = "";
        /* $rt['menu'] = menumaker::groupmenu("TestGroup Menu", menumaker::menu_item($menuurl, $menuname, $menumaticon) .
          menumaker::menu_item($menuurl, "Menu2", $menumaticon) .
          menumaker::menu_item($menuurl, "Menu2", $menumaticon) .
          menumaker::menu_item($menuurl, "Menu2", $menumaticon)); */
        $rt['pagejs'] = <<<ERO
                page(app + '/accchartofaccount', function () {
                lr(app + '/?app=accchartofaccount&opt=index&fd=fd', 'mainbody');
            });
            page(app +'/acc_accounts/new/:id', function (ctx) {
                    lr(app +app + '/?app=acc_accounts&opt=new&fd=fd&ID=' + ctx.params.id, 'rightblank');
                });
            page(app +'/acc_accounts/new/:id/:perentid', function (ctx) {
                    lr(app +app + '/?app=acc_accounts&opt=new&fd=fd&ID=' + ctx.params.id+'&subfor='+ctx.params.perentid, 'rightblank');
                });

                page(app +'/acc_accounts/edit/:id', function (ctx) {
                    lr(app + '/?app=acc_accounts&opt=edit&fd=fd&ID=' + ctx.params.id, 'rightblank');
                });
                

ERO;
        return $rt;
    }

    function accjournal() {

        $rt['menu'] = "";
        /* $rt['menu'] = menumaker::groupmenu("TestGroup Menu", menumaker::menu_item($menuurl, $menuname, $menumaticon) .
          menumaker::menu_item($menuurl, "Menu2", $menumaticon) .
          menumaker::menu_item($menuurl, "Menu2", $menumaticon) .
          menumaker::menu_item($menuurl, "Menu2", $menumaticon)); */
        $rt['pagejs'] = "
            page(app + '/acc_accounts/journal', function () {
                lr(app + '/?app=acc_accounts&opt=journal&fd=fd', 'mainbody');
                document.title = 'Journal';
            });
            
";
        return $rt;
    }

    function erp_expense() {
        $rt['menu'] = ""; //Reffer to Accounting Section

        $rt['pagejs'] = "page(app +'/erp_expense', function () {
                    lr(app + '/?app=erp_expense&opt=index&fd=fd', 'mainbody');
                    document.title = \"Erp Expense\";
                });
                page(app +'/erp_expense/new', function () {
                    lr(app +app + '/?app=erp_expense&opt=new&fd=fd', 'mainbody');
                    document.title = \"Add Erp Expense\";
                });

                page(app +'/erp_expense/edit/:id', function (ctx) {
                    lr(app + '/?app=erp_expense&opt=edit&fd=fd&ID=' + ctx.params.id, 'mainbody');
                    document.title = \"Edit Erp Expense\";
                });";
        return $rt;
    }

//--------------------------------------------------------------
    function erp_sales() {
        $rt['menu'] = menumaker::menu_item("erp_sales", "Sales", "point_of_sale", "Manage Erp Sales");
        $rt['top'] = "<li class=\"nav-item d-none d-sm-inline-block\">"
                . "<a href=\"{$this->kring->coreconf('siteurl')}/counter\" id=\"salesbtn\" class=\"nav-link btn btn-sm btn-success\" style=\"margin-right:5px;\" title=\"Alt+S\">"
                . "<span class=\"material-icons\">add_box</span> POS</a>"
                . "</li>";
        $rt['pagejs'] = "page(app +'/erp_sales', function () {
                    lr(app + '/?app=erp_sales&opt=index&fd=fd', 'mainbody');
                    document.title = \"Erp Sales\";
                });
                page(app +'/erp_sales/new', function () {
                    lr(app +app + '/?app=erp_sales&opt=edit&fd=fd', 'mainbody');
                    document.title = \"Add Erp Sales\";
                });
                page(app +'/erp_sales/return', function () {
                    lr(app +app + '/?app=erp_sales&opt=return&fd=fd', 'mainbody');
                    document.title = \"Add Erp Sales\";
                });

                page(app +'/erp_sales/edit/:id', function (ctx) {
                    lr(app + '/?app=erp_sales&opt=edit&fd=fd&ID=' + ctx.params.id, 'mainbody');
                    document.title = \"Edit Erp Sales\";
                });

                page(app +'/erp_sales/print_invoice/:id', function (ctx) {
                    lr(app + '/?app=erp_sales&opt=print_invoice&fd=fd&sid=' + ctx.params.id, 'mainbody');
                    document.title = \"Edit Erp Sales\";
                });
                
";
        return $rt;
    }

//--------------------------------------------------------------Erp Returns
    function erp_return() {
        $rt['menu'] = menumaker::groupmenu("Return",
                        menumaker::menu_item("erp_return/sales", "Sales Return", "assignment", "assignment_return")
                        . menumaker::menu_item("erp_return/purchase", "Purchase", "inventory")
                        . menumaker::menu_item("erp_return", "All Return", "assignment", "assignment_return")
                        , "assignment_return");

        $rt['pagejs'] = "page(app +'/erp_return', function () {
                    lr(app + '/?app=erp_return&opt=index&fd=fd', 'mainbody');
                    document.title = \"Erp Return\";
                });
                page(app +'/erp_return/sales', function () {
                    lr(app +app + '/?app=erp_return&opt=sales&fd=fd', 'mainbody');
                    document.title = \"Add Erp Return\";
                });
                page(app +'/erp_return/purchase', function () {
                    lr(app +app + '/?app=erp_return&opt=purchase&fd=fd', 'mainbody');
                    document.title = \"Add Erp Return\";
                });

                page(app +'/erp_return/edit/:id', function (ctx) {
                    lr(app + '/?app=erp_return&opt=edit&fd=fd&ID=' + ctx.params.id, 'mainbody');
                    document.title = \"Edit Erp Return\";
                });";
        return $rt;
    }

//-----------------------------------ERP Reportings
    function erp_report() {
        $rt['menu'] = menumaker::groupmenu("Reports",
                        menumaker::menu_item("erp_report", "Sales Report", "assignment", "Reporting")
                        . menumaker::menu_item("erp_report/inventory", "Inventory", "summarize")
                        . menumaker::menu_item("erp_report/category", "Category", "summarize")
                        . menumaker::menu_item("erp_report/items", "items", "summarize")
                        . menumaker::menu_item("erp_report/incomestatment", "Imcome Statement", "summarize")
                        , "assignment");
        $rt['pagejs'] = <<<EOTT
              page(app +'/erp_report', function () {
                    lr(app + '/?app=erp_report&opt=index&fd=fd', 'mainbody');
                    document.title = "Reports";
                });
             page(app +'/erp_report/sales', function () {
                    lr(app + '/?app=erp_report&opt=sales&fd=fd', 'mainbody');
                    document.title = "Reports";
                });
             page(app +'/erp_report/inventory', function () {
                    lr(app + '/?app=erp_report&opt=inventory&fd=fd', 'mainbody');
                });
             page(app +'/erp_report/category', function () {
                    lr(app + '/?app=erp_report&opt=category&fd=fd', 'mainbody');
                    document.title = "Reports";
                });
             page(app +'/erp_report/items', function () {
                    lr(app + '/?app=erp_report&opt=items&fd=fd', 'mainbody');
                    document.title = "Reports";
                });
             page(app +'/erp_report/incomestatment', function () {
                    lr(app + '/?app=erp_report&opt=incomestatment&fd=fd', 'mainbody');
                    document.title = "Reports";
                });
  
EOTT;
        return $rt;
    }

    function user() {
        $rt['menu'] = menumaker::menu_item("user", "Staff User", "manage_accounts", "Manage Staff");

        $rt['pagejs'] = "page(app +'/appsettings', function () {
                    lr(app + '/?app=appsettings&opt=index&fd=fd', 'mainbody');
                    document.title = \"Application Settings\";
                });
                
";
        return $rt;
    }

    function appsettings() {
        $rt['menu'] = menumaker::menu_item("appsettings", "App Settings", "tune", "Manage Staff");

        $rt['pagejs'] = "page(app +'/user', function () {
                    lr(app + '/?app=user&opt=index&fd=fd', 'mainbody');
                    document.title = \"User\";
                });
                page(app +'/user/new', function () {
                    lr(app +app + '/?app=user&opt=new&fd=fd', 'mainbody');
                    document.title = \"Add User\";
                });

                page(app +'/user/view/:id', function (ctx) {
                    lr(app + '/?app=user&opt=view&fd=fd&ID=' + ctx.params.id, 'mainbody');
                    document.title = \"Edit User\";
                });

                page(app +'/user/edit/:id', function (ctx) {
                    lr(app + '/?app=user&opt=edit&fd=fd&ID=' + ctx.params.id, 'mainbody');
                    document.title = \"Edit User\";
                });";
        return $rt;
    }

}
