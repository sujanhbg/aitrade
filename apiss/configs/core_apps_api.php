<?php

/*
 * Copyright (c) 2020, sjnx
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *
 * * Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

/*
 * Call default controller if router/Url not defined
 */
$userid = isset($_SESSION['UsrID']) ? $_SESSION['UsrID'] : null;
$host = isset($_REQUEST['host']) ? $_REQUEST['host'] : "metrocomplexbd";
$core['defaultController'] = "Home";
/*
 * Call Default method of controller class for render
 */
$core['defaultMethod'] = "index";
/*
 * default version is cockie based;
 *
 */
$core['defaultVersion'] = "dev-master"; //$userid == 10023601 ? "dev-master" : "dev-master";
$core['baseurl'] = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? "https://" : "http://";
$core['baseurl'] .= $host . "/api_kbiz";
$core['siteurl'] = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? "https://" : "http://";
$core['siteurl'] .= $host . ".phplab.xyz";
$core['theme'] = dirname(__DIR__) . "/apps_api_kbiz/" . $core['defaultVersion'] . "/views";
$core['documentpath'] = dirname(__DIR__) . "/kdata/docs/";
$core['filepath'] = dirname(__DIR__) . "/public/files/" . explode(".", $host)[0];
$core['fileurl'] = $core['siteurl'] . "/files/" . explode(".", $host)[0];
$core['accesslimit'] = false;
$core['AllowedCountry'] = ["Bangladesh"];
$core['AllowProxy'] = false;
$core['advancedPermission'] = false;
/* 
 * True if web want to save visitor log in database
 * it need to more space to database
 * couse this save every visitor request of website
 */
$core['SaveLogInDb'] = false;

/*
 * Save IP Data in /kdata/ipdata directory
 */
$core['SaveIpDataInFile'] = false;

/*
 * Get Config from Database
 */
$core['GetCnfValFromDB'] = false;

/*
 * If False That can be allow user without athentication method
 */
$core['loginwithDB'] = true;
//helperFile is a helper class, which is called dynamaclly
$core['helpersClass'] = "main";
//Define the application type
$core['apptype'] = "restapi_json";
