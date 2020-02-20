//#!/usr/bin/env node

//
// WebSocket chat server
// Implemented using Node.js
//
// Requires the websocket module.
//
"use strict";

var url = require('url');
var fs = require('fs');
var mysql = require('mysql');
var PHPUnserialize = require('php-unserialize');
var env = process.env.NODE_ENV || 'development';
var config = require('./config')[env];
var request = require('sync-request');
var linkifyStr = require('linkifyjs/string');
var unserializer = require('php-session-unserialize');

var cfg = {
    ssl: config.ssl,
    ssl_key: 'private.key',
    ssl_cert: 'cert.crt'
};
var httpServ = (cfg.ssl) ? require('https') : require('http');

var db = mysql.createPool({
    host: config.database.host,
    user: config.database.user,
    password: config.database.password,
    database: config.database.db,
    charset: 'utf8mb4'
});

function reconnect() {
    db.connect(function(err) {
        //- Create a new one
        db = db.createPool(db_config);

        //- Try to reconnect
        db.getConnection(function(err) {
            if (err) {
                //- Try to connect every 2 seconds.
                setTimeout(reconnect(db), 2000);
            } else {
                console.log("\n\t *** New connection established with the database. ***")
                return db;
            }
        });
    });
}

db.on('error', function(err) {
    console.log('db error', err);
    if (err.code === 'PROTOCOL_CONNECTION_LOST') {
        reconnect();
    } else {
        reconnect();
    }
});

var WebSocketServer = require('websocket').server;

var connectionArray = [];
var nextID = Date.now();
var appendToMakeUnique = 1;

if (cfg.ssl) {
    var server = httpServ.createServer({
        key: fs.readFileSync(cfg.ssl_key),
        cert: fs.readFileSync(cfg.ssl_cert)
    }, function(request, response) {
        console.log((new Date()) + " Received request for " + request.url);
        response.writeHead(404);
        response.end();
    });
} else {
    var server = httpServ.createServer(function(request, response) {
        console.log((new Date()) + " Received request for " + request.url);
        response.writeHead(404);
        response.end();
    });
}

server.listen(process.env.PORT || config.server.port, function() {
    console.log((new Date()) + " Server is listening on port " + config.server.port);
});

// Create the WebSocket server
var wsServer = new WebSocketServer({
    httpServer: server,
    autoAcceptConnections: true
});

function getConnectionForID(id) {
    var connect = null;
    var i;

    for (i = 0; i < connectionArray.length; i++) {
        if (connectionArray[i].clientID === id) {
            connect = connectionArray[i];
            break;
        }
    }

    return connect;
}

function makeUserListMessage() {
    var userListMsg = {
        type: "userlist",
        users: []
    };

    var i;

    // Add the users to the list
    for (i = 0; i < connectionArray.length; i++) {
        userListMsg.users.push(connectionArray[i].username);
    }

    return userListMsg;
}

function sendUserListToAll() {
    var userListMsg = makeUserListMessage();
    var userListMsgStr = JSON.stringify(userListMsg);
    var i;

    for (i = 0; i < connectionArray.length; i++) {
        connectionArray[i].sendUTF(userListMsgStr);
    }
}

String.prototype.replaceArray = function(find, replace) {
    var replaceString = this;
    var regex;
    for (var i = 0; i < find.length; i++) {
        regex = new RegExp(find[i], "g");
        replaceString = replaceString.replace(regex, replace[i]);
    }
    return replaceString;
};

function getUserTags(msg, str) {
    var qualityRegex = /\@([^ ]\w*)/g,
        matches,
        qualities = [],
        replace = [];

    while (matches = qualityRegex.exec(str)) {
        var res = request('GET', config.url + '/api/getUserNameCorrectly/' + matches[1]);
        if (res.statusCode == 200) {
            qualities.push('@' + matches[1]);
            replace.push("@<a href='/users/" + res.getBody('utf8') + "'>" + res.getBody('utf8') + "</a>");
        }
    }

    return str.replaceArray(qualities, replace);
}

async function getWhispers(str, self) {
    str = JSON.parse(str);

    if (str.text.includes("/whisper")) {
        var username = str.text.split('/whisper')[1].split(' ')[1];
        var message = str.text.split('/whisper ' + username)[1];

        await new Promise((resolve, reject) => {
          db.query('SELECT * from users where username = ?', username, function(err, result) {
            if(result.length) {
              if (message && message.trim().length) {
                str.type = "private-message";
                if (self) {
                  str.text = "<b>Whisper to " + result[0]['username'] + ": </b>" + message;
                } else {
                  str.text = "<b>Whisper: </b>" + message;
                }

                return resolve(JSON.stringify(str));
              }
            }
          });
      });
    }

    return Promise.resolve(JSON.stringify(str));
}

function escapeString(str) {
  str = JSON.parse(str);

  str.text = escapeHtml(str.text);

  return JSON.stringify(str);
}

var users = {};
var userlist = [];

wsServer.on('connect', function(connection) {
    console.log((new Date()) + " Connection accepted.");
    connectionArray.push(connection);

    // Send the new client its token; it will
    // respond with its login username.
    connection.clientID = nextID;
    nextID++;

    var msg = {
        type: "id",
        id: connection.clientID
    };
    connection.sendUTF(JSON.stringify(msg));

    // Handle the "message" event received over WebSocket. This
    // is a message sent by a client, and may be text to share with
    // other users or a command to the server.
    connection.on('message', function(message) {
        if (message.type === 'utf8') {
            console.log("Received Message: " + message.utf8Data);

            // Process messages

            var sendToClients = true;
            msg = JSON.parse(message.utf8Data);
            var connect = getConnectionForID(msg.id);
            var startDate = new Date();

            if (msg.id && !users[connect.clientID]) {

                // Process user
                // And return the information of the user with it
                db.query('SELECT data FROM sessions WHERE id = ?', msg.session, function(err, result) {
                    if (result.length) {
                        var session = unserializer(String(result[0]['data']));

                        users[connect.clientID] = session.Auth.User;

                        userlist.push({'user': session.Auth.User, 'clientID': connect.clientID});

                        if (users[connect.clientID] === undefined) {
                            connection.close();
                        }
                    } else {
                        connection.close();
                    }
                })
            }

            switch (msg.type) {
                case "message":
                    msg.name = users[msg.id].username;
                    msg.uid = users[msg.id].id;
                    msg.text = msg.text;
                    break;
            }

            // Convert the message back to JSON and send it out
            // to all clients.
            if (sendToClients) {
                var msgString = JSON.stringify(msg);
                var i;

                if (msg.text != undefined && msg.text && msg.text.trim().length) {
                    db.query('SELECT *, chats.created AS created FROM chats JOIN users ON users.id = chats.user_id WHERE username = ? ORDER BY chats.created DESC', msg.name, function(err, result) {
                        if (!result.length || result.length && Math.floor(startDate.getTime() - new Date(result[0]['created']).getTime()) / (1000) >= 3) {
                            if (msg.text.includes('/whisper')) {
                                var username = msg.text.split('/whisper')[1].split(' ')[1];
                                db.query('SELECT * from users where username = ?', username, function(err, result) {
                                    if (result.length) {
                                        msg.text = msg.text.replace(username, result[0]['username']);
                                        db.query('INSERT INTO chats (user_id, from_user_id, to_user_id, body) VALUES (?, ?, ?, ?)', [msg.uid, msg.uid, result[0]['id'], msg.text], function(err, result) {
                                            if (err)
                                                console.log(err);

                                            sendPrivateChat(escapeString(msgString));
                                        })
                                    }
                                })
                            } else {
                              db.query('INSERT INTO chats (user_id, body) VALUES (?, ?)', [msg.uid, msg.text], function(err, result) {
                                  if (err)
                                      console.log(err);

                                  sendChat(escapeString(msgString));
                              });
                            }
                        } else {
                          var timeout = 3 - Math.round(Math.floor(startDate.getTime() - new Date(result[0]['created']).getTime()) / (1000));

                          var message = {
                            type: "timeout",
                            timeout: timeout,
                            text: `Please wait ${timeout} seconds`
                          };
                          
                          var connect = getConnectionForID(JSON.parse(msgString).id);
                          connection.sendUTF(JSON.stringify(message));
                        }
                    });
                }
            }
        }
    });

    function sendChat(msgString) {
        var i;
        for (i = 0; i < connectionArray.length; i++) {
            connectionArray[i].sendUTF(msgString);
        }
    }

    async function sendPrivateChat(msgString) {
      var i;
      
      var username = JSON.parse(msgString).text.split('/whisper')[1].split(' ')[1];

      for (i = 0; i < userlist.length; i++) {
        if (userlist[i].user.username === username && userlist[i].clientID != JSON.parse(msgString).id) {
          var message = await getWhispers(msgString, false);
          var connect = getConnectionForID(userlist[i].clientID);
          connect.sendUTF(message);
          break;
        }
      }

      var message = await getWhispers(msgString, true);
      var connect = getConnectionForID(JSON.parse(msgString).id);
      connect.sendUTF(message);
    }

    // Handle the WebSocket "close" event; this means a user has logged off
    // or has been disconnected.
    connection.on('close', function(e) {
        var connect = getConnectionForID(connection.clientID);
        
        var index = connectionArray.indexOf(connect);

        if (index > -1) {
          connectionArray.splice(index, 1);
          userlist.splice(index, 1);
        }

        console.log((new Date()) + " Peer " + connection.remoteAddress + " disconnected.");
    });
});


function escapeHtml(text) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };

  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}