<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <title>Real Time Chat</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.7.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.7.1/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.7.1/firebase-firestore.js"></script>
    <script src="https://unpkg.com/vue/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vuefire/2.0.0-alpha.20/vuefire.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"
        type="text/css">
    <link href="./style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <?php
    
    echo "test"

    ?>

    <style>
        .chat
{
    list-style: none;
    margin: 0;
    padding: 0;
}

.chat li
{
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px dotted #B3A9A9;
}

.chat li.left .chat-body
{
    margin-left: 60px;
}

.chat li.right .chat-body
{
    margin-right: 60px;
}

.sender-header {
    display: block;
    overflow: hidden;
}

.sender-msg {
    text-align: right;
}

.chat li .chat-body p
{
    margin: 0;
    color: #777777;
}

.panel .slidedown .glyphicon, .chat .glyphicon
{
    margin-right: 5px;
}

.panel-body
{
    overflow-y: scroll;
    height: 250px;
}

::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    background-color: #F5F5F5;
}

::-webkit-scrollbar
{
    width: 12px;
    background-color: #F5F5F5;
}

::-webkit-scrollbar-thumb
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #555;
}
    </style>

    <div id="app">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="glyphicon glyphicon-comment"></span> Chat
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle"
                                    data-toggle="dropdown">
                                    <span class="glyphicon glyphicon-chevron-down"></span>
                                </button>
                                <ul class="dropdown-menu slidedown">
                                    <li><a href="#"><span class="glyphicon glyphicon-refresh"></span>Refresh</a></li>
                                    <li><a href="#"><span class="glyphicon glyphicon-ok-sign"></span>Available</a></li>
                                    <li><a href="#"><span class="glyphicon glyphicon-remove"></span>Busy</a></li>
                                    <li><a href="#"><span class="glyphicon glyphicon-time"></span>Away</a></li>
                                    <li class="divider"></li>
                                    <li><a href="" @click="logOut()"><span class="glyphicon glyphicon-off"></span>Sign
                                            Out</a></li>
                                </ul>
                            </div>
                        </div>

                        <button v-if="!isLogin" @click="googleLogin">Login with Google</button>
                        <messagecomponent v-else :userid="user.uid" :username="user.name" :userphoto="user.photo">
                        </messagecomponent>
                        <div class="panel-footer">
                            <form onsubmit="return sendMessage();">
                            <input id="message" placeholder="Enter message" autocomplete="off">
                            
                            <input type="submit">
                            </form>
                            <script>
                                function sendMessage() {
                                    // get message
                                    var message = document.getElementById("message").value;

                                    // save in database
                                    firebase.database().ref("messages").push().set({
                                        "sender": myName,
                                        "message": message
                                    });

                                    // prevent form from submitting
                                    return false;
                                }

                                // listen for incoming messages
                                firebase.database().ref("messages").on("child_added", function (snapshot) {
                                    var html = "";
                                    // give each message a unique ID
                                    html += "<li id='message-" + snapshot.key + "'>";
                                    // show delete button if message is sent by me
                                    if (snapshot.val().sender == myName) {
                                        html += "<button data-id='" + snapshot.key + "' onclick='deleteMessage(this);'>";
                                        html += "Delete";
                                        html += "</button>";
                                    }
                                    html += snapshot.val().sender + ": " + snapshot.val().message;
                                    html += "</li>";

                                    document.getElementById("messages").innerHTML += html;
                                });
                                function deleteMessage(self) {
                                    // get message ID
                                    var messageId = self.getAttribute("data-id");

                                    // delete message
                                    firebase.database().ref("messages").child(messageId).remove();
                                }

                                // attach listener for delete message
                                firebase.database().ref("messages").on("child_removed", function (snapshot) {
                                    // remove message node
                                    document.getElementById("message-" + snapshot.key).innerHTML = "This message has been removed";
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--<script src="./chatbot.js"></script>-->
    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-app.js"></script>
    
    <!-- include firebase database -->
    <script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-database.js"></script>
    <script>
        // Your web app's Firebase configuration
        var firebaseConfig = {
            apiKey: "AIzaSyBExhGEGFVitugDVDoNYW2c4qeDyVQdqJQ",
            authDomain: "ahire-636cc.firebaseapp.com",
            databaseURL: "https://ahire-636cc-default-rtdb.firebaseio.com",
            projectId: "ahire-636cc",
            storageBucket: "ahire-636cc.appspot.com",
            messagingSenderId: "454483553062",
            appId: "1:454483553062:web:ccd511b854b9535adf2fe7"
        };
        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        var myName = prompt("Enter your name");
    </script>
    
    <!-- create a form to send message -->
    <form onsubmit="return sendMessage();">
        <input id="message" placeholder="Enter message" autocomplete="off">
    
        <input type="submit">
    </form>
    <script>
        function sendMessage() {
            // get message
            var message = document.getElementById("message").value;

            // save in database
            firebase.database().ref("messages").push().set({
                "sender": myName,
                "message": message
            });

            // prevent form from submitting
            return false;
        }

        // listen for incoming messages
        firebase.database().ref("messages").on("child_added", function (snapshot) {
            var html = "";
            // give each message a unique ID
            html += "<li id='message-" + snapshot.key + "'>";
            // show delete button if message is sent by me
            if (snapshot.val().sender == myName) {
                html += "<button data-id='" + snapshot.key + "' onclick='deleteMessage(this);'>";
                html += "Delete";
                html += "</button>";
            }
            html += snapshot.val().sender + ": " + snapshot.val().message;
            html += "</li>";

            document.getElementById("messages").innerHTML += html;
        });
        function deleteMessage(self) {
            // get message ID
            var messageId = self.getAttribute("data-id");

            // delete message
            firebase.database().ref("messages").child(messageId).remove();
        }

        // attach listener for delete message
        firebase.database().ref("messages").on("child_removed", function (snapshot) {
            // remove message node
            document.getElementById("message-" + snapshot.key).innerHTML = "This message has been removed";
        });
    </script>
</body>

</html>