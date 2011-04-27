<?php
define('DOCTRINE_LIB_DIR', realpath(__DIR__ . '/../vendors/Doctrine/lib'));
define('DOCTRINE_EXTENSIONS_LIB_DIR', realpath(__DIR__ . '/../vendors/DoctrineExtensions/lib'));

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
$classLoader = new \Doctrine\Common\ClassLoader('Proxy', BASEPATH . '/application/models');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Gedmo', DOCTRINE_EXTENSIONS_LIB_DIR);
$classLoader->register();

// Variable $helperSet is defined inside cli-config.php
require __DIR__ . '/cli-config.php';

$cli = new \Symfony\Component\Console\Application('Doctrine Command Line Interface', Doctrine\Common\Version::VERSION);
$cli->setCatchExceptions(true);
$helperSet = $cli->getHelperSet();
foreach ($helpers as $name => $helper) {
    $helperSet->set($helper, $name);
}
$cli->addCommands(array(
    // DBAL Commands
    new \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand(),
    new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),

    // ORM Commands
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
    new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),
    new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand(),

));
$cli->run();
