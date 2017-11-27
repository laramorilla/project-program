<?php
#require('XLSXReader.php');


function processPreDay($data) {
	$i = 0;
	echo "<h2>".escape($data[0][1]) ."</h2>";// we print the date
	foreach($data as $row) {
    if($i!=0){ // We avoid the first line. then, we go thought each line and print 1) the hours [0] 2) the title. Also, if title contains "(" list the sessions. Also, check [2]
      echo "<div class=\"slot\">";
      echo "<div class=\"time_details\">" .escape($row[0]). '</div> ' ; //imprime hora + espacio
		  echo "<div class=\"sessions\">";
      if (strpos($row[1], '(') !== FALSE){//contiene charlas
        $beginning = substr( $row[1], strrpos( $row[1], '(')  + 1);
        $data= substr( $beginning, 0, strpos( $beginning, ')')) ;
        $res=process_pre_talks($data);
        if($res==''){
          echo "<div class=\"talk_pre\">&nbsp</div>";
        }else{
          echo $res;
        }
      }else{
        echo "<div class=\"session\">";
        echo "<div class=\"session_name\">".$row[1]. '</div>';
        echo '</div>';
      }
      if (strpos($row[2], '(') !== FALSE){//contiene charlas
        $beginning = substr( $row[2], strrpos( $row[2], '(')  + 1);
        $data= substr( $beginning, 0, strpos( $beginning, ')')) ;
        $res=process_pre_talks($data);
        if($res==''){
          echo "<div class=\"talk_pre\"></div>";
        }else{
          echo $res;
        }
      }
      if (strpos($row[3], '(') !== FALSE){//contiene charlas
        $beginning = substr( $row[3], strrpos( $row[3], '(')  + 1);
        $data= substr( $beginning, 0, strpos( $beginning, ')')) ;
        $res=  process_pre_talks($data);
        if($res==''){
          echo "<div class=\"talk_pre\"></div>";
        }else{
          echo $res;
        }

      }

      echo '</div>';
      echo '</div>';
    }
    $i++;
  }
}

function process_pre_talks($data){
  $xlsx2 = new XLSXReader('./wp-content/plugins/program-manager/programme-pre-v8.xlsx');
  $talks = $xlsx2->getSheet("Talks")->getData();
  $res='';
  foreach($talks as $talk) {
//    echo $talk[1];
    if($talk[1]==$data && $talk[5]=="true"){
  //			echo "<div class=\"talk_title\">". $talk[6]."</div>\r\n"."<div> - </div>\r\n"."<div class=\"talk_authors\">".$talk[7]."</div>\r\n";
      if($talk[2] !=""){
        $res= "<div class=\"talk_pre\">"."<b>".$talk[0]."</b>".': '. $talk[2];
      }else{
				$res= $res ."<div class=\"talk_pre\">"."<b>"."<a href=\"$talk[6]\" target=\"new\">". $talk[0]."</a></b>";
			}
      if($talk[3]!=""){
        $res= $res . " - "."<div class=\"talk_authors\">". $talk[3]."</div>" ;
      }
      $res= $res .  '</div>';
    }
  }
  return $res;
}

function processDay($data) {
	$i = 0;
//	echo '<table>';
	echo "<h2>".escape($data[0][1]) ."</h2>";// we print the date

	foreach($data as $row) {
		if($i!=0){ // We avoid the first line. then, we go thought each line and print 1) the hours [0] 2) the title. Also, if title contains "(" list the sessions. Also, check [2]
      echo "<div class=\"slot\">";
      echo "<div class=\"time_details\">" .escape($row[0]). '</div> ' ; //imprime hora + espacio
			echo "<div class=\"sessions\">";

			if (strpos($row[1], '(') !== FALSE){//contiene charlas
        echo "<div class=\"session\">";

        // imprimimos el nombre
				$name = substr( $row[1], 0, strrpos( $row[1], '(') );
				echo "<div class=\"session_name\">". $name . '</div>' ;

				//procesamos las charlas
				$beginning = substr( $row[1], strrpos( $row[1], '(')  + 1);
				$data= substr( $beginning, 0, strpos( $beginning, ')')) ;
				echo listTalks($data);
        echo '</div>';
			}else{// no contiene charlas
        echo "<div class=\"session\">";
				echo "<div class=\"session_name\">".$row[1]. '</div>';
        echo '</div>';
			}

			if (strpos($row[2], '(') !== FALSE){//contiene charlas
				// imprimimos el nombre
        echo "<div class=\"session\">";

				$name = substr( $row[2], 0, strrpos( $row[2], '(') );
				echo "<div class=\"session_name\">". $name . '</div>' ;

				//procesamos las charlas
				$beginning = substr( $row[2], strrpos( $row[2], '(')  + 1);
				$data= substr( $beginning, 0, strpos( $beginning, ')')) ;
				echo listTalks($data);
        echo '</div>';

			}

		    echo ' </div> </div> ';//sessions;
		}

		$i++;
	}
}

function listTalks($string){
	$xlsx2 = new XLSXReader('./wp-content/plugins/program-manager/programme-main-v9.xlsx');
	$talks = $xlsx2->getSheet("Talks")->getData();
	foreach($talks as $talk) {
		//echo $talk[4];
		if($talk[4]==$string){
//			echo "<div class=\"talk_title\">". $talk[6]."</div>\r\n"."<div> - </div>\r\n"."<div class=\"talk_authors\">".$talk[7]."</div>\r\n";
      echo
			"<div class=\"talk\">".
				"<div class=\"talk_name\">". $talk[6].". </div>".
				"<div class=\"talk_authors\">". $talk[7]."</div>".
				"<div class=\"talk_data\">";
				if($talk[8]!=""){
					echo "<div>Timing: ".$talk[8]." min</div>";
				}
			echo
				"</div>".
			"</div>";

		}
	}
}

function escape($string) {
	return htmlspecialchars($string, ENT_QUOTES);
}
