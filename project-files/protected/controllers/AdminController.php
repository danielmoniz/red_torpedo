<?php

/**
 * This class can store any actions that are supposed to be accessed by 
 * administrators only.
 */
class AdminController extends Controller {

    // PERMISSIONS CODE---------------------------------------
    /**
     * Returns a list of access control functions (??) or something.
     * @return array An array containing a list of access control functions (??)
     */
    public function filters() {
        return array('accessControl');
    }

    /**
     * Returns an array of arrays that contains controller-wide access controls.
     * @return Array An array of arrays containing permissions.
     */
    public function accessRules() {
        return array(
            array('allow',
                'roles'=>array('Admin'),
            ),
            array(
                'deny',
            ),
        );
    }
    // --------------------------------------------------------
    
    
}

?>
