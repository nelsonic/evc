/*
var http = require('http');
http.createServer(function (req, res) {
    res.writeHead(200, {'Content-Type': 'text/plain'});
    res.end('Hello World from NodeJS ... >>\n');
}).listen(8080);
console.log('Server running at http://46.137.53.149:8080/');
*/

var node_modules = '/usr/local/lib/node_modules/';
var express = require(node_modules+'express');
var app = express.createServer(),
    C   = require('./core');

app.use(express.bodyParser());
app.get('/', function(req, res){
  res.send('hello world ' +req.header('host'));
//  res.send(req.body.data);
//  console.log('Req >> ' +JSON.stringify(req, null, '\t') );
//  var C = req;
//  console.log(req);
});

app.listen(8080);
console.log('Server running at http://46.137.53.149:8080/');



