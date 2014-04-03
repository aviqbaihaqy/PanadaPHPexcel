<?php
namespace Controllers;
use Resources, Models, Libraries;

class Import extends Resources\Controller
{    
	public function __construct(){
		parent::__construct();
		$this->import  = new Models\Import;
		$this->excel   = new Libraries\PhpExcel;
		$this->request = new Resources\Request;
	}
		
	public function upload(){
		$this->upload = new Resources\Upload;
		$this->upload
		->setOption(
			array(
				'folderLocation'    => 'Data/'.date('Y').'/'.date('m').'/',
				'autoRename'        => true,
				'autoCreateFolder'  => true,
				'permittedFileType' => 'xls|xlsx',
				'maximumSize'       => 1000000
				)
			)
		->setErrorMessage(
			array(
				10 => 'Folder tidak tersedia.',
				3 => 'File yang anda upload terlalu besar.'
				)
			);
		$data['message'] = '';
		
		if(isset ($_POST['selectdata']))
			$info = array ('sheetName' => $this->request->post('selectdata',FILTER_SANITIZE_STRING));

		if( isset($_FILES['my_file']) ) {

			$file = $this->upload->now($_FILES['my_file']);
			
			if($file) {
				$info = array_merge ($this->upload->getFileInfo(), $info);
				$data['info'] = $info;
				$data['message']['success'] = 'Import Data berhasil';
				$data['import'] = $this->import($info);
			}
			else {
				$data['message']['error'] = $this->upload->getError('message');
			}
		}

		$this->outputJSON($data, 200);
		//var_dump($data);
	}
	
	private function import($info=array()){
		$default = array(
			'extension' => 'xls',
			'name'      => null,
			'folder'    => 'Data',
			'mime'      => null,
			'sheetName' => 'sheet1'
			);
		$info = array_merge($default, $info);
		
		$table = array(
			//"#DOC123" => array ("tableName" => "example3", "PK" => "id", "AI"=>true),
			"#DOC001" => array ("tableName" => "t_siswa", "PK" => "NIS", "AI"=>false),
			"#DOC002" => array ("tableName" => "t_pegawai", "PK" => "NOMOR_INDUK", "AI"=>false),
			"#DOC003" => array ("tableName" => "r_mata_pelajaran_diajarkan", "PK" => "KD_MATA_PELAJARAN_DIAJARKAN", "AI"=>false),
			"#DOC004" => array ("tableName" => "t_kur_semester", "PK" => "ID_KUR_SEM", "AI"=>true),
			"#DOC005" => array ("tableName" => "r_krs", "PK" => "NIS_R_KRS", "AI"=>false)
			);
		
		if(array_key_exists($info['sheetName'], $table)){
			$tableName = $table[$info['sheetName']];
		}

		$inputFileName = APP.$info['folder'].'/'.$info['name'];
		$args=array(
			"sheetName" => array($info['sheetName']), // dari select import
			"format" => array ("Date","Tanggal","TANGGAL_LAHIR",
				"DITERIMA_TANGGAL","TANGGAL_AKSES"
			) // nama kolom yang akan di format date
			);
		$data = $this->excel->ReadXlsSeveral($inputFileName,$args);
		//$data = $this->excel->ReadXls($inputFileName,$args);
		$do = $this->import->insert($tableName,$data);
		return $do;
	}
	
} 