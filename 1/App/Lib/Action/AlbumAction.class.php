<?php
 include_once( 'config.php' );
        include_once( 'saetv2.ex.class.php' );
class AlbumAction extends PublicAction{
	
	public function index(){
		//列表相册
        session_start();
	error_reporting(1);


	$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
	$ms  = $c->user_timeline_by_id(); // done

	$uid_get = $c->get_uid();
	$uid = $uid_get['uid'];
//$c->upload('这是我通过api接口发布的图片微博','logo.jpg');
	$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
	$usrname = $user_message['name'];
          /*
        $user = M('Usrinfo');
        $albuminfo = M('Albuminfo');
        $album = M('Album');
        $rst=$user->where("usrid=$uid")->find();
        $pid = 0;
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
*//*
        $model= new Model();
		$dbfix = "photo_";
		$dbtable1 = $dbfix."album";
        $dbtable2 = $dbfix."albumphoto";
        $dbtable3 = $dbfix."albuminfo";
        $sql="select a.*,count(b.id) as imgnum from $dbtable1 as a LEFT JOIN $dbtable2 as b on a.id=b.pid ,$dbtable3 as c where a.id=c.pid and c.usrname='$usrname' group by a.id order by a.sortnum asc,a.id desc";
      
          //   $sql="select a.*,count(b.id) as imgnum from $dbtable1 as a LEFT JOIN $dbtable2 as b on a.id=b.pid from a, $dbtable3 as c where a.id=c.pid and c.usrname = '$usrname' group by a.id LIMIT 0,$limitnum";
          //$sql= "select a.*,count(b.id) as imgnum from $dbtable1 as a, $dbtable2 as b, $dbtable3 as c where a.id=b.pid and a.id = c.pid and c.usrname = '$usrname' group by a.id order by a.sortnum asc,a.id desc";
	$albumarr= $model->query($sql);
	    $this->assign("albumlist",$albumarr);
	    $this->display();
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
	
	public function newalbum(){
		//新建相册
                   session_start();
	error_reporting(1);


	$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
	$ms  = $c->user_timeline_by_id(); // done

	$uid_get = $c->get_uid();
	$uid = $uid_get['uid'];
//$c->upload('这是我通过api接口发布的图片微博','logo.jpg');
	$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
	$usrname = $user_message['name'];
        
        $alname1=$_POST['album_name'];
        if($alname1==""){	$this->showmsg_box('主题名不能为空',"",0,10);}
        $albuminfo=M("Albuminfo");        
        $model=D("Album");
          //对此处修改，$vo = $model->create()删除，改为1
          /*
		if($vo = $model->create()) {
			$result	=$model->add();
			if($result) {
			  $this->showmsg_box('执行新增相册操作成功',U("index"),1,5);
			}else{
			  $this->showmsg_box('执行新增相册操作失败',"",0,10);
			}
		}else{
			$this->error($model->getError());
		}
*/
		$mydata['album_name']=$alname1;
                $mydata['create_time']=time();
                $mydata['owner'] = $usrname;
		$mydata['tag'] = $_POST['altag'];
		if($_POST['mypassword']!="")
{
$mydata['password']=$_POST['mypassword'];
}
		while(list($key,$val) = each($vo)) { 
    echo "other list of $val.<br />"; 
}
		if($mydata) {
			$result	=$model->add($mydata);
                 	 if($result) {
                           //$tmp=$mydata['album_name'];
                           //   $pid=$model->where('create_time='.$mydata['create_time'])->
                           //  $sql = "select id from photo_album where album_name='$tmp' and create_time=".$mydata['create_time'];
                           //   $rst = $model->query($sql);
                           	$aldata['pid']=$result;
                		$aldata['usrname']=$usrname;
                                $albuminfo->add($aldata);
                         
                  	  $this->showmsg_box('执行新增相册操作成功',U("index"),1,5);
                  	}else{
                  	  $this->showmsg_box('执行新增相册操作失败',"",0,10);
                  	}
		}else{
			$this->error($model->getError());
		}
          //	$mydata['album_name']="qwert";
        
          //$model->add($mydata);
	}
	
	public function editalbumname(){
		//修改相册名称
		$model	=D('Album');
		$albumname=removehtml($_POST['album_name']);
		$oldalbumname=removehtml($_GET['oldname']);
		$id=$_GET['id'];
		if(!$id){$this->ajaxReturn($oldalbumname,'相册名称不能为空!',0);exit;}
		if(iLength($albumname)<1 || iLength($albumname)>10){$this->ajaxReturn($oldalbumname,'相册名称不能空为,最多10字符!',0);exit;}
		$iseditok=$model->checkiquetitle("album_name='$albumname' and id<>$id");
		if(!$iseditok){$this->ajaxReturn($oldalbumname,'已存在相同相册名称!',0);exit;}
		$map=array(
		"album_name"=>$albumname
		);
		$result	=$model->where("id=$id")->save($map);
		if(false===$result) {
		  $this->ajaxReturn($oldalbumname,'修改失败!',0);
		}else{
		  $this->ajaxReturn(0,$albumname,1);
		}

	}
	
	public function sortalbum(){
		//排序相册
        $model=D("Album");
		$albumarr=$model->order("sortnum asc,id desc")->select();
	    $this->assign("albumlist",$albumarr);
	    $this->display();
	}
	
	public function savesortalbum(){
		//相册排序保存
            $model=M("Album");
		    $iid=$_REQUEST['mysortresult'];
			$k=1;
			foreach($iid as $key => $value){
			$map=array(
			"sortnum"=>$k
			);
			$model->where("id=".$value)->save($map);
			unset($map);
			$k++;
			}
		   $this->ajaxReturn(1,"排序相册成功",1);
	}
	
	public function delalbum(){
		//删除相册,同时将删除此相册下所有图片
		$id=$_REQUEST['id'];
		if(!$id){$this->showmsg_box('系统查找不到该操作,请重试!',"",0,5);}
		$modelo=M("Album");
		$sss=$modelo->where("id=$id")->getField('owner');

		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		$ms  = $c->user_timeline_by_id(); // done

		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		//$c->upload('这是我通过api接口发布的图片微博','logo.jpg');
		$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
		$usrname = $user_message['name'];

		if($usrname!=$sss){$this->showmsg_box('只有创建者能进行删除相册操作',"",0,5);}
		$model=M("Album");
		$modelp = M ('Albumphoto'); 
		$delb=$model->where("id=$id")->find();
		
		
		if(!$delb) {
		$this->showmsg_box('系统查找不到该操作,请重试!',"",0,5);
		}else{
		$dtitle=$delb['album_name'];
		$model->delete();
		$delp=$modelp->where("pid=$id")->select();
		if($delp){
        foreach($delp as $k=>$v){
          unlink('./Public/Uploads/Albums/b_'.$v['pickey']);
	       unlink('./Public/Uploads/Albums/s_'.$v['pickey']);
		}
		$modelp->where("pid=$id")->delete();
		}
		$this->showmsg_box('删除相册成功',"",1,3);
		}
	}
	
	public function listphoto(){
		//列有相册图片
		$id=$_REQUEST['id'];
		if(!$id){$this->showmsg_box('操作失败',"",0,5);}
		$model=M("Album");
		$albumarr=$model->where("id=$id")->find();
		if(!$albumarr){$this->showmsg_box('操作失败',"",0,5);}
		
	    $Model=D("Albumphoto");
	    $limitnum='3';//每页显示条数
		$sql=$Model->getinitsql("pid=$id",$limitnum);//取到sql
		$count= $Model->where("pid=$id")->count();//取得总数据
	   /*
	    分页开始 
	   */
		 import ( '@.ORG.Page' );//载入分页类
	   $page =  new Page($sql,5);
	   $sql =  $page->StartPage($count,"sortnum asc,id",true,true,'down');
       $list=$Model->query($sql);
 	   $ButtonArray = array("<","<<",">>",">");
	   /**
	    Page类调用说明:参数1 自定义按钮显示 2. select text none 三种 3. true or false 4. 自定义样式 
       */
	   $pagebar=$page->EndPage($ButtonArray,"select",true,"green-black");
	   /*
	    分页结束
	   */
	   
	   $this->assign("albumarr",$albumarr);
	   $this->assign("albumlist",$list);
	   $this->assign("pagebar",$pagebar);
	   $this->display();

	}
	
	public function editpname(){
		//修改图片名称
		$model =D ('Albumphoto');
		$imgname=removehtml($_POST['photo_name']);
		$oldimgname=removehtml($_GET['oldname']);
		$id=$_GET['id'];
		if(!$id){$this->ajaxReturn($oldimgname,'图片名称不能为空!',0);exit;}
		if(iLength($imgname)<1 || iLength($imgname)>10){$this->ajaxReturn($oldimgname,'图片不能为空,最多10字符!',0);exit;}
		$iseditok=$model->checkiquetitle("name='$imgname' and id<>$id");
		if(!$iseditok){$this->ajaxReturn($oldimgname,'已存在相同的图片名称!',0);exit;}
		$map=array(
		"name"=>$imgname
		);
		$result	=$model->where("id=$id")->save($map);
		if(false===$result) {
		  $this->ajaxReturn($oldimgname,'执行修改图片名称失败!',0);
		}else{
		  $this->ajaxReturn(0,$imgname,1);
		}

	}
	
	public function delimg(){
		 
		 $model = M ('Albumphoto');
		 $modela =D ('Album');
		$id=$_REQUEST['id'];
		if(!$id){$this->showmsg_box('系统查找不到该操作,请重试!',"",0,5);}
	     $delresult=$model->find($id);
		 if($delresult){
                   unlink('./Public/Uploads/Albums/b_'.$delresult['pickey']);
                   unlink('./Public/Uploads/Albums/s_'.$delresult['pickey']);
		   if($delresult['iscover']==1){
			 $data['cover']="";
			 $pid=$delresult['pid']; 
			 $result2=$modela->where("id=$pid")->save($data);
		   }
		   $model->where("id=$id")->delete();
		   $this->showmsg_box('删除成功',"",1,3);
		 }else{
		   $this->showmsg_box('删除相册图片失败',"",0,5);
		 }
		
	}
	
	
	public function uploadimgs(){
   
		$id=$_REQUEST['id'];
		if(!$id){$this->showmsg_box('系统查找不到该操作,请重试!',"",0,5);}
		$now = date("Ymd_His");
		$code = $now."_".mt_rand(10000, 99999);
          //  if(!$_POST['mytag'])
          //    	$this->showmsg_box('KOng!',"",0,5);
          	if($_FILES) { 
		//如果有文件上传 上传附件
                  // import("@.ORG.UploadFile");
                    import("ORG.Net.UploadFile");
        $upload = new UploadFile(); 
        //设置上传文件大小 
                  /*
        $upload->maxSize  = 2048000 ; 
        //设置上传文件类型 
		$upload -> allowExts  = array("jpg", "gif", "png","jpeg"); 
        //设置附件上传目录 
                  $upload->savePath =  "./Public/Uploads/Albums/"; 
        //设置需要生成缩略图，仅对图像文件有效 
        $upload->thumb =  true; 
		// 设置引用图片类库包路径
		$upload->imageClassPath = '@.ORG.Image'; 
        //设置需要生成缩略图的文件后缀 
        $upload->thumbPrefix   =  'b_,s_';  //生产3张缩略图 
       //设置缩略图最大宽度 
        $upload->thumbMaxWidth =  '600,150'; 
       //设置缩略图最大高度 
        $upload->thumbMaxHeight = '600,150'; 
       //设置上传文件规则 
       $upload->saveRule = $code; 
       //删除原图 
       $upload->thumbRemoveOrigin = true; 
*/
       // if(!$upload->upload()) { 
            //捕获上传异常 
         //   $this->error($upload->getErrorMsg()); 
       // }else { 
                  //  $fileinfo = $upload->getUploadFileInfo();//得到已上传文件的信息数组
       // } 
       
        $upload->maxSize = 3292200;
        //设置上传文件类型
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        //设置附件上传目录
                  $upload->savePath = './Public/Uploads/Albums/';
        //设置需要生成缩略图，仅对图像文件有效
        $upload->thumb = true;
        // 设置引用图片类库包路径
        $upload->imageClassPath = '@.ORG.Image';
        //设置需要生成缩略图的文件后缀
                  //  $upload->thumbPrefix = 'm_,s_';  //生产2张缩略图
                   $upload->thumbPrefix   =  'b_,s_';  //生产3张缩略图 
        //设置缩略图最大宽度
        $upload->thumbMaxWidth = '400,100';
        //设置缩略图最大高度
        $upload->thumbMaxHeight = '400,100';
        //设置上传文件规则
        $upload->saveRule = $code;
        //删除原图
        $upload->thumbRemoveOrigin = true;
        if (!$upload->upload()) {
            //捕获上传异常
            $this->error($upload->getErrorMsg());
        } else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            import("@.ORG.Image");
            //给m_缩略图添加水印, Image::water('原文件名','水印图片地址')
          // Image::water($uploadList[0]['savepath'] . 'm_' . $uploadList[0]['savename'], '../Public/Images/logo2.png');
          // $_POST['image'] = $uploadList[0]['savename'];
        }
       
       
       
        $model = M("Albumphoto"); 
        //保存当前数据对象 
           $data['pickey']=$uploadList[0]['savename']; 
        $data['pid']=$id; 
	$data['name'] = msubstr($uploadList[0]['name'], 0, strrpos($uploadList[0]['name'], '.'),'utf-8',false);
        $data['create_time']=time(); 
        $data['tag']=$_POST['mytag'];
	//$this->showmsg_box($data['tag'],"",0,5);
                  //  $data['pickey']=$fileinfo[0]['savename']; 
                  //  $data['pid']=$id; 
                  //$data['name'] = $fileinfo[0]['name'];
                  // $data['create_time']=time(); 
                  //   $data['pickey']=time(); 
	//$data['pickey']=$_FILES["file_upload"]["name"];
      //  $data['pid']=$id;
                  //	$data['pid']=time();
//$data['name']=$_FILES["file_upload"]["name"];
                  // $data['name']=time();
                  //$data['create_time']=time();
        $list=$model->add($data); 
        
                  /*              if($list!=false){
          $this->success ('上传图片成功！'); 
        }else{ 
           $this->error ('上传图片失败!'); 
        }
*/
        
        
        
		
		if($this->album_config[0]==1){
          import("@.ORG.Image");
		  Image::water("./Public/Uploads/Albums/b_".$fileinfo[0]['savename'],"./Public/mark/mark.png","",$this->album_config[2],$this->album_config[1]);
		}
		
      if($list!=false){
          $this->success ('上传图片成功！'); 
        }else{ 
          echo "上传文件失败,写入数据库操作不成功!";exit;
        }
                }
                else
                {
             $this->error ('上传图片失败!');
                }
	}
	
	public function setcover(){
	 //设为封面图	
		$modela =D ('Album');
		$modelp =D ('Albumphoto');
		$id=$_REQUEST['id'];
		$pid=$_REQUEST['pid'];
		if(!$id || !$pid){$this->showmsg_box('系统查找不到该操作,请重试!','',0,5);}
	    $presult=$modelp->where("pid=$pid and id=$id")->find();
		if(!$presult){
		 $this->showmsg_box('系统查找不到该操作,请重试!','',0,5);
		}else{
		  $modelp->doupdate('iscover=0',"pid=$pid");
		  $data['iscover'] = 1;
		  $data2['cover']=$presult['pickey'];
		  $pid=$presult['pid'];
		  $result=$modelp->where("id=$id")->save($data);
		  $result2=$modela->where("id=$pid")->save($data2);
		  if($result && $result2) {
		   $this->ajaxReturn(1,"操作成功",1);  
		  }else{
		   $this->ajaxReturn(0,"操作失败",2);  
		  }
		}
	}
	
	public function sortimg(){
		//图片排序
        $model=D("Albumphoto");
		$modela =D ('Album');
		$id=$_REQUEST['id'];
		if(!$id){$this->showmsg_box('系统查找不到该操作,请重试!','',0,5);}
	    $albumarr=$modela->where("id=$id")->find();
		if(!$albumarr){$this->showmsg_box('系统查找不到该操作,请重试!','',0,5);}
		$imgarr=$model->where("pid=$id")->order("sortnum asc,id desc")->select();
	    $this->assign("albumarr",$albumarr);
	    $this->assign("imglist",$imgarr);
	    $this->display();
	}
	
	public function savesortimg(){
		//图片排序保存
			$pid=$_REQUEST['pid'];
			if(!$pid){$this->showmsg_box('系统查找不到该操作,请重试!','',0,5);}
            $model=M("Albumphoto");
		    $iid=$_REQUEST['mysortresult'];
			$k=1;
			foreach($iid as $key => $value){
			$map=array(
			"sortnum"=>$k
			);
			$model->where("pid=$pid and id=".$value)->save($map);
			unset($map);
			$k++;
			}
		   $this->ajaxReturn(1,"排序图片成功",1);
	}
	
}
?>
