# rblxserverinfo
some php port for some shit my homie showed me

### How do I use this?

I assume whoever is using this has basic knowledge of php, if you don't then please leave now.
  where it says "PUT A FUCKING ID HERE" put a fucking id there of a game
  
	$dir = new GameServerInformation(PUT A FUCKING ID HERE);
	$dir->getCollectionSize();
	$dir->getListing();

	foreach ($dir->Servers as $server) {
		echo "<b> Server " . $server["Guid"] . ": </b> <br> " 
		. "IP: " . $server["IP"] . "<br>" 
		. "Ping: " . $server["Ping"] . "<br>"
		. "Fps: " . $server["Fps"] . "<br> <br>";

	}
	
	when getListing is called it will fill an array of all the open servers that can return data such ass the ip, ping and fps if the server is a VIP server, full of players or for some reason can't be reached (paid access and shit like that) it will return with no data.
	
	Each server array consists of 5 values
	Guid -> string
	IP -> string
	Ping -> float
	FPS -> float
	Players -> array
	
	i wrote this at 3 am for someone now fuck off
