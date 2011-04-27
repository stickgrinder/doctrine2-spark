<?php
use Doctrine\Common\ClassLoader,
    Doctrine\ORM\Configuration,
    Doctrine\ORM\EntityManager,
    Doctrine\Common\EventManager,
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

    // Set up class loading
    require_once $config['doctrine2']['common_lib_path'] . '/Doctrine/Common/ClassLoader.php';

    $doctrineClassLoader = new ClassLoader('Doctrine', $config['doctrine2']['orm_lib_path']);
    $doctrineClassLoader->register();
    $entitiesClassLoader = new ClassLoader('Entity', APPPATH . 'models/');
    $entitiesClassLoader->register();
    $proxiesClassLoader = new ClassLoader('Proxy', APPPATH . 'models/');
    $proxiesClassLoader->register();
    
    // Look through all extensions config
    foreach ($config['doctrine2_extensions'] as $extension) {
      // If even just one is active, load the Gedmo stuff and exit the loop
      if ($extension['active'] === true) {
        $extensionsClassLoader = new ClassLoader('Gedmo', SPARK_DOCTRINE2_PATH . '/vendors/DoctrineExtensions/lib');
        $extensionsClassLoader->register();
        $eventManager = new \Doctrine\Common\EventManager();
        break;
      }
    }

    // Set up caches
    $ormConfig = new Configuration;
    $cache = new ArrayCache;
    $ormConfig->setMetadataCacheImpl($cache);
    $ormConfig->setQueryCacheImpl($cache);

    // Set up driver
    $chainDriverImpl = new \Doctrine\ORM\Mapping\Driver\DriverChain();

    $Doctrine_AnnotationReader = new \Doctrine\Common\Annotations\AnnotationReader($cache);
    $Doctrine_AnnotationReader->setDefaultAnnotationNamespace('Doctrine\ORM\Mapping\\');
    $driver = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($Doctrine_AnnotationReader, APPPATH . 'models/Entity');
    $chainDriverImpl->addDriver($driver, 'Entity');

    if ($config['doctrine2_extensions']['translatable']['active'] === true) {
      $translatableDriverImpl = $ormConfig->newDefaultAnnotationDriver(
        SPARK_DOCTRINE2_PATH . '/DoctrineExtensions/lib/Gedmo/Translatable/Entity'
      );
      $chainDriverImpl->addDriver($translatableDriverImpl, 'Gedmo\Translatable');
    }    

    $ormConfig->setMetadataDriverImpl($chainDriverImpl);

    // Proxy configuration
    $ormConfig->setProxyDir(APPPATH . '/models/Proxies');
    $ormConfig->setProxyNamespace('Proxy');
    $ormConfig->setAutoGenerateProxyClasses(TRUE);

    // Set up logger
    //$logger = new EchoSqlLogger;
    //$ormConfig->setSqlLogger($logger);

    // Set up extensions
    // Translatable
    if ($config['doctrine2_extensions']['translatable']['active'] === true) {
      $translatableListener = new \Gedmo\Translatable\TranslationListener();
      $translatableListener->setTranslatableLocale('en_us');
      $eventManager->addEventSubscriber($translatableListener);
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