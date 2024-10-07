<?php

namespace App\Controller;

use Slim\App;
use ReflectionClass;
use App\Attribute\Route;

class RootController
{
//    Enregistre les routes de chaque contrôleur
    public function registerRoutes(App $app)
    {
        $this->registerController($app, PingController::class);
        $this->registerController($app, UserController::class);
        $this->registerController($app, MongoController::class);
    }

//    Enregistre les routes d'un contrôleur donné
    private function registerController(App $app, string $controller)
    {
        $class = new ReflectionClass($controller);
        $routesAttributes = $class->getAttributes(Route::class);

        $prefix = '';

        if ($routesAttributes) {
            $prefix = $routesAttributes[0]->newInstance()->getPath();
        }

        foreach ($class->getMethods() as $method) {
            $routesAttributes = $method->getAttributes(Route::class);

            if (empty($routesAttributes)) {
                continue;
            }

            foreach ($routesAttributes as $attribute) {
                $route = $attribute->newInstance();
                $app->map([$route->getMethod()], $prefix . $route->getPath(), [new $controller, $method->getName()]);
            }
        }
    }
}
