<?php
namespace Libraries;
use Resources;

class Datatables {
    
	private $primary;
	private $table;
	private $distinct;
	private $groupBy       	= array();
	private $select         = array();
	private $joins          = array();
	private $columns        = array();
	private $where          = array();
	
    public function __construct($db_name = 'default'){
		$this->request = new Resources\Request;
		$this->db = new Resources\Database($db_name);
	}
	
	public function primary($field){
		$this->primary = $field;
		return $this;
	}
	
	public function select($columns = array()){
		$this->columns =  $columns;
		
		if(isset($this->primary))
			array_push($columns,$this->primary." AS DT_RowId");
		
		$this->db->select($columns);
		return $this;
	}
	
	public function from($table){
		$this->table = $table;
		$this->db->from($table);
		return $this;
	}
	
	public function where($column, $operator, $value, $seperator = false){
		$this->where[] = array($column, $operator, $value, $seperator);
		$this->db->where($column, $operator, $value, $seperator);
		return $this;
	}
	
	public function join($table, $pk, $fk, $op='=', $type = NULL){
		$this->joins[] = array($table, $pk, $fk, $op, $type);
		$this->db->join($table,$type)->on($pk, $op, $fk);
		return $this;
	}
	
	public function distinct($column){
		$this->distinct = $column;
		$this->db->distinct($column);
		return $this;
	}
	
	public function groupBy($columns = array()){
		$this->groupBy[] = $columns;
		$columns = implode(",",$columns);
		$this->db->groupBy($columns);
		return $this;
	}
	
	public function generate($output = 'json', $charset = 'UTF-8'){
		if(strtolower($output) == 'json')
		$this->get_paging();
		$this->get_ordering();
		$this->get_filtering();
		return $this->produce_output(strtolower($output), strtolower($charset));
	}
	
	private function get_paging(){
		$iStart = $this->request->post('iDisplayStart');
		$iLength = $this->request->post('iDisplayLength');

		if($iLength != '' && $iLength != '-1')
			$this->db->limit($iLength, ($iStart)? $iStart : 0);
	}
	
	private function get_ordering(){
		if($this->check_mDataprop())
			$mColArray = $this->get_mDataprop();
		elseif($this->request->post('sColumns'))
			$mColArray = explode(',', $this->request->post('sColumns'));
		else
			$mColArray = $this->columns;
			
		$mColArray = array_values($mColArray);
		$columns = array_values($this->columns);

		for($i = 0; $i < intval($this->request->post('iSortingCols')); $i++)
			if(isset($mColArray[intval($this->request->post('iSortCol_' . $i))]) && 
				in_array($mColArray[intval($this->request->post('iSortCol_' . $i))], $columns) && 
				$this->request->post('bSortable_'.intval($this->request->post('iSortCol_' . $i))) == 'true')
				$this->db->orderBy($mColArray[intval($this->request->post('iSortCol_' . $i))], $this->request->post('sSortDir_' . $i));
		
		//var_dump($this->db->command());
		//var_dump($mColArray[intval($this->request->post('iSortCol_0'))]);
		//var_dump($columns);
	}
	
	private function get_filtering(){
		if($this->check_mDataprop())
			$mColArray = $this->get_mDataprop();
		elseif($this->request->post('sColumns'))
			$mColArray = explode(',', $this->request->post('sColumns'));
		else
			$mColArray = $this->columns;

		$sWhere = '';
		$sSearch = $this->request->post('sSearch',FILTER_SANITIZE_STRING);
		$mColArray = array_values($mColArray);
		$columns = array_values($this->columns);

		if($sSearch != '')
		for($i = 0; $i < count($mColArray); $i++)
			if($this->request->post('bSearchable_' . $i) == 'true' && 
				in_array($mColArray[$i], $columns))
				$this->db->where($mColArray[$i], 'LIKE', '%'.$sSearch.'%', 'OR');

		$sRangeSeparator = $this->request->post('sRangeSeparator');
		
		/* Individual column filtering */
		for($i = 0; $i < intval($this->request->post('iColumns')); $i++)
		{
			if(isset($_POST['sSearch_' . $i]) && $this->request->post('sSearch_' . $i) != '' && in_array($mColArray[$i], $columns))
			{
				$miSearch = explode(',', $this->request->post('sSearch_' . $i,FILTER_SANITIZE_STRING));
				
				if(count($miSearch)>1) 
				{
					foreach($miSearch as $val)
					{
						if(preg_match("/(<=|>=|=|<|>)(\s*)(.+)/i", trim($val), $matches))
							$this->db->where($mColArray[$i].' '.$matches[1], $matches[3]);
						elseif(!empty($sRangeSeparator) && preg_match("/(.*)$sRangeSeparator(.*)/i", trim($val), $matches))
						{
							$rangeQuery = '';

							if(!empty($matches[1]))
							$rangeQuery = 'STR_TO_DATE(' . $mColArray[$i] . ",'%d/%m/%y %H:%i:%s') >= STR_TO_DATE('" . $matches[1] . " 00:00:00','%d/%m/%y %H:%i:%s')";

							if(!empty($matches[2]))
							$rangeQuery .= (!empty($rangeQuery)? ' AND ': '') . 'STR_TO_DATE('. $mColArray[$i] . ",'%d/%m/%y %H:%i:%s') <= STR_TO_DATE('" . $matches[2] . " 23:59:59','%d/%m/%y %H:%i:%s')";

							if(!empty($matches[1]) || !empty($matches[2]))
							$this->db->where($rangeQuery);
						}
						else
							$this->db->where($mColArray[$i], 'LIKE', '%'. $val .'%', 'OR');
					}
				}else
					$this->db->where($mColArray[$i], 'LIKE', '%'. $miSearch[0] .'%', 'AND');
			}
		}
		
		//var_dump($this->db->command());
	}
	
	private function produce_output($output, $charset){
		$aaData = array();
		$rResult = $this->get_display_result();
		if($output == 'json'){
			$iTotal = $this->get_total_results();
			$iFilteredTotal = $this->get_total_results(TRUE);
		}

		foreach($rResult as $row_key => $row_val){
			$aaData[$row_key] = ($this->check_mDataprop())? $row_val : array_values($row_val);

			if(!$this->check_mDataprop())
				$aaData[$row_key] = array_values($aaData[$row_key]);
		}
		
		if($output == 'json'){
			$sOutput = array
			(
			'sEcho'                => intval($this->request->post('sEcho')),
			'iTotalRecords'        => $iTotal,
			'iTotalDisplayRecords' => $iFilteredTotal,
			'aaData'               => $aaData,
			'sColumns'             => $this->columns
			);

			if($charset == 'utf-8')
				return json_encode($sOutput);
			else
				return $this->jsonify($sOutput);
		}
		else
		return array('aaData' => $aaData, 'sColumns' => $sColumns);
		
	}
	
	private function get_display_result(){
		return Resources\Tools::objectToArray($this->db->getAll());
	}
	
	private function get_total_results($filtering = FALSE){
		
		if($filtering)
			$this->get_filtering();
		
		foreach ($this->joins as $val)
			$this->db->join($val[0],$val[1])->on($val[2], $val[3], $val[4]);
		
		foreach($this->where as $val)
			$this->db->where($val[0], $val[1], $val[2], $val[3]);
		
		if($this->groupBy){
			$columns = implode(",",$this->groupBy);
			$this->db->groupBy($columns);
		}
		
		if(strlen($this->distinct) > 0){
			$this->db->distinct($this->distinct);
			$this->db->select($this->columns);
		}
		return $this->db->select('count(*)')->from($this->table)->getVar();
	}
	
	private function check_mDataprop(){
		if(!$this->request->post('mDataProp_0'))
		return FALSE;

		for($i = 0; $i < intval($this->request->post('iColumns')); $i++)
		if(!is_numeric($this->request->post('mDataProp_' . $i)))
		return TRUE;

		return FALSE;
	}
	
	private function get_mDataprop(){
		$mDataProp = array();

		for($i = 0; $i < intval($this->request->post('iColumns')); $i++)
		$mDataProp[] = $this->request->post('mDataProp_' . $i);

		return $mDataProp;
	}
	
	public function debug(){
		return $this->db->command();
	}
}