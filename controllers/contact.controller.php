<?php


class ContactController extends Controller{
    
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Message();
    }

    public function index()
    {
        // data for advertising
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
        
        if ($_POST) {

        	if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['message']) && !empty($_POST['capcha'])) {

        		if($_POST['capcha'] == Session::get('capcha')){
            if ($this->model->save($_POST)) {
                Session::setFlash('Thank you! Your message was sent successfully!');
            } else {
                Session::setFlash('There is some problems');
            	}
            }else{
            	Session::setFlash('Pleace check captcha!');
            }

        	}else{
        		Session::setFlash('Pleace fill in all fields');

        	}
        }
    }
    
    public function admin_index() 
    {
        $this->data['messages'] = $this->model->getList();
    }
}