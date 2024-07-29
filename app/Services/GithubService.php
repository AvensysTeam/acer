<?php

namespace App\Services;

use Github\Client;

class GithubService
{
    protected $client;

    public function __construct()
    {
        $token = config('services.github.token');
        if (!$token) {
            throw new \Exception('GitHub token not configured.');
        }

        $this->client = new Client();
        $this->client->authenticate($token, null, Client::AUTH_ACCESS_TOKEN);
    }

    public function getLatestCommits($owner, $repo)
    {
        return $this->client->api('repo')->commits()->all($owner, $repo, ['sha' => 'main', 'per_page' => 3]);
    }
}
