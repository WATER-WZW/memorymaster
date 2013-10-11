﻿<?php
    include_once( 'config.php' );
        include_once( 'saetv2.ex.class.php' );
class WallAction extends PublicAction{
	
    public function index(){
	  
        $model= new Model();
		$dbfix = C( "DB_PREFIX" );
		$dbtable1 = $dbfix."album";
        $dbtable2 = $dbfix."albumphoto";
	   
	    $limitnum='3';//每页显示条数
		$sql="select a.*,count(b.id) as imgnum from $dbtable1 as a LEFT JOIN $dbtable2 as b on a.id=b.pid group by a.id LIMIT 0,$limitnum";
		$count= count($model->query("select a.*,count(b.id) as imgnum from $dbtable1 as a LEFT JOIN $dbtable2 as b on a.id=b.pid group by a.id"));//取得总数据
	   /*
	    分页开始 
	   */
	   import ( '@.ORG.Page' );//载入分页类
	   $page =  new Page($sql,5);
	   $sql =  $page->StartPage($count,"a.sortnum asc,a.id",true,true,'down');
       $list=$model->query($sql);
 	   $ButtonArray = array("<","<<",">>",">");
	   /**
	    Page类调用说明:参数1 自定义按钮显示 2. select text none 三种 3. true or false 4. 自定义样式 
       */
	   $pagebar=$page->EndPage($ButtonArray,"select",true,"green-black");
	   /*
	    分页结束
	   */
	   $this->assign("albumlist",$list);
	   $this->assign("pagebar",$pagebar);
           $scene = '风景图'; 
           $this->assign("scene",$scene);
           
	   $this->display();
    }

	public function listphoto(){
		//相册图片列表
          //valididvalue去掉
		$id=$_REQUEST['id'];
		if(!$id){$this->showmsg_box('系统查找不到该操作,请重试!',__APP__,0,10);}
		
		$model=D("Album");
		$albumarr=$model->where("id=$id")->find();
		if(!$albumarr){$this->showmsg_box('系统查找不到该操作,请重试!',"",0,5);}
		
	    $modelphoto=D("Albumphoto");
	    $limitnum='3';//每页显示条数
		$sql=$modelphoto->getinitsql("pid=$id",$limitnum);//取到sql
		$count= $modelphoto->where("pid=$id")->count();//取得总数据
	   /*
	    分页开始 
	   */
		import ( '@.ORG.Page' );//载入分页类
	   $page =  new Page($sql,5);
	   $sql =  $page->StartPage($count,"sortnum asc,id",true,true,'down');
       $list=$modelphoto->query($sql);
 	   $ButtonArray = array("<","<<",">>",">");
	   /**
	    Page类调用说明:参数1 自定义按钮显示 2. select text none 三种 3. true or false 4. 自定义样式 
       */
	   $pagebar=$page->EndPage($ButtonArray,"select",true,"green-black");
	   /*
	    分页结束
	   */
          $model->where('id='.$id)->setInc("hits",1);
          // $model->setinc("hits","id=$id",1);
	   $this->assign("albumarr",$albumarr);
	   $this->assign("photolist",$list);
	   $this->assign("pagebar",$pagebar);
	   $this->display();	   
	}
	
	public function counthit(){
		//图片点击统计
		$id=$_REQUEST['id'];
		if($id){
	     $model=M("Albumphoto");
		 $isphoto=$model->where("id=$id")->find();
                 if($isphoto)
                 { $model->where('id='.$id)->setInc("hits",1);}
                  // if($isphoto){
                  //   $model->where('id=$id')->setInc('hits');// }
          //else
                  // {$this->showmsg_box('系统查找不到该操作,请重试!',"",0,5);}
                   //$model->setinc("hits","id=$id",1);
		 
		}
	}
	public function apply()
        {
        	$id=$_REQUEST['id'];
		if(!$id){$this->showmsg_box('系统查找不到该操作,请重试!',__APP__,0,10);}
                    
        session_start();
	error_reporting(1);


	$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
	$ms  = $c->user_timeline_by_id(); // done

	$uid_get = $c->get_uid();
	$uid = $uid_get['uid'];
//$c->upload('这是我通过api接口发布的图片微博','logo.jpg');
	$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
	$usrname = $user_message['name'];
        
        $modelx=M('Album');
	$ohy=$modelx->where("id=$id")->getField('password');
	$ohx=$_REQUEST['album_namex'];
	if($ohx!=$ohy && $ohy)
	{
	$this->showmsg_box('相册密码输入错误',"",0,5);
}
        $model=M('Albuminfo');
        $data['pid']=$id;
        $data['usrname']=$usrname;
        $rsl=$model->where('pid='.$id." AND usrname='$usrname'")->find();
        if(!$rsl)
        {	$model->add($data);
        	$this->showmsg_box('加入成功!',"",0,5);
        }
        else
        	{$this->showmsg_box('您已经加入了该主题！',"",0,5);}
        }
	
        
        
        public function themefind()
        {
        
        
          /*
        	$model=M('Album');
                
 		$albumarr=$model->where("=$id")->find();
		if(!$albumarr){$this->showmsg_box('系统查找不到该操作,请重试!',"",0,5);}
		
	    $modelphoto=D("Albumphoto");
	    $limitnum='3';//每页显示条数
		$sql=$modelphoto->getinitsql("pid=$id",$limitnum);//取到sql
$count= $modelphoto->where("pid=$id")->count();//取得总数据*/
	   /*
	    分页开始 
	   */
          /*		import ( '@.ORG.Page' );//载入分页类
	   $page =  new Page($sql,5);
	   $sql =  $page->StartPage($count,"sortnum asc,id",true,true,'down');
       $list=$modelphoto->query($sql);
$ButtonArray = array("<","<<",">>",">");*/
	   /**
	    Page类调用说明:参数1 自定义按钮显示 2. select text none 三种 3. true or false 4. 自定义样式 
       */
          //  $pagebar=$page->EndPage($ButtonArray,"select",true,"green-black");
	   /*
	    分页结束
	   */
          /*   $model->where('id='.$id)->setInc("hits",1);
          // $model->setinc("hits","id=$id",1);
	   $this->assign("albumarr",$albumarr);
	   $this->assign("photolist",$list);
	   $this->assign("pagebar",$pagebar);
$this->display();	   */



	$abname = $_REQUEST['album_name'];

          if(!$abname){$this->showmsg_box('系统查找不到该操作,请重试!',__APP__,0,3);}
	    
        $model= new Model();
		$dbfix = C( "DB_PREFIX" );
		$dbtable1 = $dbfix."album";
        $dbtable2 = $dbfix."albumphoto";
	   
	    $limitnum='3';//每页显示条数
		$sql="select a.*,count(b.id) as imgnum from $dbtable1 as a LEFT JOIN $dbtable2 as b on a.id=b.pid where a.album_name like '%$abname%' group by a.id LIMIT 0,$limitnum";
		$count= count($model->query("select a.*,count(b.id) as imgnum from $dbtable1 as a LEFT JOIN $dbtable2 as b on a.id=b.pid where a.album_name like '%$abname%' group by a.id"));//取得总数据
	   /*
	    分页开始 
	   */
	   import ( '@.ORG.Page' );//载入分页类
	   $page =  new Page($sql,5);
	   $sql =  $page->StartPage($count,"a.sortnum asc,a.id",true,true,'down');
       $list=$model->query($sql);
 	   $ButtonArray = array("<","<<",">>",">");
	   /**
	    Page类调用说明:参数1 自定义按钮显示 2. select text none 三种 3. true or false 4. 自定义样式 
       */
	   $pagebar=$page->EndPage($ButtonArray,"select",true,"green-black");
	   /*
	    分页结束
	   */
	   $this->assign("albumlist",$list);
	   $this->assign("pagebar",$pagebar);
	   $this->display();	        
        }
        
        
        public function idfind()
        {
        
        
          /*
        	$model=M('Album');
                
 		$albumarr=$model->where("=$id")->find();
		if(!$albumarr){$this->showmsg_box('系统查找不到该操作,请重试!',"",0,5);}
		
	    $modelphoto=D("Albumphoto");
	    $limitnum='3';//每页显示条数
		$sql=$modelphoto->getinitsql("pid=$id",$limitnum);//取到sql
$count= $modelphoto->where("pid=$id")->count();//取得总数据*/
	   /*
	    分页开始 
	   */
          /*		import ( '@.ORG.Page' );//载入分页类
	   $page =  new Page($sql,5);
	   $sql =  $page->StartPage($count,"sortnum asc,id",true,true,'down');
       $list=$modelphoto->query($sql);
$ButtonArray = array("<","<<",">>",">");*/
	   /**
	    Page类调用说明:参数1 自定义按钮显示 2. select text none 三种 3. true or false 4. 自定义样式 
       */
          //  $pagebar=$page->EndPage($ButtonArray,"select",true,"green-black");
	   /*
	    分页结束
	   */
          /*   $model->where('id='.$id)->setInc("hits",1);
          // $model->setinc("hits","id=$id",1);
	   $this->assign("albumarr",$albumarr);
	   $this->assign("photolist",$list);
	   $this->assign("pagebar",$pagebar);
$this->display();	   */



	$abname = $_REQUEST['album_name'];
	#abname+=0;
          if(!$abname){$this->showmsg_box('系统查找不到该操作,请重试!',__APP__,0,3);}
	    
        $model= new Model();
		$dbfix = C( "DB_PREFIX" );
		$dbtable1 = $dbfix."album";
        $dbtable2 = $dbfix."albumphoto";
	   
	    $limitnum='3';//每页显示条数
		$sql="select a.*,count(b.id) as imgnum from $dbtable1 as a LEFT JOIN $dbtable2 as b on a.id=b.pid where a.id = $abname group by a.id LIMIT 0,$limitnum";
		$count= count($model->query("select a.*,count(b.id) as imgnum from $dbtable1 as a LEFT JOIN $dbtable2 as b on a.id=b.pid where a.id = $abname group by a.id"));//取得总数据
	   /*
	    分页开始 
	   */
	   import ( '@.ORG.Page' );//载入分页类
	   $page =  new Page($sql,5);
	   $sql =  $page->StartPage($count,"a.sortnum asc,a.id",true,true,'down');
       $list=$model->query($sql);
 	   $ButtonArray = array("<","<<",">>",">");
	   /**
	    Page类调用说明:参数1 自定义按钮显示 2. select text none 三种 3. true or false 4. 自定义样式 
       */
	   $pagebar=$page->EndPage($ButtonArray,"select",true,"green-black");
	   /*
	    分页结束
	   */
	   $this->assign("albumlist",$list);
	   $this->assign("pagebar",$pagebar);
	   $this->display();	        
        }
        
        
        
        
        
        
        
        public function tagfind()
        {
        
	$abname = $_POST['album_name'];
     	$model= new Model();
		$dbfix = C( "DB_PREFIX" );
		$dbtable1 = $dbfix."album";
        $dbtable2 = $dbfix."albumphoto";
	   
	    $limitnum='20';//每页显示条数
		$sql="select b.* from $dbtable2 as b where b.tag like '%$abname%' LIMIT 0,$limitnum";
		$count= count($model->query($sql));//取得总数据
	   /*
	    分页开始 
	   */
	   import ( '@.ORG.Page' );//载入分页类
	   $page =  new Page($sql,5);
	   $sql =  $page->StartPage($count,"b.id",true,true,'down');
       $list=$model->query($sql);
 	   $ButtonArray = array("<","<<",">>",">");
	   /**
	    Page类调用说明:参数1 自定义按钮显示 2. select text none 三种 3. true or false 4. 自定义样式 
       */
	   $pagebar=$page->EndPage($ButtonArray,"select",true,"green-black");
	   /*
	    分页结束
	   */
	   $this->assign("photolist",$list);
	   $this->assign("pagebar",$pagebar);
	   $this->display();
        }
        
        
        
        
        public function altagfind()
        {
        
	$abname = $_REQUEST['altag'];

          if(!$abname){$this->showmsg_box('系统查找不到该操作,请重试!',__APP__,0,3);}
	    
        $model= new Model();
		$dbfix = C( "DB_PREFIX" );
		$dbtable1 = $dbfix."album";
        $dbtable2 = $dbfix."albumphoto";
	   
	    $limitnum='3';//每页显示条数
		$sql="select a.*,count(b.id) as imgnum from $dbtable1 as a LEFT JOIN $dbtable2 as b on a.id=b.pid where a.tag='$abname' group by a.id LIMIT 0,$limitnum";
		$count= count($model->query("select a.*,count(b.id) as imgnum from $dbtable1 as a LEFT JOIN $dbtable2 as b on a.id=b.pid where a.tag='$abname' group by a.id"));//取得总数据
	   /*
	    分页开始 
	   */
	   import ( '@.ORG.Page' );//载入分页类
	   $page =  new Page($sql,5);
	   $sql =  $page->StartPage($count,"a.sortnum asc,a.id",true,true,'down');
       $list=$model->query($sql);
 	   $ButtonArray = array("<","<<",">>",">");
	   /**
	    Page类调用说明:参数1 自定义按钮显示 2. select text none 三种 3. true or false 4. 自定义样式 
       */
	   $pagebar=$page->EndPage($ButtonArray,"select",true,"green-black");
	   /*
	    分页结束
	   */
	   $this->assign("albumlist",$list);
	   $this->assign("pagebar",$pagebar);
           $this->assign("altag",$abname);
	   $this->display();	        
        }
}
?>

