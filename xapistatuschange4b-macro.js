// DJ Uittenbogaard - duittenb@cisco.com
// v0.4b  Info: https://github.com/DJF3/Cisco-Video-System-Status-Monitor/ 
// --> update 2 parameters below!
// myRoomName = the name of the room as it appears on the video status page
// myUrl      = URL to the location where you host the status script
var myRoomName = "yourmeetingroomname";
var myUrl = "https://www.yourserver.com/path/xapistatuschange4b.php";
//
//
// No changes below (unless you know what you're doing ;-)
const xapi = require('xapi');
var payload;
var videosystemname = "";

function postStatusCall(type, roomname, amount) {
  payload = '{"roomname" : "' + roomname + '", "' + type + '" : "' + amount +'", "systemname" : "' + videosystemname +'"}';
   xapi.command('HttpClient Post', {
     Header: "Content-Type: application/json",
     AllowInsecureHTTPS: "True",
     Url: myUrl
   },
     payload)
   .then((result) => {
     console.log("     -- postStatusCall OK " + result.StatusCode + " JSON:" + payload);
   })
   .catch((err) => {
       console.log("     -- postStatusCall failed: " + err.message);
   });
}
function goprintname(txt) {
  videosystemname = txt;
}


// CALL status
xapi.status.on('SystemUnit State NumberOfInProgressCalls', (numberofcalls) => postStatusCall("calls", myRoomName, numberofcalls));
// PEOPLE count status
xapi.status.on('RoomAnalytics PeopleCount Current', (numberofpeople)  => postStatusCall("count", myRoomName, numberofpeople));
// PEOPLE presence
xapi.status.on('RoomAnalytics PeoplePresence', (presencestatus)  => postStatusCall("presence", myRoomName, presencestatus));

function init(){
  xapi.status.get('UserInterface ContactInfo Name').then((value) => {
    goprintname(value);
  });
}
init();
