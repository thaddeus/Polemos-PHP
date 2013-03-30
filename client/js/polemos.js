//Global Socket Variable
var PolemosConnection;

//Document Load Function
$(function () {
	try
	{
		var PolemosServer = "ws://polemos.ixeta.net:47895/"; // SET THIS TO YOUR SERVER
		PolemosConnection = new WebSocket(PolemosServer);
		PolemosConnection.onopen    = function(msg) { 
							   console.log("Connected to Polemos Server"); 
						   };
		PolemosConnection.onmessage = function(msg) { 
							   console.log("Received: "+msg.data); 
							   processData(msg);
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
