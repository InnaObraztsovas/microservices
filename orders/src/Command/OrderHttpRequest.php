<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(name: 'order:create-http')]
class OrderHttpRequest extends Command
{
    private HttpClientInterface $client;

    private array $routes = [];

    public function __construct(RouterInterface $router)
    {
        foreach ($router->getRouteCollection()->all() as $route_name => $route) {
            $this->routes[$route_name] = [
                'path' => $route->getPath(),
                'methods' => $route->getMethods(),
                'defaults' => $route->getDefaults()
            ];
        }

        $this->client = HttpClient::create();

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->client->request('POST', 'http://gateway/register', ['json' => [
            'routes' => $this->routes,
            'service_name' => 'orders',
        ]]);

        $output->writeln($response->getContent(false));

        return Command::SUCCESS;
    }
}
