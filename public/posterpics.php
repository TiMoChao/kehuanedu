<?php
/*
 * 上传资源图片
 * @auther luochao
*/
if($_POST['targetFolder']){
	if($_POST['id']){ //若需要以id命名文件夹，则需要获取id
		$targetFolder = $_SERVER['DOCUMENT_ROOT'].$_POST['targetFolder'].'/'.$_POST['id'];
	}else{
		$targetFolder = $_SERVER['DOCUMENT_ROOT'].$_POST['targetFolder'];
	}

	if(!is_dir($targetFolder)){    //建立存放路径
		$dirs = explode('/',$targetFolder);
		$num = count($dirs);
	
		for($i=1, $dir = $dirs[0]; $i<$num; $i++){
			$dir = $dir.'/'.$dirs[$i];
			if(!is_dir($dir)){
				mkdir($dir );
				chmod($dir,0777);
			}
		}
	}
	if (!empty($_FILES)) {print_r($_FILES);
		$tempFile = $_FILES['Filedata']['tmp_name'];
		$targetPath = $targetFolder;
		$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	
		if($_POST['fileTypes']){   // Validate the file type
			$fileTypes = array();  //用来存放文件扩展名的数组
			$string = $_POST['fileTypes'];
			$types = explode(',', $string);
			$num = count($types);
			for($i=0; $i<$num; $i++){
				array_push($fileTypes, $types[$i]);
			}
		}else {
			echo "未获取到上传文件格式要求！";
			exit;
		}

		$fileParts = pathinfo($_FILES['Filedata']['name']);
	
		if (in_array($fileParts['extension'],$fileTypes)) {
			move_uploaded_file($tempFile,$targetFile);
			echo $_FILES['Filedata']['name'];
		} else {
			echo 'Invalid file type.';
			exit;
		}
	}
}else{
	echo '未获取到存放路径！';
	exit;
}
?>
