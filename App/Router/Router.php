<?php

namespace DwepD\Card\Router;

class Router
{
      private array $router = [];

      public function add(string $method, string $endpoint, string $action): void
      {
            $this->router[] = compact('method', 'endpoint', 'action');
      }
}