<?php
class Router {
    private $routes = [];

    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch($method, $uri) {
        // Supprimer la chaîne de requête et le slash final
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/');
        
        // Si l'URI est vide, la définir comme '/'
        if (empty($uri)) {
            $uri = '/';
        }
        
        // Supprimer le chemin de base de l'URI
        $basePath = parse_url(BASE_URL, PHP_URL_PATH);
        if (!empty($basePath) && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        // Si l'URI est vide après suppression du chemin de base, la définir comme '/'
        if (empty($uri)) {
            $uri = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = $this->convertRouteToRegex($route['path']);
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Supprimer la correspondance complète
                $this->executeHandler($route['handler'], $matches);
                return;
            }
        }

        // Aucune route trouvée
        header("HTTP/1.0 404 Not Found");
        echo '404 Not Found';
    }

    private function convertRouteToRegex($route) {
        // Convertir les paramètres de route en modèles regex
        $pattern = preg_replace('/\/:([^\/]+)/', '/([^/]+)', $route);
        return '#^' . $pattern . '$#';
    }

    private function executeHandler($handler, $params) {
        list($controller, $method) = explode('@', $handler);
        
        $controllerFile = 'controllers/' . $controller . '.php';
        if (!file_exists($controllerFile)) {
            throw new Exception("Controller file not found: $controllerFile");
        }
        
        require_once $controllerFile;
        
        $controllerInstance = new $controller();
        call_user_func_array([$controllerInstance, $method], $params);
    }
}

