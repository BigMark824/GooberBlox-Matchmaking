<?php

namespace Mathmark\Gooberblox\Matchmaking;

use  Mathmark\GooberBlox\Math\DistanceCalculator;

class Matchmaking
{
    private int $clientIpAddress;

    /*
        GameServer Structure should look like this
        Note: If a a Server Name isn't provided, a random one will be generated for you. This is not recommended...

        {
            "name": "Prod-US-1",
            "ipAddress" : "227.170.47.89"
        }

    */
    private ?array $availableGameServers;


    public function __construct(int $clientIpAddress, ?array $availableGameServers = null)
    {
        $this->clientIpAddress = $clientIpAddress;
        $this->availableGameServers = $availableGameServers;
    }

    private function getClientMetadata(): array
    {
        $getMetadata = file_get_contents("http://ip-api.com/json/" . $this->clientIpAddress);
        $metadata = json_decode($getMetadata, true);

        if ($getMetadata === null)
            throw new \Exception("Couldn't retrieve Client MetaData for IP: " . $this->clientIpAddress);

        return $metadata;
    }

    public function getRandomServerName() : string
    {
        $randomNames = [
            'Alfa',
            'Bravo',
            'Charlie',
            'Delta',
            'Echo',
            'Foxtrot',
            'Golf',
            'Hotel',
            'India',
            'Juliett'
        ];

        $randomNameKey = array_rand($randomNames);

        return $randomNames[$randomNameKey];
    }

    public function getRandomServer() : array
    {
        if (empty( $this->availableGameServers ) || !$this->availableGameServers) {
            throw new \Exception("There are no available gameservers.");
        }

        $randomServer = array_rand($this->availableGameServers);
        $server = $this->availableGameServers[$randomServer];

        return [
            'serverName' => $server->name ?? $this->getRandomServerName(),
            'serverAddress' => $server->ipAddress
        ];
    }
    public function getClosestServer(): array
    {
        try {
            $distanceCalculator = new DistanceCalculator();
            $clientMetadata = $this->getClientMetadata();

            if (empty( $this->availableGameServers ) || !$this->availableGameServers) {
                throw new \Exception("There are no available gameservers.");
            }

            $userLat = $clientMetadata['lat'];
            $userLon = $clientMetadata['lon'];

            $minDistance = PHP_INT_MAX;
            $closestServer = null;

            foreach ($this->availableGameServers as $server) {
                $srvLon = $server->lon;
                $srvLat = $server->lat;
                $distance = $distanceCalculator->CalculateDistance($userLat, $userLon, $srvLat, $srvLon);

                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $closestServer = $server;
                }
            }

            return [
                'serverName' => $closestServer->name ?? $this->getRandomServerName(),
                'serverAddress' => $closestServer->ipAddress,
                'distance' => $distance
            ];

        } catch (\Exception $e) {
            throw new \Exception("There are no available gameservers. " . $e);
        }
    }
}
