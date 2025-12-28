<?php
  /* The function to send sms Start */

  function send_message ( $post_body, $url, $username, $password) {
    $ch = curl_init( );
    $headers = array(
      'Content-Type:application/json',
      'Authorization:Basic '. base64_encode("$username:$password")
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, 1 );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_body );
    // Allow cUrl functions 20 seconds to execute
    curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
    // Wait 10 seconds while trying to connect
    curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
    $output = array();
    $output['server_response'] = curl_exec( $ch );
    $curl_info = curl_getinfo( $ch );
    $output['http_status'] = $curl_info[ 'http_code' ];
    $output['error'] = curl_error($ch);
    curl_close( $ch );
    return $output;
  } 

  /* The function to send sms Start */

  $fullnumber = '+250788305541';
  $bodySms = 'Hello PASCAL Your Labo Exams Result  Completed,  you should go to see Doctor, Thank you!

  From HOREBU Medical Clinic, REMERA Branch.
  ';
  
  $username = 'horebu2021';
  $password = 'Horebu@2021!';
  $messages = array(
      'from'=>'HOREBU',
      'to'=>$fullnumber,
      'body'=> $bodySms
  );

  $result = send_message( json_encode($messages), 'https://api.bulksms.com/v1/messages?auto-unicode=true&longMessageMaxParts=30', $username, $password );

  if ($result['http_status'] != 201) {
      print "Error sending: " . ($result['error'] ? $result['error'] : "HTTP status ".$result['http_status']."; Response was " .$result['server_response']);
  } else {
      //print "Response " . $result['server_response'];
      // Use json_decode($result['server_response']) to work with the response further
      //save in table 
    $saveSmsHistory = $connexion->prepare('INSERT INTO sms_sent(numero,id_consu,phone) VALUES(:numero,:id_consu,:phone)');
    $saveSmsHistory->execute(array('numero'=>$ligneL->numero,'id_consu'=>$ligneL->id_consuLabo,'phone'=>$fullnumber));
  }
?>