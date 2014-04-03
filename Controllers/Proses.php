<?php
namespace Controllers;
use Resources, Models;

class Proses extends Resources\Controller
{    
	public function __construct(){
		parent::__construct();
		$this->proses = new Models\Proses;
		$this->rest = new Resources\Rest;
		$this->request = new Resources\Request;
	}
	
	public function index($form = null){
		$table = array(
			"forms_ta" => "form_ta",
			"forms_kp" => "form_kp",
			"forms_pendadaran" => "form_pendadaran",
			"forms_wisuda" => "form_wisuda",
			"forms_cuti" => "form_cuti",
			"forms_aktif" => "form_aktif",
			"forms_pindah_prodi" => "form_pindah_prodi"
			"forms_perbaikan_nilai" => "form_perbaikan_nilai"
		);
		$data['form'] = $form;
		$data['post'] = $this->request->post('data');
		$data['table'] = $table[$form];
		if(array_key_exists($form, $table)){
			$data['table'] = $table[$form];
			$this->proses->insert($table[$form],$this->request->post('data'));
		}
		//return $data;
		var_dump($data);
	}	
} 