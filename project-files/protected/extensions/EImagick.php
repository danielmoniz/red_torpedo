<?php

class EImagick extends CApplicationComponent
{
    public $_ImagickClient;

    public function init()
    {
        $this->_ImagickClient = new Imagick();
    }

    public function client()
    {
        return $this->_ImagickClient;
    }

    private function determineTransformation($sourceHeight, $sourceWidth, $targetWidth, $targetHeight)
    {
        $sourceRatio = $sourceHeight/$sourceWidth;
        $targetRatio = $targetHeight/$targetWidth;



        if($sourceWidth > $sourceHeight)
        {
            $oneSideCutSize = (int)(($width-$height)/2);
            $xCoord = (int)$oneSideCutSize;
            $yCoord = 0;
        }
        else
        {
            $oneSideCutSize = (int)(($height-$width)/2);
            $xCoord = 0;
            $yCoord = (int)$oneSideCutSize;
        }
        
    }

    public function generateImage($source, $targetFolder, $fileName, 
            $fileExtension, $width, $height, $targetExtension = "")
    {
        
        $image = $this->_ImagickClient;
        $image->readImage($source);
        
        $targetPath = $targetFolder.$fileName.".".$fileExtension;
        if(!empty($targetExtension))
        {
            $image->setImageFormat($targetExtension);
            $image->setBackgroundColor(new ImagickPixel('#FFFFFF'));
            $image->flattenImages();
            $targetPath = $targetFolder.$fileName.".".$targetExtension;
        }
        
        $sourceWidth = $image->getImageWidth();
        $sourceHeight = $image->getImageHeight();

        if($height < 80 && $width < 80)
        {
             $image->cropThumbnailImage($width, $height);
        }
        else if($sourceHeight > $height || $sourceWidth > $width)
        {
            $image->scaleImage($width, $height, true);
        } 

        $image->writeImage($targetPath);
        $image->clear();
    }

}

?>
