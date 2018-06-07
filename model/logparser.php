<?php
  class Model {

    /**
     * collect a list of logs we're able to work with..
     * this is pretty crude; since we're only able to (nicely) format access.log at present, this function will be
     * rewritten to be more fluid, in future versions
     *
     * you can select any log you'd like, but only access.log will have all of it's slots formatted appropriately :)
     */

    public function log_locations($dir = Config::LOGPATH) {

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
