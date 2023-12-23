<?php

use kring\core\Controller;

class Auth extends Controller
{

   public $adminarea;
   public function __construct()
   {
      parent::__construct();
      $this->adminarea = 0;
   }

   public function model()
   {
      return $this->loadESmodel('auth');
   }

   public function index()
   {
      $data['pretty'] = true;
      $data['msg']    = "Kalni Rest API V 1.00 for KBS";
      return $data;
   }

   public function login()
   {
      return $this->model()->login();
   }
   public function logout()
   {
   }
   public function register()
   {
   }
   public function user()
   {
      return $this->model()->get_user_info();
   }
   function is_in()
   {
      return $this->model()->is_in();
   }
}
