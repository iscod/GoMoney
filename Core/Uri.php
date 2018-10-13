<?php
/**
 * Uri for GoMoney
 */

namespace GoMoney;

class Uri
{
    public function __construct()
    {
        $this->uri = $this->_parseUri();
    }

    public $uri = NULL;
    public $method = NULL;
    public $segments = [];

    public function getArgs()
    {
        return $this->_parseArgv();
    }

    /**
     * Parse CLI arguments
     *
     * Take each command line argument and assume it is a Uri segment.
     *
     * @return    string
     */
    protected function _parseArgv()
    {
        $args = array_slice($_SERVER['argv'], 1);
        return $args ? implode('/', $args) : '';
    }

    protected function _setUriString($str, $http_method = 'GET')
    {
        if ($http_method = 'CLI') {

        }

        $this->uri_string = trim($str, '/');
        if ($this->uri_string === '') {
            return TRUE;
        }

        $this->segments[0] = NULL;

        foreach (explode('/', trim($this->uri_string, '/')) as $segment) {
            $segment = trim($segment);
            if ($segment !== '') {
                $this->segments[] = $segment;
            }
        }

        unset($this->segments[0]);
    }

    protected function _parseUri()
    {
        if (!isset($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME'])) {
            return '';
        }

        $uri = parse_url($_SERVER['REQUEST_URI']);
        $uri = $uri['path'] ?? '';


//        [scheme] => http
//        [host] => hostname
//        [user] => username
//        [pass] => password
//        [path] => /path
//        [query] => arg=value
//        [fragment] => anchor
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getQueryString()
    {

    }

    /**
     * @return null
     */
    public function getUriString()
    {
        return $this->uri;
    }


}