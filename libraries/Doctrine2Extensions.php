<?php
// protect script from direct access
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Doctrine2Extensions {
  public function __construct($doctrineOrmConfig, $eventManager) {
    $this->extensionsAutoload();
    $this->addTranslatableDriver($doctrineOrmConfig);
    $this->addListener($eventManager);
  }
  
  private function extensionsAutoload() {
    $classLoader = new \Doctrine\Common\ClassLoader('Gedmo', LIB_PATH_TODO);
    $classLoader->register();
  }
  
  private function addTranslatableDriver(\Doctrine\ORM\Configuration $doctrineOrmConfig) {
    $chainDriverImpl = new \Doctrine\ORM\Mapping\Driver\DriverChain();
    $translatableDriverImpl = $doctrineOrmConfig->newDefaultAnnotationDriver(
      LIB_PATH_TODO . '/Gedmo/Translatable/Entity'
    );
    $chainDriverImpl->addDriver($translatableDriverImpl, 'Gedmo\Translatable');
    $doctrineOrmConfig->setMetadataDriverImpl($chainDriverImpl);
  }
  
  private function addListener(\Doctrine\Common\EventManager $eventManager) {
    $translatableListener = new \Gedmo\Translatable\TranslationListener();
    $translatableListener->setTranslatableLocale('en_us');
    $eventManager->addEventSubscriber($translatableListener);
  }
}