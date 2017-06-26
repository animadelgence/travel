<?php

namespace Plugin\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class imageupload extends routeplugin {
	public function upload($fileSize,$fileName,$files,$folderName,$imageName="",$fileType="")
     {
       $res = array();
       if($fileSize >= 5)
        {
            $res['originalPath'] = '';
            $res['exterror']=$fileName.' size is more than 5MB. Please upload an image less than 5MB';
            $res['thumbPath'] = '';
        	return json_encode($res);
			exit;
        }
	 if($files == 1)
        {
            $res['originalPath'] = '';
            $res['exterror']=$fileName.' size is more than 5MB. Please upload an image less than 5MB';
            $res['thumbPath'] = '';
			return json_encode($res);
			exit;
        }
          if ($fileType == 'image/png' || $fileType == 'image/jpg' || $fileType == 'image/jpeg' || $fileType == 'image/gif') {
                //echo "inside if";exit;
     		$uploadObj = new \Zend\File\Transfer\Adapter\Http();
     		$uploadObj->setDestination($_SERVER['DOCUMENT_ROOT'].$folderName);
			$upload =  $this->dynamicPath();
			$ext = $this->_findexts($fileName);
            if(empty($imageName)){
                $newfilename = md5(time()).".".$ext;
               
            } else{
                $newfilename = $imageName.".".$ext;
               
            }
     		$uploadObj->addFilter('Rename', array('target' =>$_SERVER['DOCUMENT_ROOT'].$folderName.$newfilename,'overwrite' => true));
     		$ups = $upload.$folderName.$newfilename;
     		if($uploadObj->receive($fileName)) {

                    $pathThumb = $this->makeThumbnails($_SERVER['DOCUMENT_ROOT'].$folderName, $newfilename);
     			$res['originalPath'] = $ups;
			$res['exterror'] = 0;
			$res['thumbPath'] = $upload.$folderName.$pathThumb;
			return json_encode($res);
				exit;
     		}
		else
		{
            $res['originalPath'] = '';
			$res['exterror'] = "Error in image recieve";
            $res['thumbPath'] = '';
			return json_encode($res);
			exit;
		}
         }
         else
         {
             $res['originalPath'] = '';
             $res['exterror'] = 'Sorry, ' . $fileName . ' is invalid, allowed extensions is : png,jpg,jpeg,gif';
             $res['thumbPath'] = '';
             return json_encode($res);
             exit;
         }


     }
	 public function _findexts($filename)
     {
     	return pathinfo($filename, PATHINFO_EXTENSION);
     }
	public function makeThumbnails($updir, $img)
     {
     	$thumbnail_width = 140;                     //thumbnail
     	$thumbnail_height = 105;
     	$thumb_beforeword = "thumb_";
     	if(!is_dir($updir."thumb")){
     		@mkdir( $updir."thumb", 0777,true );

     		chmod($updir."thumb",0777);

     	}
     	$arr_image_details = getimagesize("$updir"."$img"); // pass id to thumb name
     	$original_width = $arr_image_details[0];
     	$original_height = $arr_image_details[1];
     	if ($original_width > $original_height) {
     		$new_width = $thumbnail_width;
     		$new_height = intval($original_height * $new_width / $original_width);
     	} else {
     		$new_height = $thumbnail_height;
     		$new_width = intval($original_width * $new_height / $original_height);
     	}
     	$dest_x = intval(($thumbnail_width - $new_width) / 2);
     	$dest_y = intval(($thumbnail_height - $new_height) / 2);
     	if ($arr_image_details[2] == 1) {
     		$imgt = "ImageGIF";
     		$imgcreatefrom = "ImageCreateFromGIF";
     	}
     	if ($arr_image_details[2] == 2) {
     		$imgt = "ImageJPEG";
     		$imgcreatefrom = "ImageCreateFromJPEG";
     	}
     	if ($arr_image_details[2] == 3) {
     		$imgt = "ImagePNG";
     		$imgcreatefrom = "ImageCreateFromPNG";
     	}
     	if ($imgt) {
     		$old_image = $imgcreatefrom("$updir"."$img");
     		$new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
     		imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
     		$imgt($new_image, "$updir".'thumb/'."$thumb_beforeword"."$img");
     		return 'thumb/'."$thumb_beforeword"."$img";
     	}
     }

    public function resizeUpload($fileSize,$fileName,$files,$folderName)
     {
		$res = array();
		if($fileSize >= 1)
        {
        	$res['exterror']=$fileName.' size is more than 1MB. Please upload an image less than 1MB';
        	return json_encode($res);
			exit;

        }
		if($files == 1)
        {
            $res['exterror']=$fileName.' size is more than 1MB. Please upload an image less than 1MB';
			return json_encode($res);
			exit;

        }

     		$uploadObj = new \Zend\File\Transfer\Adapter\Http();
     		$uploadObj->setDestination($_SERVER['DOCUMENT_ROOT'].$folderName);
			$upload =  $this->dynamicPath();
			$ext = $this->_findexts($fileName);
     		$newfilename = md5(time()).".".$ext;
     		$uploadObj->addFilter('Rename', array('target' =>$_SERVER['DOCUMENT_ROOT'].$folderName.$newfilename,'overwrite' => true));
     		$ups = $upload.$folderName.$newfilename;
     		if($uploadObj->receive($fileName)) {

            $pathThumb = $this->resizeImage($_SERVER['DOCUMENT_ROOT'].$folderName, $newfilename);
            $res['originalPath'] = $ups;
			$res['exterror'] = 0;
			$res['thumbPath'] = $upload.$folderName.$pathThumb;
			return json_encode($res);
				exit;
     		}
		else
		{
			$res['exterror'] = "Error in image recieve";
			return json_encode($res);
			exit;
		}
     }

    public function resizeImage($updir, $img)
     {
     	$thumbnail_width = 640;             //resizing image
     	$thumbnail_height = 480;
     	$thumb_beforeword = "thumb_";
     	if(!is_dir($updir."thumb")){
     		@mkdir( $updir."thumb", 0777,true );

     		chmod($updir."thumb",0777);

     	}
     	$arr_image_details = getimagesize("$updir"."$img"); // pass id to thumb name
     	$original_width = $arr_image_details[0];
     	$original_height = $arr_image_details[1];
     	if ($original_width > $original_height) {
     		$new_width = $thumbnail_width;
     		$new_height = intval($original_height * $new_width / $original_width);
     	} else {
     		$new_height = $thumbnail_height;
     		$new_width = intval($original_width * $new_height / $original_height);
     	}
     	$dest_x = intval(($thumbnail_width - $new_width) / 2);
     	$dest_y = intval(($thumbnail_height - $new_height) / 2);
     	if ($arr_image_details[2] == 1) {
     		$imgt = "ImageGIF";
     		$imgcreatefrom = "ImageCreateFromGIF";
     	}
     	if ($arr_image_details[2] == 2) {
     		$imgt = "ImageJPEG";
     		$imgcreatefrom = "ImageCreateFromJPEG";
     	}
     	if ($arr_image_details[2] == 3) {
     		$imgt = "ImagePNG";
     		$imgcreatefrom = "ImageCreateFromPNG";
     	}
     	if ($imgt) {
     		$old_image = $imgcreatefrom("$updir"."$img");
     		$new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
     		imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
     		$imgt($new_image, "$updir".'thumb/'."$thumb_beforeword"."$img");
     		return 'thumb/'."$thumb_beforeword"."$img";
     	}
     }

}
?>
