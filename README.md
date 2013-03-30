Polemos
=======

A web based MMORPG engine

Server
=======

Polemos' server is based on a PHP WebSocket framework called Ratchet which can be found on GitHub. (https://github.com/cboden/Ratchet)

To use the server, Ratchet must first be installed with a free script called 'composer'. (http://getcomposer.org/) Then, from ssh or terminal, cd into the server folder and manually execute the php file. (Example: 'php -f polemos.php&') Note: Polemos needs to be run from command line, running it from a URL will not work properly.

Client
=======

Polemos' client is an HTML5 WebSocket client and canvas element.
