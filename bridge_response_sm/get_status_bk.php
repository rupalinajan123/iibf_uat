<?php
  require_once 'PHP_BridgePG/BridgePGUtil.php';
  $bconn = new BridgePGUtil();
  $bconn->set_mid(MERCHANT_ID);
  
  $tid = $csc_txn = '';
  if(isset($_GET['tid'])) { $tid = $_GET['tid']; }
  if(isset($_GET['csc_txn'])) { $csc_txn = $_GET['csc_txn']; }
  
  /* echo "<br>tid : ".$tid;
  echo "<br>csc_txn : ".$csc_txn;
  echo "<br><br>******************************************<br><br>"; */
  
  $response = $bconn->get_status($tid, $csc_txn);
  
  echo json_encode(array(
  "status"=>'success',
  "tid" => $tid,
  "csc_txn" => $csc_txn,
  "response" => $response
  ));
?>