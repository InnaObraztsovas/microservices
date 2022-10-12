<?php

namespace App\Command;


use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(name: 'user:create-http')]
class UserHttpRequest extends Command
{
    private array $availableRoutesKeys = [
        'app_user', 'user_store', 'user_show'
    ];
    private HttpClientInterface $client;

    private $routes = [];

    public function __construct(RouterInterface $router)
    {
        foreach ($router->getRouteCollection()->all() as $route_name => $route) {
            if (in_array($route_name, $this->availableRoutesKeys)) {
                $this->routes[$route_name] = $route->getPath();
            }
        }

        $this->client = HttpClient::create();
        parent::__construct();
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->client->request('POST', 'http://gateway/register', ['json' => $this->getRoutes()]);

        $output->writeln($response->getContent(false));

        return Command::SUCCESS;

    }
}