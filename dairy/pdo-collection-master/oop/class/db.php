<?php
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'dbConnect.php';

class Db {

	
	protected $_connAccess;
	protected $_conn;
	protected $_connClose;
	protected $_sql;
	protected $_op = array('>','<','!=','>=','>=','<>','LIKE','OR');

	
	public function __construct(){
		
		//$this->_conn 	   = dbConnect::getConnection();
		$this->_connAccess = dbConnect::cekInstance();
		
	}
	private function includeIterator(){
		
		require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'iterator.php';
	}
	public function execute($sql){
			
		$exe = mysql_query($sql,$this->_connAccess->getConnection());
		
		if(!$exe)
			exit('Error : '.mysql_error().':'.$sql);
		else{ 
			return $exe;
			dbConnect::closeConnection();
		}
	}
	public function getRow(){
		
		mysql_fetch_object($this->execute());
	}
	function insert($table,array $data){
		
		$sql = "INSERT INTO ".$table." SET";
			
			foreach((array) $data as $field=>$value){
			
				$sql.= " ".$field." = '".mysql_real_escape_string($value)."',";			
			}
			$sql = rtrim($sql,',');
			$this->execute($sql);
		
	}
	function update($table,$data,$where=NULL){
		
		$sql ="UPDATE ".$table." SET ";
		if(is_array($data)){
			foreach((array) $data as $field=>$value){
				
				$sql.= " ".$field." = '".mysql_real_escape_string($value)."',";			
			}
		}
		$sql = rtrim($sql,','); 
		$sql.=" WHERE 1=1 ";
				
		if($where){
		
			if(is_array($where)){
				
				foreach((array) $where as $field=>$value){
					
					$sql.=" AND ".$field." = '".mysql_real_escape_string($value)."' ";				
				}			
			}
			else{
				
				print('Error, WHERE clause  is not an array');
			}
		}
		//echo $sql;exit;
		$this->execute($sql);
			
	}
	
	function get($table){
				
		$sql = "SELECT * FROM ".$table." WHERE 1=1 ";

		if($sql){
		
			$this->_sql = $sql;
		}
		return $this;
	}
	function query($sql){
		
		if(!empty($sql))
			$this->_sql = $sql;
		
		return $this;
	}
	function result(){
		
		$this->includeIterator();
		return new Iterate ($this->_sql,'result');
	
	}
	function resultArray(){
		
		$this->includeIterator();
		return new Iterate($this->_sql,'result_array');
	
	}
	function num_rows(){
		
		$data = $this->execute($this->_sql);
		return mysql_num_rows($data); 
	}
	function getRows(){
		
		$data = $this->execute($this->_sql);
		return mysql_fetch_object($data);
	}
	function getWhere($table,$where=NULL){
		
		$this->includeIterator();
		 
		$sql = "SELECT * FROM ".$table." WHERE 1=1 ";
		if($where){
						
			if(is_array($where)){
				
				foreach((array) $where as $field=>$value){
					
					/* agar where sesuai kemauan User let's do this. */
					$op_pos= $field." = ";
					foreach ((array)$this->_op as $op){
						
						if(strpos($field,$op)!==FALSE){
						
							$op_pos = $field; 
						}
						
					}
					
					$sql.=" AND ".$op_pos." '".mysql_real_escape_string($value)."' ";				
				}
			
			}
			else{
				
				print('Error, WHERE clause  is not an array');
			}
		}
		echo '<pre>';
		// echo $sql;
		// exit;
		if($sql)
			$this->_sql=$sql;
		
		return $this;
	}
	function delete($table,$where=NULL){
	
		$sql = "DELETE FROM ".$table." WHERE 1=1 ";
		
		if(!empty($where)){
			
			if(is_array($where)){
				
				foreach((array) $where as $field=>$val){
					
					$op_pos= $field." = ";
					foreach ((array)$this->_op as $op){
						
						if(strpos($field,$op)!==FALSE){
						
							$op_pos = $field; 
						}
						
					}
					
					$sql.=" AND ".$op_pos." '".mysql_real_escape_string($val)."' ";
				}
			
			}
			else{
				
				print('Paramater is not array, make it array first');
			}
		
		}
		
		$this->execute($sql);
		
	}
	
}
