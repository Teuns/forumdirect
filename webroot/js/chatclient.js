"use strict";
emojione.ascii = true;

var connection = null;
var clientID = 0;

var WebSocket = WebSocket || MozWebSocket;

function checkIn() {
  var msg = {
    session: document.getElementById("session").value,
    date: Date.now(),
    id: clientID,
    type: "checkin"
  };
  connection.send(JSON.stringify(msg));
}

var old_text;

function connect() {
  var serverUrl = "ws://192.168.2.40:8080";

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
    var f = document.getElementById("chatbox-main");
    var text = "";
    var msg = JSON.parse(evt.data);
    var time = new Date(msg.date);
    var timeStr = time.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    var username = document.getElementById("username");

    f.scrollTop = f.scrollHeight;

    switch(msg.type) {
      case "id":
        clientID = msg.id;
        checkIn();
        break;

      case "message": {
        text = `<li><b style="float: left;" onclick="document.getElementById('text').value = '/whisper ' + this.innerText + ' '; document.getElementById('text').focus()">${msg.name}</b>: <span style="float: right;">${timeStr}</span><p>${emojione.toImage(linkifyHtml(msg.text))}</p></li>`;
        
        if (text.length) {
          f.innerHTML += text;
          f.scrollTop = f.scrollHeight;
        }

        break;
      }

      case "private-message": {
        var chatbox = document.getElementById("chatbox-" + msg.name);
        var channel_link = document.getElementById("channel-" + msg.name);

        if (!chatbox) {
          location.reload(); 
        }


        if (!channel_link.innerText.includes('(+1)') && msg.name !== username.value) {
          channel_link.innerText = channel_link.innerText + " (+1)";
        }

        if (msg.name == username.value) {
          [].forEach.call(
            document.querySelectorAll('#channels > li .active'),
            function (el) {
              el.classList.remove('active');
            }
          );

          channel_link.classList.add("active");
          [].forEach.call(
            document.querySelectorAll('.chatbox ul:not(#chatbox-' + msg.name + ')'),
            function (el) {
                if (el.style.display == "block") {
                    el.style.display = "none";
                }
            }
          );

          chatbox.style.display = "block";

          document.getElementById('text').click();
        }

        text = `<li><b style="float: left;" onclick="document.getElementById('text').value = '/whisper ' + this.innerText + ' '; document.getElementById('text').focus()">${msg.name}</b>: <span style="float: right;">${timeStr}</span><p>${emojione.toImage(linkifyHtml(msg.text))}</p></li>`;
        
        if (text.length) {
          chatbox.innerHTML += text;
          chatbox.scrollTop = chatbox.scrollHeight;
        }

        break;
      }

      case "timeout": {
        document.getElementById("text").value = old_text;

        document.getElementById("chatbox-error").innerHTML = `<div class="error-msg" style="margin: 0; border-radius: 4px;"><i class="fa fa-times-circle"></i> ${msg.text}</div>`;

        setTimeout(function(e) { document.getElementById("chatbox-error").innerHTML = ''; }, msg.timeout * 1000); 
        break;
      }
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