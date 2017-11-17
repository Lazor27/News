<?php

 // controller for log in and log out 
 
class UsersController extends Controller{

    
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new User();
    }

        public function login()
    {
        // data for advertising
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);

        if ($_POST) {
            if (!empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['capcha'])) {
                if($_POST['capcha'] == Session::get('capcha')){

                $user = $this->model->getByLogin($_POST['login']); // false or true
                $hash = md5(Config::get('salt') . $_POST['password']);

                if ($user && $hash == $user['password']) {
                    Session::set('id', $user['id']);
                    Session::set('login', $user['login']);
                    Session::set('role', $user['role']);
                }else{
                     Session::setFlash('Login and password are incorrect');
                     return false;
                }

                if (Session::get('role') == 'admin') {
                    Router::redirect('/admin/');
                } else  {
                    Router::redirect('/user/');
                }}else{Session::setFlash('Please check captcha!');}
             
         }else{
        Session::setFlash('Please fill in all fields');
            
    }
        
    } 
}
    
    
    public function logout()
    {
        Session::destroy();
        Router::redirect('/');
    }

    
    public function registration()
    {
        // data for advertising
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);


        if ($_POST) {
            if (!empty($_POST['first_name']) && !empty($_POST['second_name'])&& !empty($_POST['login_name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['date_of_birth']) && !empty($_POST['capcha']) ) {
                    if($_POST['capcha'] == Session::get('capcha')){

                $first_name  = $_POST['first_name'];
                $second_name = $_POST['second_name'];
                $login       = $_POST['login_name'];
                $email       = $_POST['email'];
                $password    = md5(Config::get('salt').$_POST['password']); // salt + password
                $date        = $_POST['date_of_birth'];

                // php filter for validation emails
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Session::setFlash('Please enter real email address!');
                }
                
                // check email
                if ($this->model->getByEmail($email)) {
                    Session::setFlash('This email is used!');
                    return false;
                }
                
                // check login
                if ($this->model->getByLogin($login)) {
                    Session::setFlash('The login is used!');
                    return false;
                }
                
                $this->model->registerUser($first_name, $second_name, $login, $email, $password, $date);

                
                
                Router::redirect('/users/login/'); // to the home page
                }else{
                    Session::setFlash('Please check captcha!');

                }
            } else { 
                Session::setFlash('Please fill in all fields!');
            }
        } else {
            return false;
        }
    }

   
    public function index()
    {
        // we have data variable in parent class
        $this->data['users'] = $this->model->getUsersList();

        // data for advertising
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }

    
    public function view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = strtolower($params[0]);
            $page = isset($params[1]) ? (int)$params[1] : 1;
            $this->data['user'] = $this->model->getUserById($id);
            $this->data['user_comments'] = $this->model->getUserComments($id,$page);

            
            $itemsCount = count($this->model->getUserComments($id));
            $p = new Pagination(array(
                'itemsCount' => $itemsCount,
                'itemsPerPage' => 10,
                'currentPage' => $page
                ));


            }
        
        

        $this->data['p'] = $p;
        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['user_comments'] as $comment) {
            $this->data['pagination_comment'][$count_rows/20][] = $comment;
            $count_rows++;
        }

    
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }
    
    
    public function admin_index() 
    {
        // we have data variable in parent class
        $this->data['users'] = $this->model->getUsersList();
    }

    
   // show 1 user with all his comments 
    
    public function admin_view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = strtolower($params[0]);
            $page = isset($params[1]) ? (int)$params[1] : 1;
            $this->data['user'] = $this->model->getUserById($id);
            $this->data['user_comments'] = $this->model->getUserComments($id,$page);

            
            $itemsCount = count($this->model->getUserComments($id));
            $p = new Pagination(array(
                'itemsCount' => $itemsCount,
                'itemsPerPage' => 10,
                'currentPage' => $page
                ));


            }
        
        

        $this->data['p'] = $p;
        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['user_comments'] as $comment) {
            $this->data['pagination_comment'][$count_rows/20][] = $comment;
            $count_rows++;
        }

    
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }

    
    public function user_index ()
    {
        // we have data variable in parent class
        $this->data['users'] = $this->model->getUsersList();

        // data for advertising
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }

    
    public function user_view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = strtolower($params[0]);
            $page = isset($params[1]) ? (int)$params[1] : 1;
            $this->data['user'] = $this->model->getUserById($id);
            $this->data['user_comments'] = $this->model->getUserComments($id,$page);

            
            $itemsCount = count($this->model->getUserComments($id));
            $p = new Pagination(array(
                'itemsCount' => $itemsCount,
                'itemsPerPage' => 10,
                'currentPage' => $page
                ));


            }
        
        

        $this->data['p'] = $p;
        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['user_comments'] as $comment) {
            $this->data['pagination_comment'][$count_rows/20][] = $comment;
            $count_rows++;
        }

    
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }
}

