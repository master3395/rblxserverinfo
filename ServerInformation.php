<?php
	class GameServerInformation 
	{
		private $placeId;
		private $CollectionSize;
		private $Collection;
		public  $Servers;

		protected $InstanceAPI 		= "http://www.roblox.com/games/getgameinstancesjson?placeId=%d&startindex=%d";
		protected $JobAPI			= "http://www.roblox.com/Game/PlaceLauncher.ashx?request=RequestGameJob&gameId=%s&placeId=%d";
		protected $Agent 			= "rbx";

		public function __construct($placeId)
		{
			$this->placeId = $placeId;
		}

		public function DownloadString($url) {
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			$resp = curl_exec($ch);
			curl_close($ch);

			return $resp;
		}

		public function getCollectionSize()
		{
			$response = $this->DownloadString(sprintf($this->InstanceAPI, $this->placeId, 0));
			$response = json_decode($response);
			$this->CollectionSize = $response->TotalCollectionSize;
			$this->Collection = $response->Collection;
			return $response->TotalCollectionSize;
		}

		public function getListing()
		{
			$index = 0;
			while($index <= $this->CollectionSize)
			{
				foreach($this->Collection as $Collection)
				{
					// JobAPI Requests
					$req = json_decode($this->DownloadString(sprintf($this->JobAPI, $Collection->Guid, $this->placeId)));
					//echo "<i>" . var_dump(sprintf($this->JobAPI, $Collection->Guid, $this->placeId)) . " </i>";
					$jobURL = $req->joinScriptUrl;
					// End JobAPI Requests

					if ($req->status == "2") {
						$sreq = $this->DownloadString($jobURL);
						$sreq = json_decode(substr($sreq, stripos($sreq, "{")));

						$ServerInformation = [
							"Guid"		=> $Collection->Guid,
							"IP" 		=> $sreq->MachineAddress,
							"Ping" 		=> $Collection->Ping,
							"Fps" 		=> $Collection->Fps,
							"Players"	=> $Collection->CurrentPlayers,
						];

						$this->Servers[] = $ServerInformation;
					}
				}
				$index=$index+10;
			}
		}


	}

	$dir = new GameServerInformation(270607122);
	$dir->getCollectionSize();
	$dir->getListing();

	foreach ($dir->Servers as $server) {
		echo "<b> Server " . $server["Guid"] . ": </b> <br> " 
		. "IP: " . $server["IP"] . "<br>" 
		. "Ping: " . $server["Ping"] . "<br>"
		. "Fps: " . $server["Fps"] . "<br> <br>";

	}
