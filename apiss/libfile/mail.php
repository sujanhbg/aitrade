<?php

namespace kring\core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use kring\database as db;
use kring\utilities\comm;

class mail
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

    function escStr($str)
    {
        if ($str > 0) {
            return $this->dbal()->conn()->real_escape_string($str);
        } else {
            return '';
        }
    }

    function kring()
    {
        return new \kring\core\kring();
    }

    function html2text($Document)
    {
        $Rules = array(
            '@<script[^>]*?>.*?</script>@si',
            '@<[\/\!]*?[^<>]*?>@si',
            '@([\r\n])[\s]+@',
            '@&(quot|#34);@i',
            '@&(amp|#38);@i',
            '@&(lt|#60);@i',
            '@&(gt|#62);@i',
            '@&(nbsp|#160);@i',
            '@&(iexcl|#161);@i',
            '@&(cent|#162);@i',
            '@&(pound|#163);@i',
            '@&(copy|#169);@i',
            '@&(reg|#174);@i',
            '@&#(d+);@e'
        );
        $Replace = array(
            '',
            '',
            '',
            '',
            '&',
            '<',
            '>',
            ' ',
            chr(161),
            chr(162),
            chr(163),
            chr(169),
            chr(174),
            'chr()'
        );
        return preg_replace($Rules, $Replace, $Document);
    }

    function compose($subject, $mailbody, $mailto, $recepent_name = null, $attachments = null, $mailcc = null)
    {
        $sql = "INSERT INTO `email_out_box`( `subject`, `mailbody`, `attachments`, `mailto`, `rcp_name`, `mailcc`, `compose_time`) VALUES ('{$this->escStr($subject)}','{$this->escStr($mailbody)}','{$this->escStr($attachments)}','{$this->escStr($mailto)}','{$this->escStr($recepent_name)}','{$this->escStr($mailcc)}',NOW())";
        //echo $sql;
        $this->dbal()->query_exc($sql);
    }

    function template($templatenumber, $key, $replace)
    {
        $subject = $this->dbal()->get_single_result("SELECT `email_title` FROM `email_template` WHERE `ID`={$templatenumber}");
        $body = $this->dbal()->get_single_result("SELECT `email_body` FROM `email_template` WHERE `ID`={$templatenumber}");
        $rt['subject'] = str_replace($key, $replace, $subject);
        $rt['body'] = str_replace($key, $replace, $body);
        return $rt;
    }

    function send_mail($templatenumber, $key, $replace, $mailto)
    {
        $subject = $this->template($templatenumber, $key, $replace)['subject'];
        $body = $this->template($templatenumber, $key, $replace)['body'];
        $this->compose($subject, $body, $mailto);
    }

    function send_mail_now($templatenumber, $key, $replace, $mailto, $recepent_name)
    {
        /*$mail = new PHPMailer(true);
        $subject = $this->template($templatenumber, $key, $replace)['subject'];
        $body = $this->template($templatenumber, $key, $replace)['body'];
        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                        //Enable verbose debug output
            $mail->isSMTP();                                                //Send using SMTP
            $mail->Host = 'smtppro.zoho.com';                           //Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   //Enable SMTP authentication
            $mail->Username = 'info@kalni-it.com';                     //SMTP username
            $mail->Password = 'mCrJRqANAFKg';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            //Recipients
            $mail->setFrom('info@kalni-it.com', 'Kalni-IT Front Desk');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //Content
            $mail->isHTML(true);
            $mail->addAddress($mailto, $recepent_name);
            $mail->Subject = $subject;
            $mail->Body = $body;
            //Set email format to HTML


            $mail->send();*/
        return 1;
        /*} catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }*/
    }
}
