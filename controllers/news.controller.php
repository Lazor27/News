<?php

class NewsController extends Controller {

 
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Article();
    }

    
    public function getCarouselData($id)
    {
        // data for OwlCarousel
        $dir_name = ROOT.DS."webroot".DS."uploads".DS.$id;

        if (!is_dir($dir_name)) {
            return false;
        }
            
        $dir = scandir($dir_name);
        $pictures = array();

        foreach ($dir as $file) {
            if (is_file($dir_name.DS.$file)) { // fail or not

                $type = new SplFileInfo($file); // need it for type of the file

                if ($type->getExtension()=='jpg' || $type->getExtension() == 'png' || $type->getExtension() == 'jpeg') { // check type of the file
                    array_push($pictures,DS."webroot".DS."uploads".DS.$id.DS.$file);
                }
            }
        }

        $this->data['carousel'] = $pictures;
    }
    

   

    
    public function index()
    {
                $params = App::getRouter()->getParams();
        $attr = $this->model->getAttributes();
        $attr_result = array();
        if (!empty($attr)) {
            $i = 1;
            foreach($attr as $row) {
                $attr_result[$row['id']][$i]=$row['value'];
                $i++;
            }
        }
        // data for checkboxes for filter
        $this->data['attributes'] = $attr_result;
        if(isset($_REQUEST['filter'])){
        // array with selected filters
        $this->data['selected_filters'] = (isset($_REQUEST['filter']) && is_array($_REQUEST['filter'])) ? $_REQUEST['filter'] : array();

        $selected_attr = array();
        foreach ($this->data['selected_filters'] as $f_key =>$f_value) {
            foreach ($this->data['attributes'] as $a_key=>$a_value) {

                if (array_key_exists($f_value,$this->data['attributes'][$a_key])) {
                    $selected_attr[$a_key][] = $this->data['attributes'][$a_key][$f_value];
                    $this->data['news'] = $this->model->getList($selected_attr);
                }
            }
        }
    }
        elseif(count($params)){
            $this->data['selected_filters'] = (isset($_REQUEST['filter']) && is_array($_REQUEST['filter'])) ? $_REQUEST['filter'] : array();
            
        $page = isset($params[0]) ? $params[0] : 1;
       
        $itemsCount = count($this->model->getList());
        $p = new Pagination(array(
            'itemsCount' => $itemsCount,
            'itemsPerPage' => 80,
            'currentPage' => $page
            ));

        $this->data['news'] = $this->model->getList(null,$page);

         $this->data['p'] = $p;}

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

            $this->data['article'] = $this->model->getByID($id);
            $this->data['article_tags'] = $this->model->getArticleTags($id);
            $id_news = $this->data['article']['id'];
            $this->data['article_comments'] = $this->model->getArticleComments($id,$id_news,$page);
			


			$itemsCount = count($this->model->getArticleComments($id,$id_news));
			            $p = new Pagination(array(
			                'itemsCount' => $itemsCount,
			                'itemsPerPage' => 5,
			                'currentPage' => $page
			                ));
            $this->data['p'] = $p;


            $this->getCarouselData($id);
        }

        // data for advertising
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }


    
    public function admin_index() 
    {
                $params = App::getRouter()->getParams();
        $attr = $this->model->getAttributes();
        $attr_result = array();
        if (!empty($attr)) {
            $i = 1;
            foreach($attr as $row) {
                $attr_result[$row['id']][$i]=$row['value'];
                $i++;
            }
        }
        // data for checkboxes for filter
        $this->data['attributes'] = $attr_result;
        if(isset($_REQUEST['filter'])){
        // array with selected filters
        $this->data['selected_filters'] = (isset($_REQUEST['filter']) && is_array($_REQUEST['filter'])) ? $_REQUEST['filter'] : array();

        $selected_attr = array();
        foreach ($this->data['selected_filters'] as $f_key =>$f_value) {
            foreach ($this->data['attributes'] as $a_key=>$a_value) {

                if (array_key_exists($f_value,$this->data['attributes'][$a_key])) {
                    $selected_attr[$a_key][] = $this->data['attributes'][$a_key][$f_value];
                    $this->data['news'] = $this->model->getList($selected_attr);
                }
            }
        }
    }
        elseif(count($params)){
            $this->data['selected_filters'] = (isset($_REQUEST['filter']) && is_array($_REQUEST['filter'])) ? $_REQUEST['filter'] : array();
            
        $page = isset($params[0]) ? $params[0] : 1;
       
        $itemsCount = count($this->model->getList());
        $p = new Pagination(array(
            'itemsCount' => $itemsCount,
            'itemsPerPage' => 80,
            'currentPage' => $page
            ));

        $this->data['news'] = $this->model->getList(null,$page);

         $this->data['p'] = $p;}
     }

    /**
     * user action for showing 1 article /
     */
    public function admin_view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = strtolower($params[0]);
            $page = isset($params[1]) ? (int)$params[1] : 1;

            $this->data['article'] = $this->model->getByID($id);
            $this->data['article_tags'] = $this->model->getArticleTags($id);
            $id_news = $this->data['article']['id'];
            $this->data['article_comments'] = $this->model->getArticleComments($id,$id_news,$page);
			


			$itemsCount = count($this->model->getArticleComments($id,$id_news));
			            $p = new Pagination(array(
			                'itemsCount' => $itemsCount,
			                'itemsPerPage' => 5,
			                'currentPage' => $page
			                ));
            $this->data['p'] = $p;


            $this->getCarouselData($id);
        }

        if($_POST) {
            $id_news = $this->data['article']['id'];
            $id_user=Session::get('id');
            $id_parent = '0';
            $text = $_POST['text'];


            if (!empty($_POST['text']) && !empty($_POST['capcha'])) {
                if($_POST['capcha'] == Session::get('capcha')) {

            
           
            
            
            
            
            $this->model->saveComment($id_news,$id_user,$id_parent,$text);
            header('Location: http://138.68.107.38//admin/news/view/'.$id_news.'/');
            }else{
                Session::setFlash('Please check captcha');

        }

        
            }else{
                Session::setFlash('Please fill in all fields');
                
            }
        }
    }


    /**
     *action for analytical articles /
     */
    public function admin_analytical()
    {   $params = App::getRouter()->getParams();
		$page = isset($params[0]) ? (int)$params[0] : 1;
        $itemsCount = count($this->model->getAnalyticalList());
                        $p = new Pagination(array(
                            'itemsCount' => $itemsCount,
                            'itemsPerPage' => 50,
                            'currentPage' => $page
                            ));
            $this->data['p'] = $p;
        $this->data['news'] = $this->model->getAnalyticalList($page);

    }

  


    public function admin_add()
    {
        if ($_POST) {
            if (!empty($_POST['title']) && !empty($_POST['text'])) {

                $result = $this->model->saveArticle($_POST); // save to mySQL

                $id = $this->model->getID(); // name of the folder
                mkdir(ROOT.DS."webroot".DS."img".DS.$id); // create folder

                $dir_name = ROOT.DS."webroot".DS."img".DS.$id.DS;

                if ($_FILES) {
                    foreach ($_FILES['image']['error'] as $key => $error) {

                        if ($error == UPLOAD_ERR_OK) {

                            $temp_file_name = $_FILES['image']['tmp_name'][$key];
                            $file_name = $dir_name . basename($_FILES['image']['name'][$key]);
                            move_uploaded_file($temp_file_name, $file_name);
                        }
                    }
                }

                if ($result) {
                    Session::setFlash('Article was saved.');
                } else {
                    Session::setFlash('Error');
                }
                Router::redirect('/admin/news/');
            } else {
                Session::setFlash("Please fill all fields");
            }
        } else {
            return false;
        }
    }

    
    public function admin_edit()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = strtolower($params[0]);
            $this->data['article'] = $this->model->getByID($id);
            $this->data['art-tags'] = $this->model->getArticleTagLine($id);
            $this->data['art_category'] = $this->model->getArticleCategory($id);
            $this->data['all-categories'] = $this->model->getAllCategories();

            $this->getCarouselData($id);
        }

        if ($_POST) {
            $result = $this->model->saveEditedArticle($_POST);
            if ($result) {
                Session::setFlash('article was saved');
            } else {
                Session::setFlash('Error');
            }
        }

        $id = $this->data['article']['id'];
        $dir_name = ROOT . DS . "webroot" . DS . "img" . DS . $id . DS;
        if ($_FILES) {
            if (file_exists($dir_name)) {
                foreach ($_FILES['image']['error'] as $key => $error) {
                    if (!file_exists($dir_name . DS . basename($_FILES['image']['name'][$key]))) {
                        if ($error == UPLOAD_ERR_OK) {
                            $temp_file_name = $_FILES['image']['tmp_name'][$key];
                            $file_name = $dir_name . basename($_FILES['image']['name'][$key]);
                            move_uploaded_file($temp_file_name, $file_name);
                        }
                    }
                }
            } else {
                mkdir(ROOT . DS . "webroot" . DS . "img" . DS . $id); // create folder
                foreach ($_FILES['image']['error'] as $key => $error) {

                    if ($error == UPLOAD_ERR_OK) {
                        $temp_file_name = $_FILES['image']['tmp_name'][$key];
                        $file_name = $dir_name . basename($_FILES['image']['name'][$key]);
                        move_uploaded_file($temp_file_name, $file_name);
                    }
                }
            }
        }
    }


   
    public function user_index()
    {
                $params = App::getRouter()->getParams();
        $attr = $this->model->getAttributes();
        $attr_result = array();
        if (!empty($attr)) {
            $i = 1;
            foreach($attr as $row) {
                $attr_result[$row['id']][$i]=$row['value'];
                $i++;
            }
        }
        // data for checkboxes for filter
        $this->data['attributes'] = $attr_result;
        if(isset($_REQUEST['filter'])){
        // array with selected filters
        $this->data['selected_filters'] = (isset($_REQUEST['filter']) && is_array($_REQUEST['filter'])) ? $_REQUEST['filter'] : array();

        $selected_attr = array();
        foreach ($this->data['selected_filters'] as $f_key =>$f_value) {
            foreach ($this->data['attributes'] as $a_key=>$a_value) {

                if (array_key_exists($f_value,$this->data['attributes'][$a_key])) {
                    $selected_attr[$a_key][] = $this->data['attributes'][$a_key][$f_value];
                    $this->data['news'] = $this->model->getList($selected_attr);
                }
            }
        }
    }
        elseif(count($params)){
            $this->data['selected_filters'] = (isset($_REQUEST['filter']) && is_array($_REQUEST['filter'])) ? $_REQUEST['filter'] : array();
            
        $page = isset($params[0]) ? $params[0] : 1;
       
        $itemsCount = count($this->model->getList());
        $p = new Pagination(array(
            'itemsCount' => $itemsCount,
            'itemsPerPage' => 80,
            'currentPage' => $page
            ));

        $this->data['news'] = $this->model->getList(null,$page);

         $this->data['p'] = $p;}

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

            $this->data['article'] = $this->model->getByID($id);
            $this->data['article_tags'] = $this->model->getArticleTags($id);
            $id_news = $this->data['article']['id'];
            $this->data['article_comments'] = $this->model->getArticleComments($id,$id_news,$page);
			


			$itemsCount = count($this->model->getArticleComments($id,$id_news));
			            $p = new Pagination(array(
			                'itemsCount' => $itemsCount,
			                'itemsPerPage' => 5,
			                'currentPage' => $page
			                ));
            $this->data['p'] = $p;


            $this->getCarouselData($id);

        }

        if($_POST) {
            $id_news = $this->data['article']['id'];
            $id_user=Session::get('id');
            $id_parent = '0';
            $text = $_POST['text'];


            if (!empty($_POST['text']) && !empty($_POST['capcha'])) {
                if($_POST['capcha'] == Session::get('capcha')) {

            
           
            
            
            
            
            $this->model->saveComment($id_news,$id_user,$id_parent,$text);
            header('Location: http://138.68.107.38//user/news/view/'.$id_news.'/');
            }else{
                Session::setFlash('Please check captcha');

        }

        
            }else{
                Session::setFlash('Please fill in all fields');
                
            }
        }

        // data for advertising
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);

    }

    
    public function user_analytical()
    {
        $params = App::getRouter()->getParams();

        $page = isset($params[0]) ? (int)$params[0] : 1;

        $itemsCount = count($this->model->getAnalyticalList());
                        $p = new Pagination(array(
                            'itemsCount' => $itemsCount,
                            'itemsPerPage' => 50,
                            'currentPage' => $page
                            ));
            $this->data['p'] = $p;
        $this->data['news'] = $this->model->getAnalyticalList($page);
        






        // data for advertising
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }


   
    
}