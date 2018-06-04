<?php
include_once('model/logparse.php');

class logParseController {

  public $model;

	public function __construct() {
		$this->model = new logParseModel();
	}

  public function process($argv) {

    // do we already know which log we want to look at?  check for arguments
    if(!isset($argv[1])) {

      echo "Please select a log to analyze.  ie.:\n\tphp logparse 1\n\tphp logparse list\nto obtain a list of logs to view.\n";

    } elseif($argv[1] == 'list') {

      $helper = "--------------------------------------------\n" .
      "For the log you wish to view, run a command similar to:\n" .
      "\tphp logparse 6\n ..to view the log with #6 as it's key.\n" .
      "--------------------------------------------\n\n";

      echo $helper;

      // output a list of logs to choose from
      echo "\n\tKEY:\tLOG FILE\n";
      foreach($this->model->log_locations() as $key => $log) {
        echo "\t" . $key . "\t" . $log . "\n";
      }

      echo "\n\n" . $helper;

    } elseif (is_numeric($argv[1])){

      // notify the model which log we want to look at & send to the view
      $log = $this->selection($argv[1]);
      require('view/logparse.php');
    }
  }

  public function selection($key){

    // parse which key was called from the cli
    foreach($this->model->log_locations() as $log_key => $log) {

      // only open the requested log
      if ($log_key == $key) {

        $log_to_open = fopen($log, "rb");
        $detail      = fread($log_to_open, filesize($log));
        fclose($log_to_open);

        // pass log output to the view via process() controller function
        return $detail;
      }
    }
  }
}
