<?php
include_once __DIR__ . '/../vendor/autoload.php';
define('FIXTURE_ROOT', realpath(__DIR__ . '/Fixtures'));

use Doctrine\Common\Annotations\AnnotationRegistry;

// Neat trick to autoload annotations without explicitly naming them.
AnnotationRegistry::registerLoader('class_exists');



