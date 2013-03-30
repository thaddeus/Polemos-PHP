//Global Socket Variable
var PolemosConnection;

//Global canvas variables
var canvas;
var ctx;

//Game variables
var playerMap;

//Media variables
var tileset1 = loadImage("media/tileset1.png");

//Document Load Function
$(function () {
	try
	{
		var PolemosServer = "ws://polemos.ixeta.net:47895/"; // SET THIS TO YOUR SERVER
		PolemosConnection = new WebSocket(PolemosServer);
		PolemosConnection.onopen    = function(msg) { 
							   console.log("Connected to Polemos Server"); 
							   send('Test');
						   };
		PolemosConnection.onmessage = function(msg) { 
							   console.log("Received: " + msg); 
							   processData(msg.data);
						   };
		PolemosConnection.onclose   = function(msg) { 
							   console.log("Disconnected from Polemos Server"); 
						   };
	}
	catch(ex)
	{
		console.log(ex);
	}
	initGame();
});

function loadImage(imageURI) {
	var img = new Image();
	img.src = imageURI;
	return img;
}

function initGame()
{
	// Set canvas and context variables
	canvas = $("#game")[0];
	ctx = canvas.getContext("2d");

	// Set canvas proper width and height
	canvas.width = $("#game").width();
	canvas.height = $("#game").height();
}

function drawMap()
{
	for (var i = playerMap.length - 1; i >= 0; i--) {
		if (playerMap[i][1] == 1)
			var tileset = tileset1;
		var tiley = Math.floor(playerMap[i][0] / (tileset.width / 32));
		var tilex = playerMap[i][0] % (tileset.width / 32);
		var yloc = Math.floor(i / (canvas.width / 32)) * 32;
		var xloc = (i % (canvas.width / 32)) * 32;
		ctx.drawImage(tileset, tilex * 32, tiley * 32, 32, 32, xloc, yloc, 32, 32);
	};
}

function processData(data)
{
	var packet = $.parseJSON(data);
	if (packet.topic == 0)
	{
		playerMap = packet.map;
		drawMap();
	}
}

//Socket Send Message
function send(message){
	try { 
		PolemosConnection.send(message); 
	} 
	catch(ex) 
	{ 
		console.log(ex); 
	}
}
