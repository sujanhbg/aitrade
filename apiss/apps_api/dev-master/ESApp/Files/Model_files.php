<?php

use kring\database as db;
use kring\utilities\comm;

class Model_files
{

    function comm()
    {
        return new comm();
    }

    function dbal()
    {
        return new db\dbal();
    }

    function filepath()
    {
        $kring = new \kring\core\Kring();
        return $kring->coreconf('filepath');
    }

    function accessUrlDir()
    {
        $kring = new \kring\core\Kring();
        return $kring->coreconf('fileurl');
    }

    function get_folders($folderid = 1)
    {
        if ($folderid == 1) {
            return $this->dbal()->query("SELECT * FROM `files_folder` WHERE `sub_id`=0 AND `deleted`=0");
        } else {
            return $this->dbal()->query("SELECT * FROM `files_folder` WHERE `sub_id`={$folderid} AND `deleted`=0");
        }
    }

    function get_fileList($folderid)
    {
        return $this->dbal()->query("SELECT * FROM `files` WHERE `folder_id`={$folderid} AND `deleted`=0");
    }

    function get_upFolderID($curentfolder)
    {
        return $this->dbal()->get_single_result("SELECT `sub_id` FROM `files_folder` WHERE `ID`={$curentfolder} LIMIT 1");
    }

    function get_iconSelectData()
    {
        return $this->dbal()->query('SELECT `icon_name`,`icon_name` FROM file_icons WHERE `deleted`=0');
    }

    function save_folder()
    {
        $foldername = preg_replace('/[^A-Za-z0-9\-]/', '', $_POST['newfoldername']);
        $folderid = $this->comm()->rqstr('folderid') == 1 ? 0 : $this->comm()->rqstr('folderid');
        $icon = $this->comm()->rqstr('icon');
        $dbvalidation = $this->dbal()->num_of_row("SELECT ID FROM `files_folder` WHERE `folder_name`='$foldername' AND `sub_id`={$folderid}") ? true : false;
        $insertsql = "INSERT INTO  `files_folder` (
`folder_name`,
`sub_id`,
`icon`)
            VALUES
            (
'{$this->dbal()->conn()->real_escape_string($foldername)}',
'{$this->dbal()->conn()->real_escape_string($folderid)}',
'{$this->dbal()->conn()->real_escape_string($icon)}');";
        if ($dbvalidation == true) {
            return "Folder Name Already in exists";
        } else {
            if ($this->dbal()->query_exc($insertsql)) {
                $return = 1;
            } else {
                $return = ""
                    . "We are Sorry; We can not record your Input to our Database Server";
            }
        }


        return $return;
    }

    function get_files_folderEditData()
    {
        return $this->dbal()->query("SELECT * FROM files_folder WHERE `ID`='{$this->comm()->rqstr('ID')}' LIMIT 1");
    }

    function files_folderedited_data_save()
    {
        $gump = new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->files_folderValidationRules());
        $gump->filter_rules($this->files_folderFilterRules());
        $gump->set_fields_error_messages($this->files_folderValidationMessage());
        $validated_data = $gump->run($_POST);

        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            $dbvalidation = true; //$this->files_folder_dbvalid(['email' => $validated_data['email'], 'cell' => $validated_data['cell']]);
            if ($dbvalidation == true) {
                //$return= $validated_data['cellnumber'];
                $editsql = "UPDATE  files_folder SET
				`folder_name` =  '{$this->dbal()->conn()->real_escape_string($validated_data['folder_name'])}',
				`sub_id` =  '{$this->dbal()->conn()->real_escape_string($validated_data['sub_id'])}',
				`icon` =  '{$this->dbal()->conn()->real_escape_string($validated_data['icon'])}' WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1";

                if ($this->dbal()->update_database($editsql)) {
                    $return = 1;
                } else {
                    $return = ""
                        . "We are Sorry; We can not save your update";
                }
            } else {
                $return = "Data Exists!";
            }
        }
        return $return;
    }

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

    function uploadFile($folderid)
    {
        $target_file = $this->filepath() . "/" . basename($_FILES["fileToUpload"]["name"]);
        $target_dir = $this->filepath();
        $uploadOk = 1;
        $imagename = strtolower(md5(time() . pathinfo($target_file, PATHINFO_FILENAME)));
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $imageUrldir = $this->accessUrlDir();
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
        if ($imageFileType != "webp" && $imageFileType != "jpg" && $imageFileType != "png") {
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

            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $newOrginalFile)) {

                $resize = $this->resize_image($newOrginalFile, $newThumbFile);
                $sql = "INSERT INTO `files`( `folder_id`, `file_name`, `file_type`) VALUES ('{$folderid}','{$imagename}','{$imageFileType}')";
                $this->dbal()->query_exc($sql);

                $stdurl = $imageUrldir . "/thumb/" . $imagename . "." . "$imageFileType";
                $lurl = $imageUrldir . "/" . $imagename . "." . "$imageFileType";
                return ["uploaded" => $uploadOk, "response" => "File has been Uploaded; " . $resize, "fileName" => $imagename, "url" => $stdurl, "lurl" => $lurl];
            } else {
                return ["uploaded" => $uploadOk, "error" => ["message" => "Permission Error:{$newOrginalFile}"]];
            }
        }
    }
    function rename_folder($folderID)
    {
        $folderName = $_POST['foldername'];
        return $this->dbal()->update_database("UPDATE `files_folder` SET `folder_name`='{$folderName}' WHERE `ID`={$folderID} LIMIT 1;");
    }
}
