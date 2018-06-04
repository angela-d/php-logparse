<?php

// break up the data passed from the controller so it can be dynamically sorted
$data  = array();
$regex = '/^(\S+) (\S+) (\S+) \[([^:]+):(\d+:\d+:\d+) ([^\]]+)\] \"(\S+) (.*?) (\S+)\" (\S+) (\S+) "(.*?)" "(.*?)"/m';
preg_match_all($regex, $log, $data);

$total = substr_count($log, "\n");
echo $total . " Total Records\n-----------------------\n";

// malicious check
function malcheck($log) {

  echo "POTENTIAL RISK\n\e[31m";

  $suspicious  = array(
    'exec',
    'base64',
    'system',
    'shell_exec',
    'popen',
    'allow_url_fopen',
    'allow_url_include',
    '../',
    '\'',
    'union',
    'select',
    ' or',
    ' and ',
    '\;'
  );

  // break up the log into individual lines so each record can be parsed individually
  foreach(preg_split("/((\r?\n)|(\r\n?))/", $log) as $line){
    // compare array of suspicious terms to each result
    foreach ($suspicious as $value) {
      if (stristr($line,$value) !== false) {
        echo "Trigger: \e[33m" . $value . "\e[31m\n" . $line . "\n\n";
      }
    }
  }
  echo "\e[0m\n";
}

// format the data so it's readable
function log_amt($calc = array(), $header, $pct = 0) {
  $analyze     = array_count_values($calc);
  $calculation = array();

  echo "\n" . $header . "\n";

  // sort descending by value
  arsort($analyze);
  foreach($analyze as $key => $value) {

    // display response info for success & errors
    if(is_numeric($key) && $key >= 200 && $key <= 226) {
      $response = "\t<== success";
    } elseif(is_numeric($key) && $key >= 400 && $key <= 511) {
      // bold and color it red with ansi, so it stands out
      $response = "\t\e[1m\e[31m<== errors\e[0m";
    } else {
      $response = '';
    }

    // calculate percentages if the 3rd argument is set
    if (!$pct) {
      $percentage = '';
    } else {
      $percentage = " (" . ($value / 100) * $pct . "%)";
    }

    $calculation[$key] = $value;

    echo $value . $percentage . "\t" . $key . $response . "\n";
  }
}

// a separate check is run for potentially malicious requests; probably not the most efficient method - needs work
echo malcheck($log);

// the 3rd argument determines whether or not we want percentages calculated
echo log_amt($data[1], "HITS\tIP ADDRESSES") .
     log_amt($data[8], "HITS\tACCESSED") .
     log_amt($data[9], "HITS\tPROTOCOL") .
     log_amt($data[10], "HITS\tRESPONSE") .
     log_amt($data[12], "HITS\t\tREFERRER", $total) .
     log_amt($data[13], "HITS\t\tUSER AGENT", $total);

// release the heavy array
unset($data);
unset($total);
