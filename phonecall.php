<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Audio Demo</title>

    <script type="text/javascript" src="/api/wsc-common.js"></script>
    <script type="text/javascript" src="/api/wsc-call.js"></script>
 
</head>
 
<body onload="onPageLoad()">
        <h2 id="heading">Welcome to SDP InfoDev Demo 1 -- Audio Call</h2>
        <hr>

        <!-- Button for login. This is a start point of this page.
        Method register is invoked when the button is clicked. -->
        <div id="controlsArea">

        </div>
 
        <br>
        <br>
        <table hidden="true" id="media">
            <tr>
                <td>You</td>
                <td>Remote</td>
            </tr>
            <!-- HTML5 audio element. -->
            <tr>
                <td width="15%"><audio id="selfAudio" autoplay></audio></td>
                <td width="15%"><audio id="remoteAudio" autoplay></audio></td>
            </tr>
        </table>
 
        <script type="text/javascript">
 
            /** This app checks media stream support in Chrome or Firefox 23+  */
            // In your application, please use appropriate API to verify media stream support.
            var attachMediaStream = null;
            if (navigator.mozGetUserMedia) {
                console.log("Attaching media stream");
                // Attach a media stream to an element.
                attachMediaStream = function(element, stream) {
                    console.log("Application using Mozilla browser");
                    element.mozSrcObject = stream;
                    element.play();
                };
            } else if (navigator.webkitGetUserMedia) {
                console.log("Application using Chrome browser");
                // Attach a media stream to an element.
                attachMediaStream = function(element, stream) {
                    element.src = webkitURL.createObjectURL(stream);
                };
            } else {
                // The browser does not support media streams
                reptBrowserIssue();
            }
 
            //*************security login****************
            var demoName = " Audio Call Demo ";
            var wscSession, callPackage, userName, caller, callee;
            wsc.setLogLevel(wsc.LOGLEVEL.DEBUG);
 
            // Save where the user came from.
            var savedUrl = window.location;
 
            // This application is deployed on WebRTC Session Controller.
            var wsUri = "ws://" + window.location.hostname  + ":" + window.location.port + "/ws/webrtc/sample";
 
            // login and logout URI to redirect the user.
            //var loginUri = "http://" + window.location.hostname + ":" + window.location.port + "/infodev/wscdemo.html";
            var logoutUri = "http://" + window.location.hostname + ":" + window.location.port + "/infodev/demos/wscdemo.html";

            // Configuring the audio and video settings in the CallConfig object.
            var audioMediaDirection = wsc.MEDIADIRECTION.SENDRECV;
            var videoMediaDirection = wsc.MEDIADIRECTION.NONE;
            var callConfig = new wsc.CallConfig(audioMediaDirection, videoMediaDirection);
            console.log("Created CallConfig with audio stream only.");
            console.log(" ");
 
            // The onPageLoad event handler.
            function onPageLoad() {
                console.log("Page has loaded. Setting up the Session.");
                setSessionUp();
            }

            // This function sets up and configures the WebSocket connection.
            function setSessionUp() {
                console.log("In setSessionUp().");
 
                // Create the session. Here, userName is null. 
                // wsc can determine userName using the cookie of the request.
                wscSession = new wsc.Session(null, wsUri, sessionSuccessHandler, sessionErrorHandler);
                // Register a wsc.AuthHandler with session.
                // It provides customized info of authentication, such as username/password.
                var authHandler = new wsc.AuthHandler(wscSession);
                authHandler.refresh = refreshAuth;
                // Configure the session.
                wscSession.setBusyPingInterval(2 *1000);
                wscSession.setIdlePingInterval(6 * 1000);
                wscSession.setReconnectTime(2 * 1000);
                wscSession.onSessionStateChange = sessionStateChangeHandler;
 
                console.log("Session configured with authhandler, intervals and sessionStateChange handler.");
                console.log(" ");
            }

            // The function called when a session is instantiated. 
            // The next steps are processed here.
            function sessionSuccessHandler() {
                console.log(" In sessionSuccesshandler.");

                // Create a CallPackage.
                callPackage = new wsc.CallPackage(wscSession);
                // Bind the event handler of incoming call.
                if(callPackage){
                    callPackage.onIncomingCall = onIncomingCall;
                }
                console.log(" Created CallPackage..");
                console.log (" ");
                // Get user Id.
                userName = wscSession.getUserName();
                console.log (" Our user is " + userName);
                console.log (" ");
            }
 
            // The function called when a session is not instantiated.
            function sessionErrorHandler(error) {
                console.log("onSessionError: error code=" + error.code + ", reason=" + error.reason);
                setControls("<h1>Session Failed, please logout and try again.</h1>");
            }

            // This is a sample function. It requests the number to call.
            // 'onclick'='functionName()' for each button triggers the next step for the code.

            function displayInitialControls() {
                console.log ("In displayControls().");
                var controls = "Enter Your Callee: <input type='text' name='callee' id='callee'/><br><hr>"
                + "<input type='button' name='callButton' id='btnCall'  value='Call' onclick='onCallSomeOne()'/>"
                + "<input type='button' name='cancelButton' id='btnCancel'  value='Cancel' onclick='' disabled ='true'/><br><br><hr>"
                + "<input type='button' name='logoutButton' id='Logout'  value='Logout' onclick='logout()'/>"
                + "<br><br><hr>";
                setControls(controls);
                var calleeInput = document.getElementById("callee");
 
                if (calleeInput) {
                    console.log (" Waiting for Callee Input.");
                    console.log (" ");
                    if( userName != calleeInput) {
                        calleeInput.focus();
                    }
 
                }
            }

            // This example does not use either TURN or SERVICE authentication. 
            // This function is provided as a reference for your use.
            function refreshAuth(authType, authHeaders) {
                var authInfo = null;

                if(authType==wsc.AUTHTYPE.SERVICE){
                //Return JSON object according to the content of the "authHeaders".
                // For the digest authentication implementation, refer to RFC2617.
                    authInfo = getSipAuth(authHeaders);
 
                } else if(authType==wsc.AUTHTYPE.TURN){
 
                    //Return JSON object in this format:
                    // {"iceServers" : [ {"url":"turn:test@<aHost>:<itsPort>", "credential":"nnnn"} ]}.
                    authInfo = getTurnAuth();
                }
                return authInfo;
            };
 
            // This function manages the session states.
            function sessionStateChangeHandler(sessionState) {
                console.log("sessionState : " + sessionState);
                switch (sessionState) {
                    case wsc.SESSIONSTATE.RECONNECTING:
                    setControls("<h1>Network is unstable, please wait...</h1>");
                    break;
                    case wsc.SESSIONSTATE.CONNECTED:
                    if (wscSession.getAllSubSessions().length == 0) {
                        displayInitialControls();
                    }
                    break;
                    case wsc.SESSIONSTATE.FAILED:
                    setControls("<h1>Session Failed, please logout and try again.</h1>");
                    break;
                }
            }
 
            // This function is the incoming call callback
            // wsc triggers this function when it receives the invite from the remote caller. 
            function onIncomingCall(callObj, callConfig) {
                // We need the user's response. In this example code, we do the following:
                // We draw two buttons for users to accept or decline the incoming call.
                // Attach onclick event handlers to these two buttons.
                console.log ("In onIncomingCall(). Drawing up Control buttons to accept or decline the call.");
                var controls = "<input type='button' name='acceptButton' id='btnAccept' value='Accept "
                + callObj.getCaller()
                + " Incoming Audio Call' onclick=''/><input type='button' name='declineButton' id='btnDecline'  value='Decline Incoming Audio Call' onclick=''/>"
                + "<br><br><hr>";
                setControls(controls);

                document.getElementById("btnAccept").onclick = function() {
                    // User accepted the call.                                  

                    //  Store the caller and callee names.
                    callee = userName;
                    caller = callObj.getCaller;
                    console.log (callee + " accepted the call from caller " + caller);
                    console.log (" ");

                    // Send the message back.
                    callObj.accept(callConfig);
                }
                document.getElementById("btnDecline").onclick = function() {
                    // User declined the call. Send a message back. 

                    // Get the caller name.
                    callee = userName;
                    caller = callObj.getCaller;
                    console.log (callee + " declined the call from caller, " + caller);
                    console.log (" ");

                    // Send the message back.
                    callObj.decline();
                }

                // User accepted the call. Bind the event handlers for the call and media stream.
                console.log ("Calling setEventHandlers from onIncomingCall() with remote call object ");
                setEventHandlers(callObj);
            }
 

            // This function binds the call and media state event handlers to the call object.
            // It is called by when user is the caller or the callee.
            function setEventHandlers(callobj) {
                console.log ("In setEventHandlers");
                console.log (" ");
                callobj.onCallStateChange = function(newState){
                    callStateChangeHandler(callobj, newState);
 
                };
                callobj.onMediaStreamEvent= mediaStreamEventHandler;
            }
 
            // This function is an event handler for changes of call state.
            function callStateChangeHandler(callObj, callState) {
                console.log (" In callStateChangeHandler().");
                console.log("callstate : " + JSON.stringify(callState));
                if (callState.state == wsc.CALLSTATE.ESTABLISHED) {
                    console.log (" Call is established. Calling callMonitor. ");
                    console.log (" ");
                    callMonitor(callObj);
                } else if (callState.state == wsc.CALLSTATE.ENDED) {
                    console.log (" Call ended. Displaying controls again.");
                    console.log (" ");
                    displayInitialControls();
                } else if (callState.state == wsc.CALLSTATE.FAILED) {
                    console.log (" Call failed. Displaying controls again.");
                    console.log (" ");
                    displayInitialControls();
                }
            }
 
            // This event handler is invoked when "Call" button is clicked.
            function onCallSomeOne() {
                console.log ("In onCallSomeOne()");
            
                // Need the caller callee name. Also storing caller.
                callee = document.getElementById("callee").value;
                caller = userName;
                console.log ("Name entered is " + callee);
    
                // Did the user enter a blank ?
                if (callee === "") {
                    setControls("<h1>Invalid entry. Please enter the number you wish to call.</h1>");
                    setTimeout(function(){ displayInitialControls();}, 2000)
                    return;
                } 

                // Same domain case. The caller may not have given the entire name.     
                if (callee.indexOf("@") < 0) {
                    console.log (" ");
                    callee = callee + "@example.com";
                    console.log("Complete caller ID is " + callee);
                }
                // Did the user enter his own number?
                if (callee == userName) {
                    setControls("<h1>You cannot call yourself. Please enter the number you wish to call.</h1>");
                    return;
                }
   

                console.log(" Caller, " + caller + ", wants to call " + callee + ", the Callee.");
                console.log (" ");
 
                console.log("Creating call object to call  " + callee);
 
                // To call someone, create a Call object first.
                var call = callPackage.createCall(callee, callConfig, doCallError);
                console.log ("Created the call.");
                console.log (" ");

                if (call != null) {
                    console.log ("Calling setEventHandlers from onCallSomeOne() with call data.");
                    console.log (" ");
                    setEventHandlers(call);
                    // Then start the call.
                    console.log ("In onCallSomeOne(). Starting Call. ");
                    call.start();
                    // Allow the user to cancel call before it is set up. End the call.
                    // Disable "Call" button and enable "Cancel" button.
                    var btnCall = document.getElementById("btnCall");
                    btnCall.disabled = true;
                    var btnCancel = document.getElementById("btnCancel");
                    btnCancel.disabled = false;
                    console.log ("Enabled " + caller + " to cancel call.");
                    btnCancel.onclick = function() {
                        console.log ("In onCallSomeOne().");
                        console.log (caller + " clicked the Cancel button. Ending call. ");
                        console.log (" ");
                        call.end();
                        console.log (" If user logs out, the user will be sent back to " + logoutUri);
 
                    }
                }
            }
 
            // This function monitors the call when call is established.
            function callMonitor(callObj) {
                console.log ("In callMonitor");
                console.log ("Monitoring the call. Setting up controls to Hang Up.");
                console.log (" ");

                // We need the user's response.
                //In this example code, we draw 2 buttons.
                // "Hang Up" button ends the call, but user stays on the application page.
                // "Logout" button ends the session, and user leaves the application.
                // Attach onclick event handler to each button.
                var controls = "<input type='button' name='hangup' id='btnHangup' value='Hang Up' onclick=' '/><br><br>"
                               + "<input type='button' name='logoutButton' id='Logout'  value='Logout' onclick='logout()'/>"
                               + "<br><br><hr>";
                setControls(controls);
                document.getElementById("btnHangup").onclick = function() {
                    console.log (" In callMonitor.");
                    // Who ended the call?
                    if (userName == caller) {
                        console.log ("Caller, " + caller + ", clicked the Hang Up button. Calling call.end now.");
                        console.log (" ");
                      } else {
                        console.log ("Callee, " + callee + ", clicked the Hang Up button. Calling call.end now.");
                        console.log (" ");
                      }
                   callObj.end();
               };

           }
 
           // This event handler is invoked when a  media stream event is fired.
           // Attach media stream to HTML5 audio element.
           function mediaStreamEventHandler(mediaState, stream) {
               console.log (" In mediaStreamEventHandler.");
               console.log("mediastate : " + mediaState);
               console.log (" ");
 
               if (mediaState == wsc.MEDIASTREAMEVENT.LOCAL_STREAM_ADDED) {
                   attachMediaStream(document.getElementById("selfAudio"), stream);
               } else if (mediaState == wsc.MEDIASTREAMEVENT.REMOTE_STREAM_ADDED) {
                   attachMediaStream(document.getElementById("remoteAudio"), stream);
               }
           }
 
           // This function displays the controls set by the application.
           function setControls(controls) {
               var controlsArea = document.getElementById("controlsArea");
               controlsArea.innerHTML = controls;
           }
 
           // This function is called when the call is not created.
           function doCallError(error) {
               alert('Call error reason:' + error.reason);
           }

           // The browser does not support media streams
           // Use this function to exit gracefully.
           function reptBrowserIssue() {
               console.log("In reptBrowserIssue");
               console.log("Browser does not appear to be WebRTC-capable");
               logout();
           }

           // This function logs the user out. 
           // For 3rd party authentication use login uri to send user back to where he came from.
           function logout() {
               console.log("In logout(). Closing session.");
               console.log (" ");
               if (wscSession) {
                   wscSession.close();
               }
               // Send the user back to where he came from.
               console.log (" In logout(). Sending user back to " + logoutUri);
               window.location.href = logoutUri;
           }
</script>
</body>
</html>