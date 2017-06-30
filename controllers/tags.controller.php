<?php
class TagsController extends Controller {

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Tag();
    }
    public function index()
    {
        // we have data variable in parent class
        $this->data['tags'] = $this->model->getList();

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
            $this->data['tag'] = $this->model->getTagName($id);
            $this->data['tag_news'] = $this->model->getNewsByTagId($id);
        }
        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['tag_news'] as $news) {
            $this->data['pagination_tags'][$count_rows/5][] = $news;
            $count_rows++;
        }

        // data for pagination work
        $this->data['current_tag'] = (isset($params[0])) ? $params[0] : 0;
        $this->data['current_pag'] = (isset($params[1])) ? $params[1] : 0;
        $this->data['last_pag'] = (int)(count($this->data['tag_news'])/5)-1;

        // data for advertising
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }
    public function admin_index()
    {
        $this->data['tags'] = $this->model->getList();

        if ($_POST) {
            $this->model->createTag($_POST['tag_name']);
        }
    }
    public function admin_view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = strtolower($params[0]);
            $this->data['tag'] = $this->model->getTagName($id);
            $this->data['tag_news'] = $this->model->getNewsByTagId($id);
        }
        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['tag_news'] as $news) {
            $this->data['pagination_tags'][$count_rows/5][] = $news;
            $count_rows++;
        }

        
        $this->data['current_tag'] = (isset($params[0])) ? $params[0] : 0;
        $this->data['current_pag'] = (isset($params[1])) ? $params[1] : 0;
        $this->data['last_pag'] = (int)(count($this->data['tag_news'])/5)-1;
    }
  

    public function user_index()
    {
        // we have data variable in parent class
        $this->data['tags'] = $this->model->getList(true);

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
            $this->data['tag'] = $this->model->getTagName($id);
            $this->data['tag_news'] = $this->model->getNewsByTagId($id);
        }
        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['tag_news'] as $news) {
            $this->data['pagination_tags'][$count_rows/5][] = $news;
            $count_rows++;
        }
        $this->data['current_tag'] = (isset($params[0])) ? $params[0] : 0;
        $this->data['current_pag'] = (isset($params[1])) ? $params[1] : 0;
        $this->data['last_pag'] = (int)(count($this->data['tag_news'])/5)-1;


     
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }


}