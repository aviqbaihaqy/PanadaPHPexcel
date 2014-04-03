<?php
namespace Models;
use Resources;
class Import {
	public function __construct( ) {
		$this->db = new Resources\Database;
	}
	
	public function insert($table=array(), $result = array()){
        if ($result){
			foreach ($result as $data){
				$ID = (isset($data[$table['PK']])) ? $data[$table['PK']] : '';
				
				if ($this->db->getOne($table['tableName'], array($table['PK'] => $ID))) {
					$this->db->update($table['tableName'], $data, array($table['PK'] => $ID ));
				}else{
					$this->db->insert($table['tableName'], $data);
				}
				
				$insertID[] = ($table['AI']) ? $this->db->insertId() : $data[$table['PK']] ;
			}
			return $this->getLastInsert($table, $insertID);
		}
    }
	
	private function getLastInsert($table=array(), $where=array()){
		return $this->db
				->where($table['PK'], 'in', $where)
				->getAll($table['tableName']);
	}
	
	public function getOne($table, $criteria = array() ){
        return $this->db->getOne($table, $criteria);
    }
}
