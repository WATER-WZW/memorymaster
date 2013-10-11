<?php 

class CommonModel extends Model {
 
 function doupdate($fields,$condition){
 //根据条件更新操作 
	 $table = $this->getTableName();
	 return $this->db->execute('update '.$table.' set '.$fields.' where '.$condition.'');
 }
  
 function getinitsql($condition,$pagesize=10){
 //取得sql 此为分页类使用 如使用THINK自带的分页类则不需要此函数
	 $table = $this->getTableName();
	 if($condition==''){
	  return 'select * from '.$table.' LIMIT 0,'.$pagesize.'';
	 }else{
	  return 'select * from '.$table.' where '.$condition.' LIMIT 0,'.$pagesize.'';
	 }
 }
 
 function checkiquetitle($condition){
	//检测是否存在相同标题,修改资料时使用 
	 $table = $this->getTableName();
	 $vaild = $this->db->execute('select * from '.$table.' where '.$condition.'');
	 if($vaild){
		 return false;
	 }else{
		 return true; 
	 }
 }
 
 
}
?>