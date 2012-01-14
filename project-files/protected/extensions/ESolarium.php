<?php
/*
 * Mad thanks to Rawtaz on #yii IRC channel
 */

class ESolarium extends CApplicationComponent
{
    
    /**
     * @var string the hostname on which SOLR is running
     */
    public $host='localhost';

    /**
    * @var int sever port for SOLR
    */
    public $port='8983';

    /**
    * @var int sever port for secure SOLR connections
    */
    public $securePort='8443';

    public $core='';

    /**
     * @var boolean whether the connection should be use the secure port for a secure connection
     */
    public $secureConnect=false;

    /**
    * @var string the url path for the index against which you want to execute commands
    */
    public $path = '/solr';


    public $_solariumClient;


    public $libraryPathAlias = 'application.extensions.solarium';

    public function init()
    {

        if (!extension_loaded('solr'))
        {
            throw new CException(Yii::t('yiisolr','The YiiSolr extension requires the PHP Solr extension to be loaded. Please see http://php.net/manual/en/book.solr.php for more information on this extension.'));
        }

        if(Yii::app()->params['SolariumAutoloderLoaded'] == 0) {
            include(Yii::getPathOfAlias($this->libraryPathAlias) . '/Autoloader.php');
            Yii::registerAutoLoader(array('Solarium_Autoloader', 'load'));
            Yii::app()->params['SolariumAutoloderLoaded'] = 1;
        }

        $options = array
        (
            'adapteroptions' => array (
                'host'    => $this->host,
                'port'    => $this->port,
                'path'    => $this->path,
                'core'    => $this->core,
                'timeout' => 5,
            )
        );

        $this->_solariumClient = new Solarium_Client($options);
    }

    public function client() {
        return $this->_solariumClient;
    }



}
?>
