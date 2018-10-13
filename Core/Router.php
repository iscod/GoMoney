<?php
/**
 * Route for GoMoney
 */

namespace GoMoney;

class Router
{
    /**
     * @var array
     */
    public $routes = [];

    /**
     * @var Uri
     */
    public $uri = NULL;

    /**
     * @var string
     */
    public $action = '';

    /**
     * @var string
     */
    public $method = 'GET';

    /**
     * @var string
     */
    public $class = '';

    /**
     * @var string
     */
    public $directory = '';

    /**
     * @var array
     */
    public $route = [];

    /**
     * Router constructor.
     */
    public function __construct()
    {
        if (file_exists(CONFIG_PATH . 'routes.php')) {
            include CONFIG_PATH . 'routes.php';
        }

        $this->_parseRoutes();
    }

    public function setClass(string $class)
    {
        $this->class = str_replace(array('/', '.'), '', $class);
        return $this;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param string $uri
     * @return $this
     */
    public function setUri(string $uri)
    {
        $this->uri = strtolower($uri);
        return $this;
    }

    /**
     * @param string $action
     * @return $this
     */
    public function setAction(string $action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param null $key
     * @return array|mixed|string
     */
    public function getRoute($key = NULL)
    {
        if ($key === NULL) {
            return $this->route;
        } else {
            return $this->route[$key] ?? '';
        }

    }

    /**
     * addRoute
     * @return bool
     */
    public function addRoute()
    {
        $this->action = $this->_validateAction($this->action);

        if ($this->uri && $this->action) {
            if (is_array($this->method)) {
                foreach ($this->method as $verb) {
                    $this->routes[$verb . $this->uri] = [
                        'method' => $verb,
                        'uri' => $this->uri,
                        'action' => $this->action,
                        'directory' => $this->directory
                    ];
                }
            } else {
                $this->routes[$this->method . $this->uri] = [
                    'method' => $this->method,
                    'uri' => $this->uri,
                    'action' => $this->action,
                    'directory' => $this->directory
                ];
            }
        }

        return TRUE;
    }

    protected function _parseRoutes()
    {
        $uri = new Uri();
        $method = strtoupper($uri->getMethod());
        $uri = trim($uri->getUri(), '/');
        if ($uri === '') {
            $this->route = $this->routes['default_route'];
        } else {
            $this->route = $this->routes[$method . $uri] ?? '';
        }

        $action = $this->route['action'] ?? '';
        $action = explode('/', trim($action, '/'));
        $this->setUri($this->route['uri'] ?? '');
        $this->setClass($action[0] ?? '');
        $this->setAction($this->route['action'] ?? '');
        $this->setMethod($this->route['method'] ?? '');
    }

    public function addDefaultRoute()
    {
        $this->routes['default_route'] = [
            'method' => $this->method,
            'uri' => '',
            'action' => $this->action,
            'directory' => ''
        ];
    }

    /**
     * @param string $action
     * @return array|string
     */
    protected function _validateAction(string $action)
    {
        $action = explode('/', trim($action, '/'));

        $c = count($action);

        while ($c-- > 0) {
            $test_file = ucfirst($action[0]);
            if (!file_exists(APP_PATH . 'Controller/' . $test_file . '.php') && is_dir(APP_PATH . 'Controller/' . $this->directory . $action[0])) {
                $this->_setDirectory(array_shift($action), TRUE);
                continue;
            }

            return implode('/', $action);
        }

        return $action;
    }

    /**
     * @param string $directory
     */
    protected function _setDirectory(string $directory)
    {
        $this->directory .= str_replace('.', '', trim($directory, '/')) . '/';
    }
}