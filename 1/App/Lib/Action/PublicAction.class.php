<?php
class PublicAction extends Action{

    protected $album_config;
	function _initialize() {
	  if(!S('album_cache')){S('album_cache',M("Config")->getById(1));}
	  $cachedata=S('album_cache');
	  $this->album_config=unserialize($cachedata['albumsetting']);
	  import('@.ORG.Input');
	  Load('extend');
	}
	
	public function _empty() {
      //空操作定义
	  $this->showmsg_box('系统查找不到该操作,请重试!',__APP__,0,10);
	}
	
	protected function showmsg_box($msg='',$jp='',$pt=0,$jt=1){
	//提示信息输出
	//$msg:输出信息 $jp:转向地址 $pt:0为错误信息 1为成功信息 $jt:跳转时间
		$this->assign('waitSecond',$jt);
		$this->assign('status',$pt);
		if($jp!=''){
        $this->assign('jumpUrl',$jp);
		}
		if($pt==0){
		$this->error($msg);
		}else{
        $this->success($msg);
		}
	}

}
?>