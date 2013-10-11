<?php
class AlbumModel extends CommonModel
{
  
  protected $_validate	 =	 array(
        array('album_name','Checkchar','相册名称不能为空或大于10个字符!',1,"callback"),
        array('album_name','','相同相册名称已经存在',1,'unique',1),
		);
 
  protected $_auto = array(
        array('album_name','removehtml', 3, 'function'),
        array('create_time', 'time', 1, 'function'),
    );
	
  //此处字符串长度iLength()修改	
  function Checkchar()
  {
  $cstr=$_POST["album_name"];
  $tcstr=true;
  if(strlen($cstr)<1 || strlen($cstr)>10){
  $tcstr=false;
  }
  return $tcstr;
  }
 
}
?>