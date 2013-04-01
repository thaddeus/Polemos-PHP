//Global Socket Variable
var PolemosConnection;

//Global canvas variables
var canvas;
var ctx;

//Game variables
var mapData; //Array of map tile info
var mapWidth; //Width in tiles
var mapHeight; //Height in tiles
var playerX; //Center player location (tile)
var playerY; //Center player location (tile)
var playerMap; //Current map that the player is on
var animOffsetX; //Offset for moving animation
var animOffsetY; //Offset for moving animation

//Media variables
var tileset1 = loadImage("media/tileset1.png");

//Game constants
var tilesize  = 32; //Tiles are always square
var mapBuffer = 2; //Two tile buffer

var GROUND_LAYER     = 0; // | Layer enumeration
var MASK_LAYER       = 1; // |
var MASK_LAYER_TWO   = 2; // |
var FRINGE_LAYER     = 3; // |
var FRINGE_LAYER_TWO = 4; // |

var MAP_PACKET        = 0; // | Packet enumeration
var MAP_UPDATE_PACKET = 1; // |

var REQUEST_MAP        = 0; // | Send Packet enumeration
var REQUEST_MAP_LEFT   = 1; // |
var REQUEST_MAP_RIGHT  = 2; // |
var REQUEST_MAP_TOP    = 3; // |
var REQUEST_MAP_BOTTOM = 4; // |



//Document Load Function
$(function () {
	try
	{
		var PolemosServer = "ws://polemos.ixeta.net:47895/"; // SET THIS TO YOUR SERVER
		PolemosConnection = new WebSocket(PolemosServer);
		PolemosConnection.onopen    = function(msg) {
							   console.log("Connected to Polemos Server");
							   initGame();
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
	ctx    = canvas.getContext("2d");

	// Set canvas proper width and height
	canvas.width  = $("#game").width();
	canvas.height = $("#game").height();

	mapWidth  = canvas.width / tilesize;
	mapHeight = canvas.height / tilesize;

	playerX = 50;
	playerY = 25;
	playerMap = 0;

	animOffsetX = 0;
	animOffsetY = 0;

	getMap = new Packet(REQUEST_MAP);
	sendPacket(JSON.stringify(getMap));
}

function drawScreen()
{
	ctx.clearRect(0,0, canvas.width, canvas.height); //Clear the canvas
	drawLayer(mapData[GROUND_LAYER]); // Draw ground layer
	drawLayer(mapData[MASK_LAYER]); // Draw mask layer
	drawLayer(mapData[MASK_LAYER_TWO]); // Draw mask layer 2
	//Draw player and other objects here
	drawLayer(mapData[FRINGE_LAYER]); // Draw fringe layer
	drawLayer(mapData[FRINGE_LAYER_TWO]); // Draw fringe layer 2
}

function drawLayer(curLayer)
{
	for (var i = curLayer.length - 1; i >= 0; i--) {
		if (curLayer[i][1] == 1)
			var tileset = tileset1;

		var xtiles = mapWidth + mapBuffer; //Width of drawn map in tiles

		var tiley  = Math.floor(curLayer[i][0] / (tileset.width / tilesize)) * tilesize; //Tile x location on tileset
		var tilex  = curLayer[i][0] % (tileset.width / tilesize) * tilesize; //Tile y location on tileset

		var yloc   = (playerY + Math.floor(mapHeight / 2) - curLayer[i][3]) * tilesize - (mapBuffer * tilesize) + animOffsetY; //Y location on canvas to draw
		var xloc   = (playerX + Math.floor(mapWidth / 2) - curLayer[i][2]) * tilesize - (mapBuffer * tilesize) + animOffsetX; //X location on canvas to draw

		ctx.drawImage(tileset, tilex, tiley, tilesize, tilesize, xloc, yloc, tilesize, tilesize);
	};
}

function Packet(request)
{
	switch(request)
	{
		case REQUEST_MAP:
			this.topic = REQUEST_MAP;
			this.xloc = playerX - Math.floor(mapWidth / 2) - mapBuffer;
			this.yloc = playerY - Math.floor(mapHeight / 2) - mapBuffer;
			this.map = playerMap;
			this.mapwidth = mapWidth + (mapBuffer * 2);
			this.mapheight = mapHeight + (mapBuffer * 2);
		break;
	}
}

function processData(data)
{
	var packet = $.parseJSON(data);

	switch(packet.topic)
	{
		case MAP_PACKET:
			var x = packet.data.left;
			var y = packet.data.top;

			//For each layer returned to us
			for (var i = 0; i < packet.data.map.length; i++) {
				var x = packet.data.left;
				var y = packet.data.top;
				//For each element in that layer
				for (var j = 0; j < packet.data.map[i].length; j++) {
					//Assign tile location on client end to reduce network transmission
					packet.data.map[i][j][2] = x;
					packet.data.map[i][j][3] = y;
					x++;
					if( x > packet.data.right )
					{
						x = packet.data.left;
						y++;
					}
				};
			};
			mapData = packet.data.map; //Override entire map
			drawScreen(); //DEBUG should just update map info and wait for next draw screen
		break;

		case MAP_UPDATE_PACKET:
			updateMap( packet.data.map );
		break;
	}
}

//Socket Send Message
function sendPacket(message){
	try {
		console.log(message);
		PolemosConnection.send(message);
	}
	catch(ex)
	{
		console.log(ex);
	}
}
