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
     * @var string
     */
    public $uri = '';

    /**
     * @var string
     */
    public $action = '';

    /**
     * @var string
     */
    public $method = 'GET';

    /**
     * Router constructor.
     */
    public function __construct()
    {
        if (file_exists(CONFIG_PATH . 'routes.php')) {
            include CONFIG_PATH . 'routes.php';
        }

        $this->uri = new Uri();

        $this->_setRouting();


//        $this->enable_query_strings = ( ! is_cli() && $this->config->item('enable_query_strings') === TRUE);
//
//        // If a directory override is configured, it has to be set before any dynamic routing logic
//        is_array($routing) && isset($routing['directory']) && $this->set_directory($routing['directory']);
//        $this->_set_routing();
//
//        // Set any routing overrides that may exist in the main index file
//        if (is_array($routing))
//        {
//            empty($routing['controller']) OR $this->set_class($routing['controller']);
//            empty($routing['function'])   OR $this->set_method($routing['function']);
//        }
//
//        log_message('info', 'Router Class Initialized');

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
     * @param string $method
     * @return $this
     */
    public function setMethod(string $method)
    {
        $method = strtoupper($method);
        if (in_array($method, ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'TRACE', 'CONNECT'])) {
            $this->method = $method;
        } else {
            $this->method = 'GET';
        }
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
     * addRoute
     */
    public function addRoute()
    {
        if ($this->uri && $this->action) {
            if (is_array($this->method)) {
                foreach ($this->method as $verb) {
                    $this->routes[$verb . $this->uri] = ['method' => $verb, 'uri' => $this->uri, 'action' => $this->action];
                }
            } else {
                $this->routes[$this->method . $this->uri] = ['method' => $this->method, 'uri' => $this->uri, 'action' => $this->action];
            }
        }
    }

    public function getRoute()
    {
        return $this->routes;
    }

    /**
     *
     */
    protected function _setRouting()
    {
        if ($this->uri->getUriString() !== '') {
            $this->_parseRoutes();
        }else{
//            $this->setDefalueController();
        }
    }

    protected function _parseRoutes()
    {

    }

    protected function _parse_routes()
    {
        // Turn the segment array into a URI string
        $uri = implode('/', $this->uri->segments);

        // Get HTTP verb
        $http_verb = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'cli';

        // Loop through the route array looking for wildcards
        foreach ($this->routes as $key => $val) {
            // Check if route format is using HTTP verbs
            if (is_array($val)) {
                $val = array_change_key_case($val, CASE_LOWER);
                if (isset($val[$http_verb])) {
                    $val = $val[$http_verb];
                } else {
                    continue;
                }
            }

            // Convert wildcards to RegEx
            $key = str_replace(array(':any', ':num'), array('[^/]+', '[0-9]+'), $key);

            // Does the RegEx match?
            if (preg_match('#^' . $key . '$#', $uri, $matches)) {
                // Are we using callbacks to process back-references?
                if (!is_string($val) && is_callable($val)) {
                    // Remove the original string from the matches array.
                    array_shift($matches);

                    // Execute the callback using the values in matches as its parameters.
                    $val = call_user_func_array($val, $matches);
                } // Are we using the default routing method for back-references?
                elseif (strpos($val, '$') !== FALSE && strpos($key, '(') !== FALSE) {
                    $val = preg_replace('#^' . $key . '$#', $val, $uri);
                }

                $this->_set_request(explode('/', $val));
                return;
            }
        }

        // If we got this far it means we didn't encounter a
        // matching route so we'll set the site default route
        $this->_set_request(array_values($this->uri->segments));
    }
}