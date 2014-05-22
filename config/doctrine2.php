<?php
$config['doctrine2']['common_lib_path'] = SPARK_DOCTRINE2_PATH . '/vendors/Doctrine/lib';
$config['doctrine2']['dbal_lib_path']   = SPARK_DOCTRINE2_PATH . '/vendors/Doctrine/lib';
$config['doctrine2']['orm_lib_path']    = SPARK_DOCTRINE2_PATH . '/vendors/Doctrine/lib';

$config['doctrine2_extensions']['loggable']['active']      = true;
$config['doctrine2_extensions']['sluggable']['active']     = true;
$config['doctrine2_extensions']['timestampable']['active'] = true;
$config['doctrine2_extensions']['translatable']['active']  = true;
$config['doctrine2_extensions']['tree']['active']          = true;