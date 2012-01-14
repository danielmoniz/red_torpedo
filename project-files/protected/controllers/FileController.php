<?php

class FileController extends Controller
{
    public function actionUpload($uploadFormat ="",$uploadType = "")
    {

        //Parameters common to all files
        $userId = Yii::app()->user->id;
        $uploadProperties = array('image'=>array(
                                                'allowedFormats'=>array('jpg','jpeg','gif','png'),
                                                'sizeLimit'=> 2*1024*1024, // bytes
                                                 )
                                         );

        $baseFolder = Yii::app()->params['baseFolder'];
        $filename = "";
        $result = "";

        Yii::import("ext.EAjaxUpload.qqFileUploader");

        if($uploadFormat == "image")
        {
            $uploader = new qqFileUploader($uploadProperties['image']['allowedFormats'],
                                        $uploadProperties['image']['sizeLimit']);

            if($uploadType == "avatarimage")
            {
                $subfolder = Yii::app()->params['userAvatars'];
                $subfolder .= intval($userId/1000).DIRECTORY_SEPARATOR;
                $filename = $userId;
                $targetFolder = $baseFolder . $subfolder;
                $result = self::saveFile($uploader, $targetFolder, $filename, true);
                self::generateAvatarThumbs($targetFolder, $result);

            }
            else if ($uploadType == "postimage")
            {
                $subfolder = Yii::app()->params['postImages'];
                $subfolder .= intval($userId/1000).DIRECTORY_SEPARATOR.$userId.DIRECTORY_SEPARATOR;
                $filename = $userId."_".time();
                $targetFolder = $baseFolder . $subfolder;
                $result = self::saveFile($uploader, $targetFolder, $filename);
                self::generatePostImageThumbs($targetFolder, $result);
            }
        }

        if (isset($result['filename'])){
           $result['filename'] = $result['filename'];
           $result['source'] = Yii::app()->params['basePath'] . Yii::app()->params['postImages'] .
                        intval($userId / 1000).DIRECTORY_SEPARATOR . $userId . 
                        DIRECTORY_SEPARATOR.$result['filename'].'_200x150';
        }

        $result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);

        echo $result; //it's array
        exit;
    }

    private function generateAvatarThumbs($targetFolder, $image)
    {
        $sourcePath = $targetFolder.$image['filename'].".".$image['extension'];
        Yii::app()->imagick->generateImage($sourcePath, $targetFolder, 
                $image['filename']."_40x40", $image['extension'], 40, 40, 'jpg');
        Yii::app()->imagick->generateImage($sourcePath, $targetFolder, 
                $image['filename']."_200x150", $image['extension'], 200, 150, 'jpg');
    }

    private function generatePostImageThumbs($targetFolder, $image)
    {
        $sourcePath = $targetFolder.$image['filename'].".".$image['extension'];
        Yii::app()->imagick->generateImage($sourcePath, $targetFolder, 
                $image['filename']."_60x60", $image['extension'], 60, 60);
        Yii::app()->imagick->generateImage($sourcePath, $targetFolder, 
                        $image['filename']."_200x150", $image['extension'], 200, 150);
    }

    private function saveFile($uploader, $targetFolder, $filename, $replace = false)
    {
        Yii::app()->file->set($targetFolder);
        
        if (!Yii::app()->file->exists){
            Yii::app()->file->CreateDir(0777, $targetFolder);
        }

        return $uploader->handleUpload($targetFolder, $filename, $replace);
    }

    public function allowedActions() {
        //Actions allowed by anyone
        //eg: return 'index, suggestedTags';
        return 'upload';
    }
}

?>