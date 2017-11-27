<?php

//sendMessageTestSlack("This application is still in testing stage. If you like it, consider a donation to the development team.");
sendMessageOwnSlack("TEST");
function sendMessageTestSlack($message){

  $domain   = 'https://hooks.slack.com/services/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';
  //$domain   = 'https://hooks.slack.com/services/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';
  //$channel  = '@slackbot';
  $bot_name = 'Webhook';
  $icon     = ':alien:';
  $channel  = '@slackbot';
  $data = array(
      'channel'     => $channel,
      'username'    => $bot_name,
      'text'        => $message,
      'icon_emoji'  => $icon
  );
  $data_string = json_encode($data);
  $ch = curl_init($domain);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Content-Length: ' . strlen($data_string))
  );

  $result = curl_exec($ch);
  if ($result === false) {
     // echo 'Curl error: ' . curl_error($ch);
  }
  curl_close($ch);
}

function sendMessageOwnSlack($message){
    $aRegistrationInfo = array("affiliation" => "test");
    $cancel = false;
    $channel  = '@slackbot';
    if (!$cancel){
      $number = 30;
      //$token    = '1qhun0vR9XU4rMUjfDMVadhC';
      //$token    = "xoxp-15384258612-46752013264-202824213776-d0f2d1199625bf278b3298e429e2d606";
    $domain   = 'https://hooks.slack.com/services/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';
    //https://hooks.slack.com/services/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
      //$domain   = 'https://hooks.slack.com/services/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';
      //$channel  = '@slackbot';
      $bot_name = 'Webhook';
      $icon     = ':alien:';
      $messageInit = "";
      
      if($aRegistrationInfo["affiliation"]!=""){
        $prestigious = array("famous", "renowned", "accredited", "influential", "notorious", "considered", "respected", "appreciated", "well-liked");
        $random = rand(0,count($prestigious)-1);
        $messageInit = "A new user from the ".$prestigious[$random]." ".$aRegistrationInfo["affiliation"]." has registered. ";
      }else $messageInit = "A new user has registered. ";
      $messages = array("#number registered users and going up! :metal:",
                        "We are already #number! :smiley:",
                      "SPLC is proud to announce that there are #number registered users :thumbsup:");

      $specialMessages = array(100=> "WOW! We got 100! :trophy:", 144 => "We are a dozen of dozens! Will be surprises at the party? :ring:");

      $endMessage = $messageInit;
      if (isset($specialMessages[$number])) $endMessage .= $specialMessages[$number];
      else {
          $random = rand(0,count($messages)-1);
          $endMessage .= str_replace("#number", $number, $messages[$random]);
      }
      $data = array(
          'channel'     => $channel,
          'username'    => $bot_name,
          'text'        => $endMessage,
          'icon_emoji'  => $icon
      );
      $data_string = json_encode($data);
      $ch = curl_init($domain);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($data_string))
      );

      $result = curl_exec($ch);
      if ($result === false) {
         // echo 'Curl error: ' . curl_error($ch);
      }
      curl_close($ch);
    }
    return $result;
}
function sendMessageTestSlack2($message){

   $token    = 'xoxp-15384258612-46752013264-202824213776-d0f2d1199625bf278b3298e429e2d606';
        $domain   = 'isa-group';
        $channel  = '#splc2017';
        $bot_name = 'Webhook';
        $icon     = ':alien:';
        $message  = 'Your message';
        $attachments = array([
            'fallback' => 'Lorem ipsum',
            'pretext'  => 'Lorem ipsum',
            'color'    => '#ff6600',
            'fields'   => array(
                [
                    'title' => 'Title',
                    'value' => 'Lorem ipsum',
                    'short' => true
                ],
                [
                    'title' => 'Notes',
                    'value' => 'Lorem ipsum',
                    'short' => true
                ]
            )
        ]);
        $data = array(
            'channel'     => $channel,
            'username'    => $bot_name,
            'text'        => $message,
            'icon_emoji'  => $icon,
            'attachments' => $attachments
        );
        $data_string = json_encode($data);
        $ch = curl_init('https://'.$domain.'.slack.com/services/hooks/incoming-webhook?token='.$token);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
            );
        //Execute CURL
        $result = curl_exec($ch);
        return $result;        
}

?>