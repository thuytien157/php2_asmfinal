<?php
class Router
{
    private array $routes = [];

    public function add(string $path, array $params): void
    {
        $this->routes[] = [
            "path" => trim($path, "/"),
            "params" => $params
        ];
    }

    public function match(string $path): array|bool
    {
        $path = trim($path, "/");
    
        if ($path !== "/user/google-login-callback") {
            $path = strtok($path, '?'); // Chỉ loại bỏ khi không phải Google OAuth callback
        }
        
    
        foreach ($this->routes as $route) {
            if ($this->isMatch($route["path"], $path, $dynamicParams)) {
                return array_merge($route["params"], $dynamicParams);
            }
        }
    
        return false;
    }
    
    private function isMatch(string $route, string $path, ?array &$dynamicParams): bool
    {
        $routeParts = explode("/", $route);
        $pathParts = explode("/", $path);

        if (count($routeParts) !== count($pathParts)) {
            return false;
        }

        $dynamicParams = [];

        foreach ($routeParts as $index => $part) {
            if ($part === $pathParts[$index]) {
                continue;
            }
            if (preg_match("/{(.+)}/", $part, $matches)) {
                $dynamicParams[$matches[1]] = $pathParts[$index];
            } else {
                return false;
            }
        }

        return true;
    }
}
?>
