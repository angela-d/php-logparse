<?php
class logParseModel {

  // collect a list of logs we're able to work with
  public function log_locations($dir = './logs') {

    $target_dir = scandir($dir);
    $list       = array();

    // recursively navigate the log directory so dirs like /var/log/apache2 are also offered
    foreach($target_dir as $value)  {
      if($value === '.' || $value === '..') {continue;}
        if(is_file("$dir/$value")) {$list[]="$dir/$value";continue;}

          foreach($this->log_locations("$dir/$value") as $value) {
            $list[] = $value;
          }
    }

    return $list;
  }
}
