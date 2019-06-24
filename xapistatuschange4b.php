<!--  =============================================================
      Author: DJ Uittenbogaard -
      Details, install guide and release notes: https://github.com/DJF3
         version: 4.0 - v4 include people presence
      ============================================================= -->
      <?php
         $backgroundimage = "meetingroom.jpg";
         $textAvailable = "Available";
         $textInCall = "Call Active";
         $reloadInSeconds = 5;
         $version = "4b";
         $startx = 735;
         $starty = 110;
         $width = 250;
         $height = 328;
      ?>
<html>
<head>
   <title>Cisco Video Systems Call Status</title>
   <script src="./jquery.min.js"></script>
   <style>
      body {
         background-color: #000;
      }
      .roomname {
         position: absolute;
         left: <?php echo $startx ?>px;
         top: <?php echo $starty ?>px;
         width: <?php echo $width ?>px;
         z-index: 1000;
         padding: 5px;
         color: white;
         font-size: 30px;
         font-family: helvetica;
         text-align: center;
         display: grid;
         align-items: flex-end;
         height: 80px;
      }
      #statusbar {
         position: absolute;
         left: <?php echo ($startx - 14) ?>px;
         top: <?php echo ($starty + 90) ?>px;
         width: <?php echo ($width + 30) ?>px;
         height: <?php echo $height ?>px;
         padding: 5px;
         background-color: grey;
         font-size: 40px;
         color: #fff;
         text-align: center;
         font-family: helvetica;
         display: grid; /* was flex */
         vertical-align: bottom;
         justify-content: center;
         align-items: flex-end;
         padding-bottom: 20px;
      }
   </style>
</head>
<body>
   <?php
      error_reporting(E_ALL);
      $currenttime = date("H:i:s");
      if  ( isset($_GET["roomname"])) {  // ------- Check if meetingroom name was provided.
         $meetingroomname = $_GET["roomname"];
      } else {
          $meetingroomname = "Meeting Room";
      };
      $savetolog = "\n\n----------------------------------- START $meetingroomname------ $currenttime";
      // --------------- API CALL from Video System --------------------
      $myhttppost = file_get_contents("php://input");
      if ($myhttppost) {        // {"calls": "red", "presence": "No", "count": "22"}
         $arr_data = array( "calls" => "", "presence" => "", "count" => "0");
         $savetolog = $savetolog . "\n---#0 MYHTTPPOST >> $myhttppost";
         $json = str_replace("'",'"', json_decode($myhttppost, true));
         $meetingroomname = $json["roomname"];
         $myFile = "./statusfiles/status-$meetingroomname.json";

         // #1___READ STATUS FILE
         if (file_exists($myFile)) {
             $filedata = file_get_contents($myFile);
         } else {
             $filedata = '{"calls": "green","presence": "Unknown","count": "0"}';
         }
         $savetolog = $savetolog . "\n---#1 File content >> " . str_replace("\n", '', $filedata);

         // #2___CONVERT JSON to ARRAY + check if data is empty
         $arr_data = json_decode($filedata, true);
         if (!isset($arr_data["calls"])) { $arr_data["calls"] = "green"; $savetolog = $savetolog . " calls empty \n"; }
         if (!isset($arr_data["presence"])) { $arr_data["presence"] = "unknown";$savetolog = $savetolog . " presence empty \n"; }
         if (!isset($arr_data["count"])) { $arr_data["count"] = "-2"; $savetolog = $savetolog . " count empty \n"; }

         // #3___UPDATE ARRAY based in received data
         // #3a_____ CALL STATUS CHANGE
         if (isset($json["calls"])) {
            if (intval($json["calls"]) == 0 ) {
               $arr_data["calls"] = "green";
            } else if (intval($json["calls"]) > 0 ) {
               $arr_data["calls"] = "red";
            }
            $savetolog = $savetolog . "\n---#3 CALL COUNT: $callcount";
         }
         // #3b_____ PRESENCE CHANGE
         if (isset($json["presence"])) {
            $arr_data['presence'] = $json["presence"];
            $savetolog = $savetolog . "\n---#3 PRESENCE STATE: " . $arr_data['presence'];
         }
         // #3c_____ PEOPLE COUNT CHANGE
         if (isset($json["count"])) {
            $arr_data['count'] = $json["count"];
            $savetolog = $savetolog . "\n---#3 PEOPLE COUNT: " . $arr_data['count'];
         }
         // #4___CONVERT ARRAY TO JSON & WRITE BACK TO STATUS FILE
         $filedata = json_encode($arr_data, JSON_PRETTY_PRINT);
         if(file_put_contents($myFile, $filedata)) {
            $savetolog = $savetolog . "\n---#4 SUCCESS writing status to file:  $myFile";
         } else {
            $savetolog = $savetolog . "\n---#4 **ERROR** writing status to file:  $myFile";
         }
      }
      // --- LOG (basic)
      $mylogfile = fopen("xapistatuschange-log-$version.txt", "a");
      fwrite($mylogfile, $savetolog);
      fclose($mylogfile);
      echo "    <div class='roomname'>$meetingroomname</div>";
      echo "    <img src='$backgroundimage' />";
      echo "    <div id='statusbar'>Status Unknown</div>";
      ?>

<!-- Javascript: read status file every 5 seconds, update color statusbar -->
<script>
var mycalls = "";
var mycount = 0;
var newtext = "";
var reloadInSeconds = <?php echo $reloadInSeconds ?>;
var roomname = "<?php echo $meetingroomname ?>";
jQuery.ajaxSetup({async:false});
// ----- CALL STATUS
function getCallStatus(roomname) {
   newtext = "<span style='font-size:15px;'>status unavailable</span>";
   jQuery.get('./statusfiles/status-<?php echo $meetingroomname ?>.json?<?php echo time(); ?>', function(statuscolor) {
      // ----------- CALL STATUS
      mydata = JSON.parse(statuscolor);
      mycalls = mydata.calls;
      console.log(" ____  1a : COLOR from FILE read = " + mycalls);
      if (mycalls == "green") {      // change text to 'Available'
         newtext = "<?php echo $textAvailable ?>";
      } else if (mycalls == "red") { // change text to 'In Call'
         newtext = "<?php echo $textInCall ?>";
      } else {
         newtext = "Status unknown<br>";
         mycalls = "grey";
      };
      console.log(" ____  1b : COLOR from FILE text = " + mycalls);
      // ----------- COUNT
      mycount = parseInt(mydata.count);
      //console.log("***** PEOPLE COUNT now: " + mycount);
      if (mycount == -2) {
         mycount = "<br><span style='font-size:15px;color:#b4b2b2;'>people count not supported</span>";
      } else if (mycount == -1) {
         mycount = "<br><span style='font-size:15px;color:#D8D8D8;'>system standby</span>";
      } else if (mycount == 0) {
         mycount = "<br><span style='font-size:15px;color:#D8D8D8;'>No people detected</span>";
      } else if (mycount == 1) {
         mycount = "<br><span style='font-size:18px;color:#D8D8D8;'>" + mycount + " person detected</span>";
      } else if (mycount > 1) {
         mycount = "<br><span style='font-size:18px;color:#D8D8D8;'>" + mycount + " people detected</span>";
      }
      console.log("***** PEOPLE COUNT now: " + mycount + " -------- presence: " + mydata.presence);
      // ----------- PRESENCE
      if ((mydata.presence == "Yes") && ((parseInt(mydata.count) == -1) || (parseInt(mydata.count) == 0))) {
         mycount = "<br><span style='font-size:18px;color:#D8D8D8;'>People detected</span>";
      }
      console.log("____  3b   mycount = " + mycount);
   }, "text");
   return [mycalls, newtext, mycount];
}
// =========== MAIN CODE ====================================================
$(document).ready(function() { // Run when HTML page has been loaded
   function functionToLoadFile() {
      // #1: get Call status
      console.log("____  1  __ GET status ______________________________");
      callStatusData = getCallStatus(roomname);
      callColor = callStatusData[0];
      callText = callStatusData[1];
      newCount = callStatusData[2];
      // #2: set BG color
      //console.log("____  2  __ SET BG Color");
      document.getElementById('statusbar').style.background = callColor;
      //console.log("____  4  __ SET new text");
      document.getElementById("statusbar").innerHTML = callText + newCount;
      // #3: restart code in x seconds
      setTimeout(functionToLoadFile, reloadInSeconds * 1000);
   }
   setTimeout(functionToLoadFile, 10);
});
</script>
 </body>
</html>
