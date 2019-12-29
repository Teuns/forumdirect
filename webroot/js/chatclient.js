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

function connect() {
  var serverUrl = "ws://127.0.0.1:8080";

  connection = new WebSocket(serverUrl);

  connection.onopen = function(evt) {
    document.getElementById("text").disabled = false;
    document.getElementById("send").disabled = false;
  };

  connection.onclose = function(evt) {
    document.getElementById("text").disabled = true;
    document.getElementById("send").disabled = true;
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
        text = "<li><b style='float: left;'>" + msg.name + "</b>: <span style='float: right;'>" + timeStr + "</span><p>" + msg.text + "</p></li>";
        break;
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
  document.getElementById("text").value = "";
}

function handleKey(evt) {
  if (evt.keyCode === 13 || evt.keyCode === 14) {
    if (!document.getElementById("send").disabled) {
      send();
    }
  }
}
