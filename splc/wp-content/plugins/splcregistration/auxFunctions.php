<?php

function sendMessageSlack($idRegistration){
    $aRegistrationInfo = get_registration_info($idRegistration);
    $email = $aRegistrationInfo["email"];
    $aAll = get_all_registrations(false, false, false);
    $aTotalRegistered = 0;
    $aUsersRepeated = array();
    $cancel = false;
    foreach($aAll as $user){
      if(strtolower($email) == strtolower($user["email"]) && $idRegistration!=$user["id"]) $cancel = true; 
      if ($user["paymentdone"]==1){
        $aTotalRegistered++;
        $aUsersRepeated[strtolower($user["email"])] = 1;
      }
    }
    foreach($aAll as $user){
      if ($user["paymentdone"]==0){
        if (!isset($aUsersRepeated[strtolower($user["email"])])){
          $aUsersRepeated[strtolower($user["email"])] = 1;     
          $aTotalRegistered++;
        }
      }
    }
    if (!$cancel){
      $number = $aTotalRegistered;
      $domain   = 'https://hooks.slack.com/services/tererte/tralalala/tuturururur';
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

      $specialMessages  = array(100=> "WOW!!!! We reached 100! :trophy:", 144 => "We are a dozen of dozens! Will be surprises at the party? :ring:");
      $specialEmails    = array("fafaf@us.es" => "Made in Costa del Sol and evolved in London, Canada and Vienna.", 
                                "dfadfa@us.es" => "Tostadas completas lover and Dos Hermanas resident.", 
                                "dfadfa@us.es" => "Carlinhos lover, rosaleña and half-trianera.",
                                "dfadfa@us.es" => "<https://www.youtube.com/watch?v=xemgC81-5Uo|The boss> has come!");
      $endMessage = $messageInit;
      if (isset($specialMessages[$number])) $endMessage .= $specialMessages[$number];
      else if (isset($specialEmails[strtolower($aRegistrationInfo["email"])])) $endMessage .= $specialEmails[strtolower($aRegistrationInfo["email"])];
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

function getCheckCodeByEmail($email){
  $code   = hash("md5",$email."splc1");   
  $result = check_code_email($email, $code);
  $sHtml = "The email ".$email." ";
  if ($result == NULL){
    $sHtml .= "has not code registered.";
  }else if ($result == 1){
    $sHtml .= "has the code ".$code." registered and not used";
  }else{
    $sHtml .= "has the code ".$code." registered and used with idOrder=".$result["idorder"];
  }
  return $sHtml;
}

function generateCodeByEmail($email){
    $code   = hash("md5",$email."splc1");   
    $result = check_code_email($email, $code);
    if ($result == NULL){
        insert_code($email,$code);
        $sHmtl = "The email ".$email." has been registered with code=".$code;
    }else if ($result["used"]==0){
        $sHmtl = "The email ".$email." was already registered with code=".$code." (was not used)";
    }else if ($result["used"]){
        $sHmtl = "The email ".$email." was already registered with code=".$code." (and has been used)";
    }
}

function getCheckCodeByCode($code){
  $result = check_code($code);
  
  $sHtml = "INVALID";
  if ($result == 1){
    $sHtml .= "VALID";
  }
  return $sHtml;
}

function processActionUsers(){
    if (isset($_REQUEST["action"])){
      $action = $_REQUEST["action"];
      switch($action){
        case "discarduser":
          trash_user($_REQUEST["idOrder"]);
          break;
        case "redouser":
          untrash_user($_REQUEST["idOrder"]);
          break;
        case "confirmuser":
          confirm_registration($_REQUEST["idOrder"]);
          //sendMessageSlack($_REQUEST["idOrder"]);
          emailAdminRegistration($_REQUEST["idOrder"]);
          emailUserRegistration($_REQUEST["idOrder"]);
          emailPaymentSupervisorRegistration($_REQUEST["idOrder"]);
          break;
        case "unconfirmuser":
          unconfirm_registration($_REQUEST["idOrder"]);
          break;
        case "discardbatch":
          //print_r($_REQUEST);
          break;
        
      }
    }
}

function downloadExcel(){
  //use PhpSpreadsheet\PhpSpreadsheet\Helper\Sample;
  //use PhpSpreadsheet\PhpSpreadsheet\IOFactory;
  //use PhpOffice\PhpSpreadsheet\Spreadsheet;
  //use PhpOffice\PhpSpreadsheet\Spreadsheet;
  require_once dirname(__FILE__) . '/PhpExcel/Classes/PHPExcel.php';
  require_once dirname(__FILE__) . '/PhpExcel/Classes/PHPExcel/Writer/Excel2007.php';
  
  //require_once 'PhpSpreadsheet/Bootstrap.php';
  
  $spreadsheet = new PHPExcel();
  // Set document properties
  $spreadsheet->getProperties()->setCreator('SPLC2017')
          ->setLastModifiedBy('SPLC2017')
          ->setTitle('SPLC 2017 Registration Info')
          ->setSubject('SPLC 2017 Registration Info')
          ->setDescription('List of registered users in SPLC 2017')
          ->setKeywords('splc conference')
          ->setCategory('SPLC');
  // Add some data
  $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1',"REGISTRO")
            ->setCellValue('B1',"SITUACION")
            ->setCellValue('C1',"APELLIDO - NOMBRE")
            ->setCellValue('D1',"BADGE NAME")
            ->setCellValue('E1',"EMAIL")
            ->setCellValue('F1',"FECHA DE SOLICITUD")
            ->setCellValue('G1',"TIPO INSCRIPCION")
            ->setCellValue('H1',"IMPORTE INSCRIPCION")
            ->setCellValue('I1',"EASY CHAIR ID")
            ->setCellValue('J1',"EXTRA DINNER")
            ->setCellValue('K1',"IMPORTE EXTRA DINNER");
  // Rename worksheet
  $aUsers = array();
  $aUsers = exportUsers();
  //print_r($aUsers);
  $initRow = 2;
  foreach($aUsers as $aUser){
    $spreadsheet->getActiveSheet()
    ->fromArray(
        $aUser,  // The data to set
        NULL,        // Array values with this value will not be set
        'A'.$initRow         // Top left coordinate of the worksheet range where
                     //    we want to set these values (default is A1)
    );
    $initRow++;
  }
  $spreadsheet->getActiveSheet()->setTitle('Registration');
  // Set active sheet index to the first sheet, so Excel opens this as the first sheet
  $spreadsheet->setActiveSheetIndex(0);
  $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
  $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(18);
  $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
  $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(25);
  $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
  $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
  $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(17);
  $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(5);
  $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(5);
  $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(3);
  $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(4);


  // Redirect output to a client’s web browser (Xlsx)
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="01simple.xlsx"');
  header('Cache-Control: max-age=0');
  // If you're serving to IE 9, then the following may be needed
  header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
  header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
  header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
  header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
  header ('Pragma: public'); // HTTP/1.0
  $objWriter = PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel2007');
  $fileNameTmp = str_replace('.php', '-1.xlsx', __FILE__);
  $objWriter->save($fileNameTmp);
  readfile($fileNameTmp);
  exit;
}

function downloadCSV(){
  header('Content-Description: File Transfer');
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment; filename="listadosplc.csv"');
  // Send Headers: Prevent Caching of File
  header('Cache-Control: private', false);
  header('Pragma: private');
  header("Expires: 0");
  $aUsers = exportUsers();
  $result = getCSVString($aUsers);
  
  header('Content-Length: ' . strlen($result)); 
  //header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  //
  echo $result;
  exit;
}

function exportUsers(){
  $aAll = get_all_registrations(false, false, false);
  $aAux = array();
  foreach($aAll as $user){
    $aCurrent = array();
    $aCurrent["REGISTRO"] = $user["id"];

    if ($user["paymentmethod"]=="methodtransfer"){
      $status = "PTE. TRANSFERENCIA";
    }else{
      if ($user["paymentdone"]=="1"){
        $status = "OK. CONFIRMADO";
      }else{
        $status = "CANCELADO";
      }
    }
    $aCurrent["SITUACION"] = $status;
    $aCurrent["APELLIDO - NOMBRE"] = $user["lastname"].", ".$user["firstname"];
    $aCurrent["BADGE NAME"] = $user["profile"];
    $aCurrent["EMAIL"] = $user["email"];
    $aCurrent["FECHA DE SOLICITUD"] = substr($user["time"],0,10);
    switch ($user["regoption"]){
      case 1: $registrationtype = "ONLY MONDAY"; break;
      case 2: $registrationtype = "ONLY TUESDAY"; break;
      case 3: $registrationtype = "BOTH DAYS"; break;
      case 4: $registrationtype = "MAIN CONFERENCE"; break;
      case 5: $registrationtype = "ALL INCLUDED"; break;
    }
    $aCurrent["TIPO INSCRIPCION"]     = $registrationtype;
    $aCurrent["IMPORTE INSCRIPCION"]   = $user["totalamount"];
    $aCurrent["EASYCHAIR ID"]         = $user["easychairid"];
    $aCurrent["EXTRA DINNER"]         = $user["extradinner"];
    $aCurrent["IMPORTE EXTRA DINNER"] = $user["extradinner"]*60;
    $aAux[] = $aCurrent;
  }
  return $aAux;
}

function getCSVString($aUsers){
 $sCsv = "REGISTRO;SITUACION;APELLIDO - NOMBRE;BADGE NAME;EMAIL;FECHA DE SOLICITUD; TIPO INSCRIPCION; IMPORTE INSCRIPCION;EASY CHAIR ID; EXTRA DINNER;IMPORTE EXTRA DINNER\n";
  foreach($aUsers as $aUser){
    $sCsv .= implode($aUser,";")."\n";
  }
  return $sCsv;
}

//DEPRECATED
function getCSV(){
 $aAll = get_all_registrations(false, false, false);
  $aAux = array();
  foreach($aAll as $user){
    $aCurrent = array();
    $aCurrent["REGISTRO"] = $user["id"];

    if ($user["paymentmethod"]=="methodtransfer"){
      $status = "PTE. TRANSFERENCIA";
    }else{
      if ($user["paymentdone"]=="1"){
        $status = "OK. CONFIRMADO";
      }else{
        $status = "CANCELADO";
      }
    }
    $aCurrent["SITUACION"] = $status;
    $aCurrent["APELLIDO - NOMBRE"] = $user["lastname"].", ".$user["firstname"];
    $aCurrent["BADGE NAME"] = $user["profile"];
    $aCurrent["EMAIL"] = $user["email"];
    $aCurrent["FECHA DE SOLICITUD"] = substr($user["time"],0,10);
    switch ($user["regoption"]){
      case 1: $registrationtype = "ONLY MONDAY"; break;
      case 2: $registrationtype = "ONLY TUESDAY"; break;
      case 3: $registrationtype = "BOTH DAYS"; break;
      case 4: $registrationtype = "MAIN CONFERENCE"; break;
      case 5: $registrationtype = "ALL INCLUDED"; break;
    }
    $aCurrent["TIPO INSCRIPCION"]     = $registrationtype;
    $aCurrent["IMPORTE INSCRIPCION"]   = $user["totalamount"];
    $aCurrent["EASYCHAIR ID"]         = $user["easychairid"];
    $aCurrent["EXTRA DINNER"]         = $user["extradinner"];
    $aCurrent["IMPORTE EXTRA DINNER"] = $user["extradinner"]*60;
    $aAux[] = $aCurrent;
  }   
  $sCsv = "REGISTRO;SITUACION;APELLIDO - NOMBRE;BADGE NAME;EMAIL;FECHA DE SOLICITUD; TIPO INSCRIPCION; IMPORTE INSCRIPCION;EASY CHAIR ID; EXTRA DINNER;IMPORTE EXTRA DINNER\n";
  foreach($aAux as $aUser){
    $sCsv .= implode($aUser,";")."\n";
  }
  return $sCsv;
}




function textRegistration($idReg){
  $sText = "";
  switch ($idReg){
    case "1":
      $sText = "Monday 25th September";
      break;
    case "2":
      $sText = "Tuesday 26th September";
      break;
    case "3":
      $sText = "Monday & Tuesday 25nd & 26rd September";
      break;
    case "4":
      $sText = "Main Conference (27th to 29th September)";
      break;
    case "5":
      $sText = "Full Registration (25nd to 29th September)";
      break;
  }
  return $sText;
}

function countryHtmlCombo($selectName){
  $sHtml = '';
  $sHtml .= '<select name="'.$selectName.'" id="'.$selectName.'">';
  $sHtml .= '<option value="AF">Afghanistan</option>
  <option value="AX">Aland Islands</option>
  <option value="AL">Albania</option>
  <option value="DZ">Algeria</option>
  <option value="AS">American Samoa</option>
  <option value="AD">Andorra</option>
  <option value="AO">Angola</option>
  <option value="AI">Anguilla</option>
  <option value="AQ">Antarctica</option>
  <option value="AG">Antigua and Barbuda</option>
  <option value="AR">Argentina</option>
  <option value="AM">Armenia</option>
  <option value="AW">Aruba</option>
  <option value="AU">Australia</option>
  <option value="AT">Austria</option>
  <option value="AZ">Azerbaijan</option>
  <option value="BS">Bahamas</option>
  <option value="BH">Bahrain</option>
  <option value="BD">Bangladesh</option>
  <option value="BB">Barbados</option>
  <option value="BY">Belarus</option>
  <option value="BE">Belgium</option>
  <option value="BZ">Belize</option>
  <option value="BJ">Benin</option>
  <option value="BM">Bermuda</option>
  <option value="BT">Bhutan</option>
  <option value="BO">Bolivia, Plurinational State of</option>
  <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
  <option value="BA">Bosnia and Herzegovina</option>
  <option value="BW">Botswana</option>
  <option value="BV">Bouvet Island</option>
  <option value="BR">Brazil</option>
  <option value="IO">British Indian Ocean Territory</option>
  <option value="BN">Brunei Darussalam</option>
  <option value="BG">Bulgaria</option>
  <option value="BF">Burkina Faso</option>
  <option value="BI">Burundi</option>
  <option value="KH">Cambodia</option>
  <option value="CM">Cameroon</option>
  <option value="CA">Canada</option>
  <option value="CV">Cape Verde</option>
  <option value="KY">Cayman Islands</option>
  <option value="CF">Central African Republic</option>
  <option value="TD">Chad</option>
  <option value="CL">Chile</option>
  <option value="CN">China</option>
  <option value="CX">Christmas Island</option>
  <option value="CC">Cocos (Keeling) Islands</option>
  <option value="CO">Colombia</option>
  <option value="KM">Comoros</option>
  <option value="CG">Congo</option>
  <option value="CD">Congo, the Democratic Republic of the</option>
  <option value="CK">Cook Islands</option>
  <option value="CR">Costa Rica</option>
  <option value="CI">Côte d\'Ivoire</option>
  <option value="HR">Croatia</option>
  <option value="CU">Cuba</option>
  <option value="CW">Curaçao</option>
  <option value="CY">Cyprus</option>
  <option value="CZ">Czech Republic</option>
  <option value="DK">Denmark</option>
  <option value="DJ">Djibouti</option>
  <option value="DM">Dominica</option>
  <option value="DO">Dominican Republic</option>
  <option value="EC">Ecuador</option>
  <option value="EG">Egypt</option>
  <option value="SV">El Salvador</option>
  <option value="GQ">Equatorial Guinea</option>
  <option value="ER">Eritrea</option>
  <option value="EE">Estonia</option>
  <option value="ET">Ethiopia</option>
  <option value="FK">Falkland Islands (Malvinas)</option>
  <option value="FO">Faroe Islands</option>
  <option value="FJ">Fiji</option>
  <option value="FI">Finland</option>
  <option value="FR">France</option>
  <option value="GF">French Guiana</option>
  <option value="PF">French Polynesia</option>
  <option value="TF">French Southern Territories</option>
  <option value="GA">Gabon</option>
  <option value="GM">Gambia</option>
  <option value="GE">Georgia</option>
  <option value="DE">Germany</option>
  <option value="GH">Ghana</option>
  <option value="GI">Gibraltar</option>
  <option value="GR">Greece</option>
  <option value="GL">Greenland</option>
  <option value="GD">Grenada</option>
  <option value="GP">Guadeloupe</option>
  <option value="GU">Guam</option>
  <option value="GT">Guatemala</option>
  <option value="GG">Guernsey</option>
  <option value="GN">Guinea</option>
  <option value="GW">Guinea-Bissau</option>
  <option value="GY">Guyana</option>
  <option value="HT">Haiti</option>
  <option value="HM">Heard Island and McDonald Islands</option>
  <option value="VA">Holy See (Vatican City State)</option>
  <option value="HN">Honduras</option>
  <option value="HK">Hong Kong</option>
  <option value="HU">Hungary</option>
  <option value="IS">Iceland</option>
  <option value="IN">India</option>
  <option value="ID">Indonesia</option>
  <option value="IR">Iran, Islamic Republic of</option>
  <option value="IQ">Iraq</option>
  <option value="IE">Ireland</option>
  <option value="IM">Isle of Man</option>
  <option value="IL">Israel</option>
  <option value="IT">Italy</option>
  <option value="JM">Jamaica</option>
  <option value="JP">Japan</option>
  <option value="JE">Jersey</option>
  <option value="JO">Jordan</option>
  <option value="KZ">Kazakhstan</option>
  <option value="KE">Kenya</option>
  <option value="KI">Kiribati</option>
  <option value="KP">Korea, Democratic People\'s Republic of</option>
  <option value="KR">Korea, Republic of</option>
  <option value="KW">Kuwait</option>
  <option value="KG">Kyrgyzstan</option>
  <option value="LA">Lao People\'s Democratic Republic</option>
  <option value="LV">Latvia</option>
  <option value="LB">Lebanon</option>
  <option value="LS">Lesotho</option>
  <option value="LR">Liberia</option>
  <option value="LY">Libya</option>
  <option value="LI">Liechtenstein</option>
  <option value="LT">Lithuania</option>
  <option value="LU">Luxembourg</option>
  <option value="MO">Macao</option>
  <option value="MK">Macedonia, the former Yugoslav Republic of</option>
  <option value="MG">Madagascar</option>
  <option value="MW">Malawi</option>
  <option value="MY">Malaysia</option>
  <option value="MV">Maldives</option>
  <option value="ML">Mali</option>
  <option value="MT">Malta</option>
  <option value="MH">Marshall Islands</option>
  <option value="MQ">Martinique</option>
  <option value="MR">Mauritania</option>
  <option value="MU">Mauritius</option>
  <option value="YT">Mayotte</option>
  <option value="MX">Mexico</option>
  <option value="FM">Micronesia, Federated States of</option>
  <option value="MD">Moldova, Republic of</option>
  <option value="MC">Monaco</option>
  <option value="MN">Mongolia</option>
  <option value="ME">Montenegro</option>
  <option value="MS">Montserrat</option>
  <option value="MA">Morocco</option>
  <option value="MZ">Mozambique</option>
  <option value="MM">Myanmar</option>
  <option value="NA">Namibia</option>
  <option value="NR">Nauru</option>
  <option value="NP">Nepal</option>
  <option value="NL">Netherlands</option>
  <option value="NC">New Caledonia</option>
  <option value="NZ">New Zealand</option>
  <option value="NI">Nicaragua</option>
  <option value="NE">Niger</option>
  <option value="NG">Nigeria</option>
  <option value="NU">Niue</option>
  <option value="NF">Norfolk Island</option>
  <option value="MP">Northern Mariana Islands</option>
  <option value="NO">Norway</option>
  <option value="OM">Oman</option>
  <option value="PK">Pakistan</option>
  <option value="PW">Palau</option>
  <option value="PS">Palestinian Territory, Occupied</option>
  <option value="PA">Panama</option>
  <option value="PG">Papua New Guinea</option>
  <option value="PY">Paraguay</option>
  <option value="PE">Peru</option>
  <option value="PH">Philippines</option>
  <option value="PN">Pitcairn</option>
  <option value="PL">Poland</option>
  <option value="PT">Portugal</option>
  <option value="PR">Puerto Rico</option>
  <option value="QA">Qatar</option>
  <option value="RE">Réunion</option>
  <option value="RO">Romania</option>
  <option value="RU">Russian Federation</option>
  <option value="RW">Rwanda</option>
  <option value="BL">Saint Barthélemy</option>
  <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
  <option value="KN">Saint Kitts and Nevis</option>
  <option value="LC">Saint Lucia</option>
  <option value="MF">Saint Martin (French part)</option>
  <option value="PM">Saint Pierre and Miquelon</option>
  <option value="VC">Saint Vincent and the Grenadines</option>
  <option value="WS">Samoa</option>
  <option value="SM">San Marino</option>
  <option value="ST">Sao Tome and Principe</option>
  <option value="SA">Saudi Arabia</option>
  <option value="SN">Senegal</option>
  <option value="RS">Serbia</option>
  <option value="SC">Seychelles</option>
  <option value="SL">Sierra Leone</option>
  <option value="SG">Singapore</option>
  <option value="SX">Sint Maarten (Dutch part)</option>
  <option value="SK">Slovakia</option>
  <option value="SI">Slovenia</option>
  <option value="SB">Solomon Islands</option>
  <option value="SO">Somalia</option>
  <option value="ZA">South Africa</option>
  <option value="GS">South Georgia and the South Sandwich Islands</option>
  <option value="SS">South Sudan</option>
  <option value="ES">Spain</option>
  <option value="LK">Sri Lanka</option>
  <option value="SD">Sudan</option>
  <option value="SR">Suriname</option>
  <option value="SJ">Svalbard and Jan Mayen</option>
  <option value="SZ">Swaziland</option>
  <option value="SE">Sweden</option>
  <option value="CH">Switzerland</option>
  <option value="SY">Syrian Arab Republic</option>
  <option value="TW">Taiwan, Province of China</option>
  <option value="TJ">Tajikistan</option>
  <option value="TZ">Tanzania, United Republic of</option>
  <option value="TH">Thailand</option>
  <option value="TL">Timor-Leste</option>
  <option value="TG">Togo</option>
  <option value="TK">Tokelau</option>
  <option value="TO">Tonga</option>
  <option value="TT">Trinidad and Tobago</option>
  <option value="TN">Tunisia</option>
  <option value="TR">Turkey</option>
  <option value="TM">Turkmenistan</option>
  <option value="TC">Turks and Caicos Islands</option>
  <option value="TV">Tuvalu</option>
  <option value="UG">Uganda</option>
  <option value="UA">Ukraine</option>
  <option value="AE">United Arab Emirates</option>
  <option value="GB">United Kingdom</option>
  <option value="US">United States</option>
  <option value="UM">United States Minor Outlying Islands</option>
  <option value="UY">Uruguay</option>
  <option value="UZ">Uzbekistan</option>
  <option value="VU">Vanuatu</option>
  <option value="VE">Venezuela, Bolivarian Republic of</option>
  <option value="VN">Viet Nam</option>
  <option value="VG">Virgin Islands, British</option>
  <option value="VI">Virgin Islands, U.S.</option>
  <option value="WF">Wallis and Futuna</option>
  <option value="EH">Western Sahara</option>
  <option value="YE">Yemen</option>
  <option value="ZM">Zambia</option>
  <option value="ZW">Zimbabwe</option>
</select>';
return $sHtml;
}  

function resultCountry($code){
  $countries = array
  (
    'AF' => 'Afghanistan',
    'AX' => 'Aland Islands',
    'AL' => 'Albania',
    'DZ' => 'Algeria',
    'AS' => 'American Samoa',
    'AD' => 'Andorra',
    'AO' => 'Angola',
    'AI' => 'Anguilla',
    'AQ' => 'Antarctica',
    'AG' => 'Antigua And Barbuda',
    'AR' => 'Argentina',
    'AM' => 'Armenia',
    'AW' => 'Aruba',
    'AU' => 'Australia',
    'AT' => 'Austria',
    'AZ' => 'Azerbaijan',
    'BS' => 'Bahamas',
    'BH' => 'Bahrain',
    'BD' => 'Bangladesh',
    'BB' => 'Barbados',
    'BY' => 'Belarus',
    'BE' => 'Belgium',
    'BZ' => 'Belize',
    'BJ' => 'Benin',
    'BM' => 'Bermuda',
    'BT' => 'Bhutan',
    'BO' => 'Bolivia',
    'BA' => 'Bosnia And Herzegovina',
    'BW' => 'Botswana',
    'BV' => 'Bouvet Island',
    'BR' => 'Brazil',
    'IO' => 'British Indian Ocean Territory',
    'BN' => 'Brunei Darussalam',
    'BG' => 'Bulgaria',
    'BF' => 'Burkina Faso',
    'BI' => 'Burundi',
    'KH' => 'Cambodia',
    'CM' => 'Cameroon',
    'CA' => 'Canada',
    'CV' => 'Cape Verde',
    'KY' => 'Cayman Islands',
    'CF' => 'Central African Republic',
    'TD' => 'Chad',
    'CL' => 'Chile',
    'CN' => 'China',
    'CX' => 'Christmas Island',
    'CC' => 'Cocos (Keeling) Islands',
    'CO' => 'Colombia',
    'KM' => 'Comoros',
    'CG' => 'Congo',
    'CD' => 'Congo, Democratic Republic',
    'CK' => 'Cook Islands',
    'CR' => 'Costa Rica',
    'CI' => 'Cote D\'Ivoire',
    'HR' => 'Croatia',
    'CU' => 'Cuba',
    'CY' => 'Cyprus',
    'CZ' => 'Czech Republic',
    'DK' => 'Denmark',
    'DJ' => 'Djibouti',
    'DM' => 'Dominica',
    'DO' => 'Dominican Republic',
    'EC' => 'Ecuador',
    'EG' => 'Egypt',
    'SV' => 'El Salvador',
    'GQ' => 'Equatorial Guinea',
    'ER' => 'Eritrea',
    'EE' => 'Estonia',
    'ET' => 'Ethiopia',
    'FK' => 'Falkland Islands (Malvinas)',
    'FO' => 'Faroe Islands',
    'FJ' => 'Fiji',
    'FI' => 'Finland',
    'FR' => 'France',
    'GF' => 'French Guiana',
    'PF' => 'French Polynesia',
    'TF' => 'French Southern Territories',
    'GA' => 'Gabon',
    'GM' => 'Gambia',
    'GE' => 'Georgia',
    'DE' => 'Germany',
    'GH' => 'Ghana',
    'GI' => 'Gibraltar',
    'GR' => 'Greece',
    'GL' => 'Greenland',
    'GD' => 'Grenada',
    'GP' => 'Guadeloupe',
    'GU' => 'Guam',
    'GT' => 'Guatemala',
    'GG' => 'Guernsey',
    'GN' => 'Guinea',
    'GW' => 'Guinea-Bissau',
    'GY' => 'Guyana',
    'HT' => 'Haiti',
    'HM' => 'Heard Island & Mcdonald Islands',
    'VA' => 'Holy See (Vatican City State)',
    'HN' => 'Honduras',
    'HK' => 'Hong Kong',
    'HU' => 'Hungary',
    'IS' => 'Iceland',
    'IN' => 'India',
    'ID' => 'Indonesia',
    'IR' => 'Iran, Islamic Republic Of',
    'IQ' => 'Iraq',
    'IE' => 'Ireland',
    'IM' => 'Isle Of Man',
    'IL' => 'Israel',
    'IT' => 'Italy',
    'JM' => 'Jamaica',
    'JP' => 'Japan',
    'JE' => 'Jersey',
    'JO' => 'Jordan',
    'KZ' => 'Kazakhstan',
    'KE' => 'Kenya',
    'KI' => 'Kiribati',
    'KR' => 'Korea',
    'KW' => 'Kuwait',
    'KG' => 'Kyrgyzstan',
    'LA' => 'Lao People\'s Democratic Republic',
    'LV' => 'Latvia',
    'LB' => 'Lebanon',
    'LS' => 'Lesotho',
    'LR' => 'Liberia',
    'LY' => 'Libyan Arab Jamahiriya',
    'LI' => 'Liechtenstein',
    'LT' => 'Lithuania',
    'LU' => 'Luxembourg',
    'MO' => 'Macao',
    'MK' => 'Macedonia',
    'MG' => 'Madagascar',
    'MW' => 'Malawi',
    'MY' => 'Malaysia',
    'MV' => 'Maldives',
    'ML' => 'Mali',
    'MT' => 'Malta',
    'MH' => 'Marshall Islands',
    'MQ' => 'Martinique',
    'MR' => 'Mauritania',
    'MU' => 'Mauritius',
    'YT' => 'Mayotte',
    'MX' => 'Mexico',
    'FM' => 'Micronesia, Federated States Of',
    'MD' => 'Moldova',
    'MC' => 'Monaco',
    'MN' => 'Mongolia',
    'ME' => 'Montenegro',
    'MS' => 'Montserrat',
    'MA' => 'Morocco',
    'MZ' => 'Mozambique',
    'MM' => 'Myanmar',
    'NA' => 'Namibia',
    'NR' => 'Nauru',
    'NP' => 'Nepal',
    'NL' => 'Netherlands',
    'AN' => 'Netherlands Antilles',
    'NC' => 'New Caledonia',
    'NZ' => 'New Zealand',
    'NI' => 'Nicaragua',
    'NE' => 'Niger',
    'NG' => 'Nigeria',
    'NU' => 'Niue',
    'NF' => 'Norfolk Island',
    'MP' => 'Northern Mariana Islands',
    'NO' => 'Norway',
    'OM' => 'Oman',
    'PK' => 'Pakistan',
    'PW' => 'Palau',
    'PS' => 'Palestinian Territory, Occupied',
    'PA' => 'Panama',
    'PG' => 'Papua New Guinea',
    'PY' => 'Paraguay',
    'PE' => 'Peru',
    'PH' => 'Philippines',
    'PN' => 'Pitcairn',
    'PL' => 'Poland',
    'PT' => 'Portugal',
    'PR' => 'Puerto Rico',
    'QA' => 'Qatar',
    'RE' => 'Reunion',
    'RO' => 'Romania',
    'RU' => 'Russian Federation',
    'RW' => 'Rwanda',
    'BL' => 'Saint Barthelemy',
    'SH' => 'Saint Helena',
    'KN' => 'Saint Kitts And Nevis',
    'LC' => 'Saint Lucia',
    'MF' => 'Saint Martin',
    'PM' => 'Saint Pierre And Miquelon',
    'VC' => 'Saint Vincent And Grenadines',
    'WS' => 'Samoa',
    'SM' => 'San Marino',
    'ST' => 'Sao Tome And Principe',
    'SA' => 'Saudi Arabia',
    'SN' => 'Senegal',
    'RS' => 'Serbia',
    'SC' => 'Seychelles',
    'SL' => 'Sierra Leone',
    'SG' => 'Singapore',
    'SK' => 'Slovakia',
    'SI' => 'Slovenia',
    'SB' => 'Solomon Islands',
    'SO' => 'Somalia',
    'ZA' => 'South Africa',
    'GS' => 'South Georgia And Sandwich Isl.',
    'ES' => 'Spain',
    'LK' => 'Sri Lanka',
    'SD' => 'Sudan',
    'SR' => 'Suriname',
    'SJ' => 'Svalbard And Jan Mayen',
    'SZ' => 'Swaziland',
    'SE' => 'Sweden',
    'CH' => 'Switzerland',
    'SY' => 'Syrian Arab Republic',
    'TW' => 'Taiwan',
    'TJ' => 'Tajikistan',
    'TZ' => 'Tanzania',
    'TH' => 'Thailand',
    'TL' => 'Timor-Leste',
    'TG' => 'Togo',
    'TK' => 'Tokelau',
    'TO' => 'Tonga',
    'TT' => 'Trinidad And Tobago',
    'TN' => 'Tunisia',
    'TR' => 'Turkey',
    'TM' => 'Turkmenistan',
    'TC' => 'Turks And Caicos Islands',
    'TV' => 'Tuvalu',
    'UG' => 'Uganda',
    'UA' => 'Ukraine',
    'AE' => 'United Arab Emirates',
    'GB' => 'United Kingdom',
    'US' => 'United States',
    'UM' => 'United States Outlying Islands',
    'UY' => 'Uruguay',
    'UZ' => 'Uzbekistan',
    'VU' => 'Vanuatu',
    'VE' => 'Venezuela',
    'VN' => 'Viet Nam',
    'VG' => 'Virgin Islands, British',
    'VI' => 'Virgin Islands, U.S.',
    'WF' => 'Wallis And Futuna',
    'EH' => 'Western Sahara',
    'YE' => 'Yemen',
    'ZM' => 'Zambia',
    'ZW' => 'Zimbabwe',
  );
  return $countries[$code];
}   
          
?>
