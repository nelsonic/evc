var start  = new Date().getTime();
var node_modules = '/usr/local/lib/node_modules/';
var express = require(node_modules+'express');
var app = express.createServer();

/*** config.js ***/
var C = {}; // C is config object we are going to put in config.js
	C.DEFAULT_VOUCHER_CODE_LENGTH = 10;
	C.DEFAULT_SECURITY_CODE_LENGTH = 3;
	C.DEFAULT_NUMBER_OF_CODES_TO_CREATE = 10;
    C.CHARACTERS_FOR_VOUCHER_CODES = 'A,B,C,D,E,F,G,H,J,K,L,M,N,P,Q,R,T,U,V,W,X,Y,Z,2,3,4,5,6,7,8,9,2,3,4,5,6,7,8,9';
    C.numcodes = 20;
    C.format = 'GG********' ; // characters 

app.use(express.bodyParser());
app.get('/codes', function(req, res){
	
	if(req.query["num"] !== 'null') { C.numcodes = req.query["num"]; }
	if(req.query["format"] !== 'null') { C.format = req.query["format"]; }
	C.prefix = C.format.split(/\*/)[0] || ''; // 'GG';
	console.log('Prefix : ' +C.prefix);
	C.numrandomchars = C.format.split(/\*/g).length - 1;
	
	console.log("There are " + C.numrandomchars + " random chars");
	var rand = C.randomCharacter();
	var codesarray = [];
	while(codesarray.length < C.numcodes) {
		var code = C.generateCode(10);
		codesarray.push(code);
	}
	
	
	var	resp =' Here are your codes: <br />' +codesarray.join('<br />') + '<br /> numcodes = '+C.numcodes;
	var taken = new Date().getTime() - start;
	resp += '<hr /> <br /> ' +req.header('host') +' - time taken : '+taken;	
	resp += '<style type="text/css"> body { font-family:"Courier New" ;} </style>';
	res.send(resp);
	
});


// setup the vars used in the randomCharacter function below
C.chars           = C.CHARACTERS_FOR_VOUCHER_CODES.split(',');
C.numchars        = C.chars.length;
C.randomCharacter = function() { 
	// see: https://developer.mozilla.org/en-US/docs/JavaScript/Reference/Global_Objects/Math/random
	var i = Math.floor((Math.random()*C.numchars)); 
	return C.chars[i];
}
C.prevchar = ''; // yes this is a Global Var ... :-?
C.generateCode = function(codelength) {
  var code = '', char = '';
  if(C.prefix && C.numrandomchars) { codelength = C.prefix.length + C.numrandomchars; code = C.prefix;}
  else if(C.numrandomchars) { codelength = C.numrandomchars; }
//  console.log(' Length :: ' +codelength  +C.prefix +' - ' +C.numrandomchars);
  while(code.length < codelength) {
      char = C.randomCharacter();
      if(char !== C.prevchar ) { code += char; C.prevchar = char; }
  }
  return code;  
}

app.listen(8080);
console.log('Server running at http://46.137.53.149:8080/');



