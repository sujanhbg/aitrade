<?php

use kring\database as db;
use kring\utilities\comm;

class Model_products
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

    function escStr($str)
    {
        return $this->dbal()->conn()->real_escape_string($str);
    }


    function productsViewdata()
    {
        return $this->dbal()->query("SELECT 
				`ID`,
				`product_name`,
				`category`,
				`barcode`,
				`brand_name`,
				`supplier_ID`,
				`cost_price`,
				`sale_price`,
				`discounted_price`,
				`pic_url`
                                FROM products
                                WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }




    function productsValidationRules()
    {
        return [
            'product_name'  =>  'required|min_len,1',
            'category'  =>  'required|min_len,1',
            'brand_name'  =>  'required|min_len,1',
            'supplier_ID'  =>  'required|min_len,1',
            'cost_price'  =>  'required|min_len,1',
            'sale_price'  =>  'required|min_len,1',
            'discounted_price'  =>  'required|min_len,1',
            'pic_url'  =>  'required|min_len,1'
        ];
    }




    function productsValidationMessage()
    {
        return [
            'ID' => ['required' => 'ID  is required.', 'min_len' => 'Invalid ID'],
            'product_name' => ['required' => 'Product Name  is required.', 'min_len' => 'Invalid product_name'],
            'category' => ['required' => 'Category  is required.', 'min_len' => 'Invalid category'],
            'barcode' => ['required' => 'Barcode  is required.', 'min_len' => 'Invalid barcode'],
            'brand_name' => ['required' => 'Brand Name  is required.', 'min_len' => 'Invalid brand_name'],
            'supplier_ID' => ['required' => 'Supplier ID  is required.', 'min_len' => 'Invalid supplier_ID'],
            'cost_price' => ['required' => 'Cost Price  is required.', 'min_len' => 'Invalid cost_price'],
            'sale_price' => ['required' => 'Sale Price  is required.', 'min_len' => 'Invalid sale_price'],
            'discounted_price' => ['required' => 'Discounted Price  is required.', 'min_len' => 'Invalid discounted_price'],
            'pic_url' => ['required' => 'Pic Url  is required.', 'min_len' => 'Invalid pic_url']
        ];
    }


    function productsFilterRules()
    {
        return [
            'ID'  =>  'trim|sanitize_string|basic_tags',
            'product_name'  =>  'trim|sanitize_string|basic_tags',
            'category'  =>  'trim|sanitize_string|basic_tags',
            'barcode'  =>  'trim|sanitize_string|basic_tags',
            'brand_name'  =>  'trim|sanitize_string|basic_tags',
            'supplier_ID'  =>  'trim|sanitize_string|basic_tags',
            'cost_price'  =>  'trim|sanitize_string|basic_tags',
            'sale_price'  =>  'trim|sanitize_string|basic_tags',
            'discounted_price'  =>  'trim|sanitize_string|basic_tags',
            'pic_url'  =>  'trim|sanitize_string|basic_tags'
        ];
    }

    function datasource()
    {
        $pd = json_decode(file_get_contents('php://input'), true);
        $data['draw'] = isset($pd['draw']) ? $pd['draw'] : 1;
        $start = isset($pd['start']) ? $pd['start'] : 0;
        $length = isset($pd['length']) ? $pd['length'] : 10;
        $table = "products";
        $selectdata = ['ID', 'product_name', 'category', 'barcode', 'brand_name', 'supplier_ID', 'cost_price', 'sale_price', 'discounted_price', 'vat', 'pic_url'];
        $orderby = dtv::orderby($selectdata, $table);
        $search = dtv::dt_search($selectdata, "products.deleted=0", $table);
        $fields = dtv::fields($selectdata, $table);
        $supplierq = $_GET['supplier'] == 0 ? "" : "AND products.supplier_ID={$this->comm()->rqstr('supplier')}";
        //for Join Query
        $stock = "(SELECT SUM(erp_inventory.tr_qty) FROM erp_inventory WHERE erp_inventory.item_id=products.ID) as stock";
        $fields2 = dtv::fields(['company_name', 'supplier_name'], 'suppliers');
        $sql = "SELECT {$fields},{$fields2},{$stock} FROM products INNER JOIN suppliers ON products.supplier_ID=suppliers.ID WHERE {$search} {$supplierq} {$orderby}";
        $limit = " LIMIT {$start},{$length}";
        $data['recordsTotal'] = $this->dbal()->num_of_row($sql);
        $data['recordsFiltered'] = $this->dbal()->num_of_row($sql);
        $data['data'] = $this->dbal()->querydt($sql . $limit);
        return $data;
    }

    function products_dbvalid($data)
    {
        $cond = "SELECT ID FROM products WHERE ";
        foreach ($data as $serv => $sdata) {
            $cond .= " " . $serv . "='" . $sdata . "' OR";
        }
        $condi = trim($cond, "OR");
        if ($this->dbal()->num_of_row($condi) > 0) {
            return false;
        } else {
            return true;
        }
    }



    function products_CheckValid()
    {

        $gump = new \GUMP();
        $gump->set_fields_error_messages($this->productsValidationMessage());
        $data = array($_REQUEST['fname'] => $_REQUEST['fval']);
        $validated = $gump->is_valid($data, array_intersect_key($this->productsValidationRules(), array_flip(array($_REQUEST['fname']))));
        $dbvalid = $this->products_dbvalid([$_REQUEST['fname'] => $_REQUEST['fval']]);

        if ($validated === true) {
            if ($_REQUEST['fname'] == "email" && $dbvalid == false) {
                $return = "<span style='color:red'><i class='fa fa-times' aria-hidden='true'></i>"
                    . " {$_REQUEST['fval']} already exists</span>";
            } else {
                $return = 1;
            }
        } else {

            $return = "<span style='color:red'><i class='fa fa-times' aria-hidden='true'></i> ";
            $return .= $validated[0] . "</span>";
        }
        return $return;
    }





    function productsnew__record_create()
    {
        $gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->productsValidationRules());
        $gump->filter_rules($this->productsFilterRules());
        $gump->set_fields_error_messages($this->productsValidationMessage());
        $validated_data = $gump->run($_POST);
        $dbvalidation = null;
        //if($this->check_exits("students", "title={$validated_data['title']}")){$dbvalidation.="Data Already Exits";}
        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            if ($dbvalidation == null) {
                //$return= $validated_data['cellnumber'];
                $insertsql = "INSERT INTO  `products` (
            `product_name`,
`category`,
`product_description`,
`barcode`,
`brand_name`,
`supplier_ID`,
`unit_name`,
`cost_price`,
`sale_price`,
`discounted_price`,
`reorder_level`,
`receiving_quantity`,
`pic_url`,
`model`,
`vat`,
`has_sl`,
`has_warranty`,
`def_warranty_perion`)
            VALUES
            ('{$this->escStr($validated_data['product_name'])}',
'{$this->escStr($validated_data['category'])}',
'{$this->escStr($validated_data['product_description'])}',
'{$this->escStr($validated_data['barcode'])}',
'{$this->escStr($validated_data['brand_name'])}',
'{$this->escStr($validated_data['supplier_ID'])}',
'{$this->escStr($validated_data['unit_name'])}',
'{$this->escStr($validated_data['cost_price'])}',
'{$this->escStr($validated_data['sale_price'])}',
'{$this->escStr($validated_data['discounted_price'])}',
'{$this->escStr($validated_data['reorder_level'])}',
'{$this->escStr($validated_data['receiving_quantity'])}',
'{$this->escStr($validated_data['pic_url'])}',
'{$this->escStr($validated_data['model'])}',
'{$this->escStr($validated_data['vat'])},
'{$this->escStr($validated_data['has_sl'])}',
'{$this->escStr($validated_data['has_warranty'])}',
'{$this->escStr($validated_data['def_warranty_perion'])}');";

                if ($this->dbal()->query_exc($insertsql)) {
                    $return = 1;
                    /*
						$this->dbal()->editLog("products", "product_name", $this->escStr($validated_data['product_name']));
$this->dbal()->editLog("products", "category", $this->escStr($validated_data['category']));
$this->dbal()->editLog("products", "product_description", $this->escStr($validated_data['product_description']));
$this->dbal()->editLog("products", "barcode", $this->escStr($validated_data['barcode']));
$this->dbal()->editLog("products", "brand_name", $this->escStr($validated_data['brand_name']));
$this->dbal()->editLog("products", "supplier_ID", $this->escStr($validated_data['supplier_ID']));
$this->dbal()->editLog("products", "unit_name", $this->escStr($validated_data['unit_name']));
$this->dbal()->editLog("products", "cost_price", $this->escStr($validated_data['cost_price']));
$this->dbal()->editLog("products", "sale_price", $this->escStr($validated_data['sale_price']));
$this->dbal()->editLog("products", "discounted_price", $this->escStr($validated_data['discounted_price']));
$this->dbal()->editLog("products", "reorder_level", $this->escStr($validated_data['reorder_level']));
$this->dbal()->editLog("products", "receiving_quantity", $this->escStr($validated_data['receiving_quantity']));
$this->dbal()->editLog("products", "pic_url", $this->escStr($validated_data['pic_url']));
$this->dbal()->editLog("products", "model", $this->escStr($validated_data['model']));
$this->dbal()->editLog("products", "vat", $this->escStr($validated_data['vat']));

						*/
                } else {
                    $return = "<span class=\"validerror\">"
                        . "We are Sorry; We can not record your Input to our Database Server</span>";
                }
            } else {
                $return = "<span class=\"validerror\">$dbvalidation</span>";
            }
        }
        return $return;
    }





    function get_productsEditData()
    {
        return $this->dbal()->query("SELECT * FROM products WHERE `ID`='{$this->comm()->rqstr('ID')}' LIMIT 1");
    }




    function productsedited_data_save()
    {
        $gump =  new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->productsValidationRules());
        $gump->filter_rules($this->productsFilterRules());
        $gump->set_fields_error_messages($this->productsValidationMessage());
        $validated_data = $gump->run($_POST);

        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            $dbvalidation = true; //$this->products_dbvalid(['email' => $validated_data['email'], 'cell' => $validated_data['cell']]);
            if ($dbvalidation == true) {
                //$return= $validated_data['cellnumber'];
                $editsql = "UPDATE  products SET 
				`product_name` =  '{$this->escStr($validated_data['product_name'])}',
				`category` =  '{$this->escStr($validated_data['category'])}',
				`product_description` =  '{$this->escStr($validated_data['product_description'])}',
				`barcode` =  '{$this->escStr($validated_data['barcode'])}',
				`brand_name` =  '{$this->escStr($validated_data['brand_name'])}',
				`supplier_ID` =  '{$this->escStr($validated_data['supplier_ID'])}',
				`unit_name` =  '{$this->escStr($validated_data['unit_name'])}',
				`cost_price` =  '{$this->escStr($validated_data['cost_price'])}',
				`sale_price` =  '{$this->escStr($validated_data['sale_price'])}',
				`discounted_price` =  '{$this->escStr($validated_data['discounted_price'])}',
				`pic_url` =  '{$this->escStr($validated_data['pic_url'])}',
				`model` =  '{$this->escStr($validated_data['model'])}',
				`vat` =  '{$this->escStr($validated_data['vat'])}',
				`has_sl` =  '{$this->escStr($validated_data['has_sl'])}',
				`has_warranty` =  '{$this->escStr($validated_data['has_warranty'])}',
				`def_warranty_perion` =  '{$this->escStr($validated_data['def_warranty_perion'])}' WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1";

                if ($this->dbal()->update_database($editsql)) {
                    $return = 1;
                    /*
			$this->dbal()->editLog("products", "product_name", $this->escStr($validated_data['product_name']));
$this->dbal()->editLog("products", "category", $this->escStr($validated_data['category']));
$this->dbal()->editLog("products", "product_description", $this->escStr($validated_data['product_description']));
$this->dbal()->editLog("products", "barcode", $this->escStr($validated_data['barcode']));
$this->dbal()->editLog("products", "brand_name", $this->escStr($validated_data['brand_name']));
$this->dbal()->editLog("products", "supplier_ID", $this->escStr($validated_data['supplier_ID']));
$this->dbal()->editLog("products", "unit_name", $this->escStr($validated_data['unit_name']));
$this->dbal()->editLog("products", "cost_price", $this->escStr($validated_data['cost_price']));
$this->dbal()->editLog("products", "sale_price", $this->escStr($validated_data['sale_price']));
$this->dbal()->editLog("products", "discounted_price", $this->escStr($validated_data['discounted_price']));
$this->dbal()->editLog("products", "reorder_level", $this->escStr($validated_data['reorder_level']));
$this->dbal()->editLog("products", "receiving_quantity", $this->escStr($validated_data['receiving_quantity']));
$this->dbal()->editLog("products", "pic_url", $this->escStr($validated_data['pic_url']));
$this->dbal()->editLog("products", "model", $this->escStr($validated_data['model']));
$this->dbal()->editLog("products", "vat", $this->escStr($validated_data['vat']));

			*/
                } else {
                    $return = "<span class=\"validerror\">"
                        . "We are Sorry; We can not save your update</span>";
                }
            } else {
                $return = "<span class=\"validerror\">Data Exists!</span>";
            }
        }
        return $return;
    }


    function get_for_select()
    {
        return $this->dbal()->query("SELECT * FROM `products` WHERE `deleted`=0");
    }

    function productsDeleteSql()
    {
        return $this->dbal()->update_database("UPDATE  products SET `deleted` =  '1'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }



    function productsRestoreSql()
    {
        return $this->dbal()->update_database("UPDATE  products SET `deleted` =  '0'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }

    function get_categorydataset()
    {
        return $this->dbal()->query('SELECT `category_name` FROM product_catagory WHERE `deleted`=0');
    }

    function get_supplier_ID_options_data()
    {
        return $this->dbal()->query('SELECT `ID`,`company_name`,`supplier_name` FROM suppliers WHERE `deleted`=0');
    }

    function get_unit_name_options_data()
    {
        return $this->dbal()->query('SELECT `unit_name` FROM product_unit WHERE `deleted`=0');
    }
    function filepath($pID)
    {
        $kring = new \kring\core\Kring();
        !is_dir($kring->coreconf('filepath') . "/products") ? mkdir($kring->coreconf('filepath') . "/products", 0777) : false;
        return $kring->coreconf('filepath') . "/products/{$pID}";
    }
    function image_links()
    {
        $pID = $this->comm()->rqstr('pid');
        if (!$pID) {
            return 'Product Not found';
        } else {


            $imageFolder = $this->filepath($pID);
            if (!is_dir($imageFolder)) {
                mkdir($imageFolder);
            }
            return array_values(array_diff(scandir($imageFolder . "/thumb"), array('..', '.')));
        }
    }
    function accessUrlDir($pID)
    {
        $kring = new \kring\core\Kring();
        return $kring->coreconf('fileurl') . "/products/{$pID}";
    }

    //------------------Upload Product Image
    function resize_image($source, $target, $width = 200, $jpeg_quality = 90)
    {
        $image = new Zebra_Image();
        $image->auto_handle_exif_orientation = false;
        $image->source_path = $source;
        $image->target_path = $target;
        $image->jpeg_quality = $jpeg_quality;
        $image->preserve_aspect_ratio = true;
        $image->enlarge_er_images = true;
        $image->preserve_time = true;
        $image->handle_exif_orientation_tag = true;
        if (!$image->resize($width, 0, ZEBRA_IMAGE_CROP_CENTER)) {
            switch ($image->error) {

                case 1:
                    $return = 'Source file could not be found!';
                    break;
                case 2:
                    $return = 'Source file is not readable!';
                    break;
                case 3:
                    $return = 'Could not write target file!';
                    break;
                case 4:
                    $return = 'Unsupported source file format!';
                    break;
                case 5:
                    $return = 'Unsupported target file format!';
                    break;
                case 6:
                    $return = 'GD library version does not support target file format!';
                    break;
                case 7:
                    $return = 'GD library is not installed!';
                    break;
                case 8:
                    $return = '"chmod" command is disabled via configuration!';
                    break;
                case 9:
                    $return = '"exif_read_data" function is not available';
                    break;
            }
        } else {
            $return = 'Success!';
        }
        return $return;
    }

    function uploadFile()
    {
        $pID = $this->comm()->rqstr('pid');
        $imageUrldir = $this->accessUrlDir($pID);
        $target_file = $this->filepath($pID) . "/" . basename($_FILES["fileToUpload"]["name"]);
        $target_dir = $this->filepath($pID);

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777);
        }
        if (!is_dir($target_dir . "/thumb")) {
            mkdir($target_dir . "/thumb", 0777);
        }
        $uploadOk = 1;
        $product_name = str_replace([' ', '&'], ['_', '_and_'], $this->comm()->rqstr('pname'));
        $imagename = strtolower($product_name . "_" . substr(md5(time()), 0, 4));
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                //$return= "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $error = "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $error = "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 5000000) {
            $error = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        //        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "webp") {
        if ($imageFileType != "webp" && $imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
            $error = "Sorry, only webp, jpg, png files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            return ["uploaded" => $uploadOk, "filepath" => $target_dir . "/" . $imagename . "." . $imageFileType, "error" => ["message" => $error]];
            // if everything is ok, try to upload file
        } else {

            $newOrginalFile = $target_dir . "/" . $imagename . "." . $imageFileType;
            $newThumbFile = $target_dir . "/thumb/" . $imagename . "." . $imageFileType;
            $webpOrginalFile = $target_dir . "/" . $imagename . ".webp";
            $webThumbFile = $target_dir . "/thumb/" . $imagename . ".webp";


            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $newOrginalFile)) {
                $resize = $this->resize_image($newOrginalFile, $newThumbFile);
                if ($imageFileType == "png") {
                    $img = imagecreatefrompng($newOrginalFile);
                    imagepalettetotruecolor($img);
                    imagealphablending($img, true);
                    imagesavealpha($img, true);
                    imagewebp($img, $webpOrginalFile, 80);
                    imagedestroy($img);
                    //-convert image from thumb file
                    $img = imagecreatefrompng($newThumbFile);
                    imagepalettetotruecolor($img);
                    imagealphablending($img, true);
                    imagesavealpha($img, true);
                    imagewebp($img, $webThumbFile, 80);
                    imagedestroy($img);
                    unlink($newOrginalFile);
                    unlink($newThumbFile);
                } elseif ($imageFileType == 'jpg' || $imageFileType == 'jpeg') {
                    $img = imagecreatefromjpeg($newOrginalFile);
                    imagepalettetotruecolor($img);
                    imagealphablending($img, true);
                    imagesavealpha($img, true);
                    imagewebp($img, $webpOrginalFile, 80);
                    imagedestroy($img);
                    //convert to thumb
                    $img = imagecreatefromjpeg($newThumbFile);
                    imagepalettetotruecolor($img);
                    imagealphablending($img, true);
                    imagesavealpha($img, true);
                    imagewebp($img, $webThumbFile, 80);
                    imagedestroy($img);
                    unlink($newOrginalFile);
                    unlink($newThumbFile);
                } else {
                }
                $stdurl = $imageUrldir . "/thumb/" . $imagename . ".webp";
                $lurl = $imageUrldir . "/" . $imagename . ".webp";
                return ["uploaded" => $uploadOk, "response" => "File has been Uploaded; " . $resize, "fileName" => $imagename, "url" => $stdurl, "lurl" => $lurl];
            } else {
                return ["uploaded" => $uploadOk, "error" => ["message" => "Permission Error:{$newOrginalFile}"]];
            }
        }
    }
    function setmainimage()
    {
        $pID = $this->comm()->rqstr('pid');
        $image = $this->comm()->rqstr('image');
        return $this->dbal()->update_database("UPDATE `products` SET `pic_url` = '{$image}' WHERE `products`.`ID` = {$pID};");
    }

    function inventory_history()
    {
        $pID = $this->comm()->rqstr('pid');
        return $this->dbal()->query("
        SELECT
    `erp_inventory`.`ID`,
    `erp_inventory`.`item_id`,
    `erp_inventory`.`tr_qty`,
    `erp_inventory`.`tr_comment`,
    `erp_inventory`.`employe_id`,
    `erp_inventory`.`shopid`,
    `erp_inventory`.`tr_date`,
    `user`.`firstname`,
    `user`.`lastname`
FROM
    `erp_inventory`
INNER JOIN `user` ON `erp_inventory`.`employe_id` = `user`.`ID`
WHERE
    `erp_inventory`.`item_id` = {$pID}
        ");
    }
    function get_supplier()
    {
        return $this->dbal()->query("SELECT `ID`,`supplier_name`,`company_name` FROM `suppliers` WHERE `deleted`=0");
    }
}
