<?php
namespace Controllers;
use Resources, Models,Modules\Panel;

class Home extends Resources\Controller{

	public function __construct(){
        parent::__construct();
    }
	
    public function index($pages='import'){
        $data['title'] = 'Hello world!';
		$data['halaman']= (file_exists(TEMPLATE.$pages.'.php')) ? $pages : '404';
		$this->output('default/template', $data);
		//var_dump($data);
    }
}
