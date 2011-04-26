<?php
use Doctrine\Common\ClassLoader,
    Doctrine\ORM\Configuration,
    Doctrine\ORM\EntityManager,
    Doctrine\Common\Cache\ArrayCache,
    Doctrine\DBAL\Logging\EchoSqlLogger;

// protect script from direct access
if (!defined('BASEPATH')) exit('No direct script access allowed');

// find out this spark library position
// (dirname is applied twice since this script is located in "libraries" directory
// inside the spark root, so single dirname won't help).
define('SPARK_DOCTRINE2_PATH', dirname(__DIR__));

class Doctrine2 {

  public $em = null;

  public function __construct($config = array())
  {
    // load database configuration from CodeIgniter
    require_once APPPATH . 'config/database.php';
    // Set up class loading. You could use different autoloaders, provided by your favorite framework,
    // if you want to.
    require_once SPARK_DOCTRINE2_PATH . '/vendors/Doctrine/Common/ClassLoader.php';

    $doctrineClassLoader = new ClassLoader('Doctrine',  SPARK_DOCTRINE2_PATH . '/vendors');
    $doctrineClassLoader->register();
    $entitiesClassLoader = new ClassLoader('models', rtrim(APPPATH, '/'));
    $entitiesClassLoader->register();
    $proxiesClassLoader = new ClassLoader('Proxies', APPPATH . 'models/proxies');
    $proxiesClassLoader->register();

    // Set up caches
    $ormConfig = new Configuration;
    $cache = new ArrayCache;
    $ormConfig->setMetadataCacheImpl($cache);
    $ormConfig->setQueryCacheImpl($cache);

    // Set up driver
    $Doctrine_AnnotationReader = new \Doctrine\Common\Annotations\AnnotationReader($cache);
    $Doctrine_AnnotationReader->setDefaultAnnotationNamespace('Doctrine\ORM\Mapping\\');
    $driver = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($Doctrine_AnnotationReader, APPPATH.'models');
    $ormConfig->setMetadataDriverImpl($driver);

    // Proxy configuration
    $ormConfig->setProxyDir(APPPATH.'/models/proxies');
    $ormConfig->setProxyNamespace('Proxies');

    // Set up logger
    //$logger = new EchoSqlLogger;
    //$ormConfig->setSqlLogger($logger);

    $ormConfig->setAutoGenerateProxyClasses(TRUE);

    $eventManager = $config['doctrine2_event_manager'];
    if ($config['load_doctrine2_extensions']) {
      require_once 'Doctrine2Extensions.php';
      $extensions = new Doctrine2Extensions($ormConfig, $eventManager);
    }

    // Database connection information
    $connectionOptions = array(
      'driver'   => 'pdo_mysql',
      'user'     => $db['default']['username'],
      'password' => $db['default']['password'],
      'host'     => $db['default']['hostname'],
      'dbname'   => $db['default']['database'],
    );

    // Create EntityManager
    $this->em = EntityManager::create($connectionOptions, $ormConfig, $eventManager);
  }
}