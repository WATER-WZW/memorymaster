<?php
        include_once( 'config.php' );
        include_once( 'saetv2.ex.class.php' );

class IndexAction extends PublicAction{
	
    public function index(){

	session_start();
	error_reporting(1);


	$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
	$ms  = $c->user_timeline_by_id(); // done

	$uid_get = $c->get_uid();
	$uid = $uid_get['uid'];
//$c->upload('这是我通过api接口发布的图片微博','logo.jpg');
	$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
	$usrname = $user_message['name'];
  
      /*   $user = M('Usrinfo');
        $albuminfo = M('Albuminfo');
        $album = M('Album');
        $rst=$user->where("usrid=$uid")->find();
        $pid = NULL;
        if($rst)
        {
        	$pid = $albuminfo->where("usrname='$usrname'")->field('pid')->select();
        }
        else
        {
        	$data['usrname'] = $usrname;
                $data['usrid'] = $uid;
                $data['create_time'] = time();
        	$user->add($data);	
        }
	if($pid)
        	$list =	$album->where("id=$pid")->limit('5')->select();
*/
      
        $model= new Model();
		$dbfix = C( "DB_PREFIX" );
		$dbtable1 = $dbfix."album";
        $dbtable2 = $dbfix."albumphoto";
  	$dbtable3 = $dbfix."albuminfo";
          $limitnum='3';//每页显示条数
      	$sql="select a.*,count(b.id) as imgnum from $dbtable1 as a LEFT JOIN $dbtable2 as b on a.id=b.pid ,$dbtable3 as c where a.id=c.pid and c.usrname='$usrname' group by a.id LIMIT 0,$limitnum";
      	$count= count($model->query("select a.*,count(b.id) as imgnum from $dbtable1 as a LEFT JOIN $dbtable2 as b on a.id=b.pid ,$dbtable3 as c where a.id=c.pid and c.usrname='$usrname' group by a.id"));//取得总数据
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

	
}
?>
