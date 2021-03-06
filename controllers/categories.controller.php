<?php
Class CategoriesController extends Controller {

    
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Category();
    }

    public function build_tree($id_parent, $level){
        if (isset($this->data['cat'][$id_parent])) { // if categories with such id_parent exists
            foreach ($this->data['cat'][$id_parent] as $value) { // check it
                
             
                switch (App::getRouter()->getMethodPrefix()) {
                    case 'admin_':
                        $this->data['tree'] .= "<div style='margin-left:".($level*25)."px;'><h3>
                                            <a href='/admin/categories/view/". $value['id'] . "'>" . $value['name'] . "</a></h3></div>" . PHP_EOL;
                        break;
                    case 'user_':
                        $this->data['tree'] .= "<div style='margin-left:".($level*25)."px;'><h3>
                                            <a href='/user/categories/view/". $value['id'] . "'>" . $value['name'] . "</a></h3></div>" . PHP_EOL;
                        break;
                    case '':
                        $this->data['tree'] .= "<div style='margin-left:".($level*25)."px;'><h3>
                                            <a href='/categories/view/". $value['id'] . "'>" . $value['name'] . "</a></h3></div>" . PHP_EOL;
                        break;
                    default:
                        break;
                }
                
                $level++;
                $this->build_tree($value['id'],$level);
                $level--;
            }
        }
    }
    public function index()
    {
      
        $this->data['cat'] = $this->model->getCategoryTree();
        $this->data['tree'] = "";
        $this->build_tree(0,0);

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
            $this->data['category'] = $this->model->getCategoryName($id);
            $this->data['category_news'] = $this->model->getNewsByCategoryId($id);
        }

        
        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['category_news'] as $news) {
            $this->data['pagination_news'][$count_rows/5][] = $news;
            $count_rows++;
        }
        

        $this->data['current_category'] = (isset($params[0])) ? $params[0] : 0;
        $this->data['current_pag'] = (isset($params[1])) ? $params[1] : 0;
        $this->data['last_pag'] = (int)(count($this->data['category_news'])/5)-1;

    
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }

    
    // actions for administrators
    public function admin_index()
    {
       
        $this->data['cat'] = $this->model->getCategoryTree();
        $this->data['tree'] = "";
        $this->build_tree(0,0);

        if ($_POST) {

            if ($this->model->createCategory($_POST['id_parent'],$_POST['category_name'])) {
                Router::redirect('/admin/categories/');
            }
        }
        $this->data['parents-category'] = $this->model->getParentsCategories();

    }

   
    public function admin_view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = strtolower($params[0]);
            $this->data['category'] = $this->model->getCategoryName($id);
            $this->data['category_news'] = $this->model->getNewsByCategoryId($id);
        }

        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['category_news'] as $news) {
            $this->data['pagination_news'][$count_rows/5][] = $news;
            $count_rows++;
        }

        // data for pagination work
        $this->data['current_category'] = (isset($params[0])) ? $params[0] : 0;
        $this->data['current_pag'] = (isset($params[1])) ? $params[1] : 0;
        $this->data['last_pag'] = (int)(count($this->data['category_news'])/5)-1;
    }

//actions for login users

    public function user_index()
    {
        // data for building categories tree 
        $this->data['cat'] = $this->model->getCategoryTree();
        $this->data['tree'] = "";
        $this->build_tree(0,0);

        
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
            $this->data['category'] = $this->model->getCategoryName($id);
            $this->data['category_news'] = $this->model->getNewsByCategoryId($id);
        }

        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['category_news'] as $news) {
            $this->data['pagination_news'][$count_rows/5][] = $news;
            $count_rows++;
        }

        // data for pagination work
        $this->data['current_category'] = (isset($params[0])) ? $params[0] : 0;
        $this->data['current_pag'] = (isset($params[1])) ? $params[1] : 0;
        $this->data['last_pag'] = (int)(count($this->data['category_news'])/5)-1;

        // data for advertising
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }

    
    
    public function moderator_index()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = strtolower($params[0]);
            $this->data['category'] = $this->model->getCategoryName($id);
            $this->data['category_news'] = $this->model->getNewsByCategoryId($id);
        }

        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['category_news'] as $news) {
            $this->data['pagination_news'][$count_rows/5][] = $news;
            $count_rows++;
        }

        $this->data['current_category'] = (isset($params[0])) ? $params[0] : 0;
        $this->data['current_pag'] = (isset($params[1])) ? $params[1] : 0;
        $this->data['last_pag'] = (int)(count($this->data['category_news'])/5);

       
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }
    
    public function admin_political()
    {
        if ($_POST) {
            $this->model->editComment($_POST);
        }
        $this->data['all_comments'] = $this->model->getPoliticComments();
    }
    
    
}
