"use strict";
emojione.ascii = true;

var connection = null;
var clientID = 0;

var WebSocket = WebSocket || MozWebSocket;

function setUsername() {
  var msg = {
    session: document.getElementById("session").value,
    date: Date.now(),
    id: clientID,
    type: "username"
  };
  connection.send(JSON.stringify(msg));
}

var old_text;

function connect() {
  var serverUrl = "ws://127.0.0.1:8080";

  connection = new WebSocket(serverUrl);

  connection.onopen = function(evt) {
    document.getElementById("chatbox-error").innerHTML = '';
    document.getElementById("text").disabled = false;
    document.getElementById("send").disabled = false;
  };
  
  connection.onclose = function(e) {
    document.getElementById("text").disabled = true;
    document.getElementById("send").disabled = true;
    
    console.log('Socket is closed. Reconnect will be attempted in 1 second.', e.reason);
    
    document.getElementById("chatbox-error").innerHTML = `<div class="error-msg" style="margin: 0; border-radius: 4px;"><i class="fa fa-times-circle"></i> Socket is closed. Reconnect will be attempted in 1 second, ${e.reason}</div>`;

    setTimeout(function() {
      connect();
    }, 1000);
  };

  connection.onmessage = function(evt) {
    var f = document.getElementById("chatbox");
    var text = "";
    var msg = JSON.parse(evt.data);
    var time = new Date(msg.date);
    var timeStr = time.toLocaleTimeString();

    f.scrollTop = f.scrollHeight;

    switch(msg.type) {
      case "id":
        clientID = msg.id;
        setUsername();
        break;

      case "message":
        text = `<li><b style="float: left;" onclick="document.getElementById('text').value = '/whisper ' + this.innerText + ' '; document.getElementById('text').focus()">${msg.name}</b>: <span style="float: right;">${timeStr}</span><p>${msg.text}</p></li>`;
        break;

      case "timeout": {
        document.getElementById("text").value = old_text;

        document.getElementById("chatbox-error").innerHTML = `<div class="error-msg" style="margin: 0; border-radius: 4px;"><i class="fa fa-times-circle"></i> ${msg.text}</div>`;

        setTimeout(function(e) { document.getElementById("chatbox-error").innerHTML = ''; }, msg.timeout * 1000); 
        break;
      }
    }

    if (text.length) {
      document.getElementById("chatbox").innerHTML += text;
      f.scrollTop = f.scrollHeight;
    }
  };
}

function send() {
  var msg = {
    text: document.getElementById("text").value,
    type: "message",
    id: clientID,
    date: Date.now()
  };
  connection.send(JSON.stringify(msg));
  old_text = document.getElementById("text").value;
  document.getElementById("text").value = "";
}

function handleKey(evt) {
  if (evt.keyCode === 13 || evt.keyCode === 14) {
    if (!document.getElementById("send").disabled) {
      send();
    }
  }
}