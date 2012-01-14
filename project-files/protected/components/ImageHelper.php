<?php
/**
 * This class exists to help with image paths. Instead of building 
 * them manually whenever they are needed, we can create general functions 
 * here for cleaner code.
 */
class ImageHelper extends CComponent {

    /**
     * Gets the user avatar image.
     * @param int $userId The id of the relevant user.
     * @param int $height The height of the desired image.
     * @param int $width The width of the desired image.
     * @return string An image path.
     */
    public function getProfileImagePath($userId, $height = 200, $width = 150)
    {
        return "/" . Yii::app()->params['userAvatars']
                . floor($userId / 1000) . "/"
                . $userId . "_{$height}x{$width}.jpg";
    }
    
    /**
     * A wrapper function for getProfileImagePath. Returns the 
     * basic mini-size avatar image.
     * @param int $userId The id of the relevant user.
     * @return string An image path.
     */
    public function getMiniProfileImagePath($userId) {
        return self::getProfileImagePath($userId, $height = 40, $width = 40);
    }
    
}

?>
