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
        $config = Config::load(strtolower(__CLASS__));
        $this->_parseConfig($config);
        $this->_parseRoute();
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

    public function _parseConfig($config)
    {
        foreach ($config as $item) {
            if (is_array($item['method'])) {
                foreach ($item['method'] as $verb) {
                    $this->addRoute($item['uri'], $item['action'], $verb);
                }
            } else {
                $this->addRoute($item['uri'], $item['action'], $item['method'] ?? '');
            }
        }

        return $this->routes;
    }

    /**
     * @param $uri
     * @param $action
     * @param string $method
     * @return array
     */
    public function addRoute(string $uri, string $action, string $method = '')
    {
        if (!$method) $method = 'GET';
        $this->routes[strtolower($method . $uri)] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action
        ];
        return $this->routes;
    }

    protected function _parseRoute()
    {
        $uri = new Uri();

        $method = strtoupper($uri->getMethod());
        $uri = trim($uri->getUri(), '/');

        if ($uri === '') {
            $this->route = $this->routes[strtolower($method . 'default')];
        } else {
            $this->route = $this->routes[strtolower($method . $uri)] ?? '';
        }

        $action = $this->route['action'] ?? '';
        $action = explode('/', trim($action, '/'));
        $this->setUri($this->route['uri'] ?? '');
        $this->setClass($action[0] ?? '');
        $this->setAction($this->route['action'] ?? '');
        $this->setMethod($this->route['method'] ?? '');
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
                array_shift($action);
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