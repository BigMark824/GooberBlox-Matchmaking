# Stupid Simple Matchmaking
PHP Library used for simple equal distance matchmaking, great for Live Service Games!
### Installation 
Simply run
``composer require mathmark/stupid-simple-matchmaking`` in any PHP project setup with composer!

### StupidSimple\Game\Matchmaking
This is where all the magic happens! All our matchmaking functionality is called here.

### Constructor
- `private int $clientIpAddress` This is our base Client Address, this is what helps us find a server for a user

- `private ?array $availableGameServers;` This is an array populated with the metadata for our available GameServers

#### The structure should look like this 

```json
[
  {
    "name": "US-PROD-1",
    "ipAddress": "192.168.1.10"
  },
  {
    "name": "CHI-PROD-1",
    "ipAddress": "192.168.1.20"
  },
]
```

### Functions

- `getRandomServer() : array` This returns an ipAddress & name for a random GameServer available
- `getClosestServer() : array` This is where we utilise our `$clientIpAddress`, this will return an ipAddress & name for a GameServer thats closest to our User/Client


**Note:** If a name isn't provided for the server, one will be generated. **However** this is not recommended as it isn't great for organisation and is not consistent.

### How this works & what is tracked?
Our service uses a trusted web API ([ip-api](https://ip-api.com/)) to get metadata for our client and server (such as Geolocation & CountryCode), it then uses this information to MatchMake our user.

### Example Code

```php
use StupidSimple\Game\Matchmaking;

$availableGameServers = [
    [
        "name" => "US-PROD-1",
        "ipAddress" => "192.168.1.10"
    ],
    [
        "name" => "CHI-PROD-1",
        "ipAddress" => "192.168.1.20"
    ]
];

$clientIp = $_SERVER['REMOTE_ADDR'];

$matchmaking = new Matchmaking($clientIp, $servers);

$closestServer = $matchmaking->getClosestServer();
exit(json_encode($closestServer));
```

#### Example Response

`getClosestServer()` && `getRandomServer()` both respectively return this array
```json
{
    "serverName" : "US-PROD-1",
    "serverAddress" : "192.168.1.10"
}  
```
`getClosestServer()` also returns the distance in the array, `getRandomServer()` does not.

For example:

```json
{
    "serverName" : "US-PROD-1",
    "serverAddress" : "192.168.1.10",
    "distance" : "0.00" # measured in miles
}  
```

See ``StupidSimple\Math\DistanceCalulator`` for more information on how the algorithm works!

### Citations
https://en.wikipedia.org/wiki/Haversine_formula
