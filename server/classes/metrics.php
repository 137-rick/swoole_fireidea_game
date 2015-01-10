<?php

class Metrics
{
    private $config;
    private $client;

    function __construct($config)
    {
        $this->config = $config;
        $this->client = new Memcache;
        $this->client->addServer(CACHE_IP, CACHE_PORT);

    }

    public function updatePlayerCounters()
    {
        /*
          var self = this,
            config = this.config,
            numServers = _.size(config.game_servers),
            playerCount = _.reduce(worlds, function(sum, world) { return sum + world.playerCount; }, 0);

        if(this.isReady) {
            // Set the number of players on this server
            this.client.set('player_count_'+config.server_name, playerCount, function() {
                var total_players = 0;

                // Recalculate the total number of players and set it
                _.each(config.game_servers, function(server) {
                    self.client.get('player_count_'+server.name, function(error, result) {
                        var count = result ? parseInt(result) : 0;

                        total_players += count;
                        numServers -= 1;
                        if(numServers === 0) {
                            self.client.set('total_players', total_players, function() {
                                if(updatedCallback) {
                                    updatedCallback(total_players);
                                }
                            });
                        }
                    });
                });
            });
         */
    }

    public function updateWorldDistribution($worlds)
    {
        return $this->client->set("world_distribution_".$this->config["server_name"],$worlds);
    }

    public function getOpenWorldCount()
    {
        return $this->client->get("world_count_".$this->config["server_name"]);
    }

    public function getTotalPlayers()
    {
        return $this->client->get("total_players");
    }
}