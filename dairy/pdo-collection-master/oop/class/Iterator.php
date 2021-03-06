<?php
 include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'dbConnect.php';
 
class Iterate implements Iterator{

	protected $_query;
	protected $_result 		= array();
	protected $_numResult	= 0;
	protected $_pointer		= 0;
	protected $_sql			= 0;
	protected $_mode;
	protected $_conn;
	
	function __construct($sql,$mode=NULL){
		
		$this->_sql = $sql;
		$this->_mode= $mode;
		
		//echo $mode.$sql;
	}
	
	//reset pointer ke 0
	function rewind(){ 
		
		$this->_pointer = 0;
	}
	
	// return pointer terkini
	function key(){ 
		
		return $this->_pointer;
	}
	
	protected function _getQuery(){
		
		if(!$this->_query){
		
			$this->_conn  = dbConnect::getConnection();

			$this->_query =	mysql_query($this->_sql,$this->_conn);
			if(!$this->_query)
				print('Error : '.mysql_error().' '.$this->_sql);
				
		}
	
		return $this->_query;
		dbConnect::closeConnection();
	}

	protected function _getNumResult(){
	
		if(!$this->_numResult){
		
			$this->_numResult = mysql_num_rows($this->_getQuery());
		}
		
		return $this->_numResult;
	
	}
	
	//memvalidasi pointer current ada elemen nya
	function valid(){
		
		if($this->_pointer >=0 && $this->_pointer < $this->_getNumResult()){
			
			return TRUE;
		}
		else{
			
			return FALSE;
		}
	
	}
	
	protected function _getRow($pointer){
		
		if(isset($this->_result[$pointer])){
			
			return $this->_result[$pointer];
		}
		
		$row = NULL;
		
		if($this->_mode=='result'){
			
			$row = mysql_fetch_object($this->_getQuery());			
		}
		elseif($this->_mode=='result_array'){
		
			$row = mysql_fetch_array($this->_getQuery());
		}
		
		if($row){
			
			$this->_result[$pointer] = $row;
		}
		return $row;
	}
	function next(){
		
		$row = $this->_getRow($this->_pointer);
		if($row){
			
			$this->_pointer++;
		}
		
		return $row;	
	}
	function current(){
		
		return $this->_getRow($this->_pointer);
	}
		
} 
