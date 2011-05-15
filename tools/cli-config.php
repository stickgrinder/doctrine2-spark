<?php

require_once DOCTRINE_LIB_DIR . '/vendor/doctrine-common/lib/Doctrine/Common/ClassLoader.php';

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\ORM', DOCTRINE_LIB_DIR);
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL', DOCTRINE_LIB_DIR . '/vendor/doctrine-dbal/lib');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\Common', DOCTRINE_LIB_DIR . '/vendor/doctrine-common/lib');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Symfony', DOCTRINE_LIB_DIR . '/vendor');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Entity', BASEPATH . '/application/models');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Proxies', BASEPATH . '/application/models');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Gedmo', DOCTRINE_EXTENSIONS_LIB_DIR);
$classLoader->register();

$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);

$chainDriverImpl = new \Doctrine\ORM\Mapping\Driver\DriverChain();
$driverImpl = $config->newDefaultAnnotationDriver(array(BASEPATH . '/application/models'));
$translatableDriverImpl = $config->newDefaultAnnotationDriver(
  DOCTRINE_EXTENSIONS_LIB_DIR . '/Gedmo/Translatable/Entity'
);
$chainDriverImpl->addDriver($driverImpl, 'Entity');
$chainDriverImpl->addDriver($translatableDriverImpl, 'Gedmo\Translatable');

$config->setMetadataDriverImpl($chainDriverImpl);

$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');

if (file_exists(__DIR__ . '/database.php')) {
  require_once __DIR__ . '/database.php';
  $connectionOptions = array(
    'driver'   => 'pdo_mysql',
    'user'     => $db['default']['username'],
    'password' => $db['default']['password'],
    'host'     => $db['default']['hostname'],
    'dbname'   => $db['default']['database'],
  );
}
else {
  $connectionOptions = array(
    'driver' => 'pdo_sqlite',
    'path' => 'database.sqlite',
  );
}

$eventManager = new \Doctrine\Common\EventManager();

$translatableListener = new \Gedmo\Translatable\TranslationListener();
$translatableListener->setTranslatableLocale('en_us');
$eventManager->addEventSubscriber($translatableListener);

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config, $eventManager);

$helpers = array(
  'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
  'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em),
);