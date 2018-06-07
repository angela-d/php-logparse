<?php

class Autoload {

  static public function load() {
    // since we already know the directories, loop in an array to include them upon initialization
    $paths = array(
      'classes',
      'controller',
      'model',
      'view'
    );

    // include all of the subdirectories
    foreach($paths as $path) {
      // make sure the file exists before inclusion
      if(file_exists($path . DIRECTORY_SEPARATOR . 'logparser.php')) {
        require_once($path . DIRECTORY_SEPARATOR . 'logparser.php');
      }
    }
  }
}

spl_autoload_register('Autoload::load');

$logparse = new Controller;
