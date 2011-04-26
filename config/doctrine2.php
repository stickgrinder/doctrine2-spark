<?php
require_once SPARK_DOCTRINE2_PATH . '/vendors/Doctrine/Common/EventManager.php';

$config['doctrine2_event_manager']   = new \Doctrine\Common\EventManager();
$config['load_doctrine2_extensions'] = TRUE;