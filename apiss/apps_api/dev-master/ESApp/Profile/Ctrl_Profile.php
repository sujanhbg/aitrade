    <?php

    use kring\core\Controller;

    class Profile extends Controller
    {

        public $adminarea;

        function __construct()
        {
            parent::__construct();
            $this->adminarea = 1;
        }

        function model()
        {
            return $this->loadESmodel('profile', 'Profile');
        }

        function index($pr)
        {
        }
        function info()
        {
            return $this->model()->getUserData();
        }
        function save()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($this->model()->useredited_data_save() == 1) {
                    $data['status'] = 'success';
                    $data['message'] = '';
                } else {
                    $data['status'] = 'error';
                    $data['message'] = 'Please try again leter';
                }
            } else {
                $data['status'] = 'error';
                $data['message'] = 'Opps';
            }
            return $data;
        }
    }
