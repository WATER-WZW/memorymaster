<?php

class SettingAction extends PublicAction{
	
	public function index(){
		 $this->assign('albumsetting',$this->album_config);
		 $this->display();
	}
	
	
	public function saveedit(){
	  if(!valididvalue($_POST['id'])){$this->showmsg_box('系统查找不到该操作,请重试!',__APP__,0,10);}	
	  $model	=	D('Config');
	  $vo = $model->create('',2);
	  if(!$vo) {
		  $this->error($model->getError());
	  }
	  $id	= is_array($vo)?$vo[$model->getPk()]:$vo->{$model->getPk()};
	  $result  = $model->save($vo);
	  if($result) {
		   S('album_cache',$model->getById(1));//更新缓存
		   $this->ajaxReturn(0,'设置成功!',1);
	  }else{
	       $this->showmsg_box('操作错误,原因:1.数据没作任何修改 2.程序发生错误',__APP__,0,10);
	  }
	
	}
	
	public function clearcache() {
	  //清理缓存
	  import("ORG.Io.Dir");
	  Dir::delDir(CACHE_PATH);	
	  Dir::delDir(TEMP_PATH);	
	  Dir::delDir(LOG_PATH);	
	  Dir::delDir(DATA_PATH);	
	  Dir::del(RUNTIME_PATH);
	  $this->showmsg_box('执行清理系统缓存成功',U("index/index"),1,5);
	 }
	
}
?>