<?php
/**
 * Uri for GoMoney
 */

namespace GoMoney;

class Uri
{

    private $uri = NULL;
    private $query = [];
    private $method = NULL;
    private $args = NULL;
    private $head = [];
    private $cookie = '';

    public function __construct()
    {
        $this->args = $this->_parseArgv();
        $this->uri = $this->_parseUri();
        $this->query = $this->_parseQuery();
        $this->method = $this->_parseMethod();
        $this->head = $this->_parseHead();
        $this->cookie = $this->_parseCookie();
    }

    /**
     * @return null|string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string|NULL $key
     * @return array|mixed|string
     */
    public function getQuery(string $key = NULL)
    {
        if ($key === NULL) {
            return $this->query;
        } else {
            return $this->query[$key] ?? '';
        }
    }

    /**
     * @return null|string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @param string|NULL $key
     * @return mixed|string
     */
    public function getCookie(string $key = NULL)
    {
        if ($key === NULL) {
            return $this->cookie;
        } else {
            return $this->cookie[$key] ?? '';
        }

    }

    /**
     * @param string|NULL $key
     * @return array|mixed|string
     */
    public function getHead(string $key = NULL)
    {
        if ($key === NULL) {
            return $this->head;
        } else {
            return $this->head[strtoupper($key)] ?? '';
        }
    }

    /**
     * @return bool
     */
    public function isCli()
    {
        return (strtolower(PHP_SAPI) === 'cli' OR strtolower(php_sapi_name()) === 'cli');
    }

    /**
     * @return string
     */
    protected function _parseUri()
    {
        if ($this->isCli()) {
            $uri = $this->getArgs();
        } else {
            if (!isset($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME'])) {
                return '';
            }

            $url = parse_url("http://baidu.com/" . $_SERVER['REQUEST_URI']);
            $query = $url['query'] ?? '';
            $uri = $url['path'] ?? '';
            if (isset($_SERVER['SCRIPT_NAME'][0])) {
                if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
                    $uri = (string)substr($uri, strlen($_SERVER['SCRIPT_NAME']));
                } elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0) {
                    $uri = (string)substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
                }
            }

            if (trim($uri, '/') === '' && strncmp($query, '/', 1) === 0) {
                $query = explode('?', $query, 2);
                $uri = $query[0];
                $_SERVER['QUERY_STRING'] = isset($query[1]) ? $query[1] : '';
            } else {
                $_SERVER['QUERY_STRING'] = $query;
            }

            if ($uri === '/' OR $uri === '') {
                $uri = '/';
            }

            $uri = $this->_formatUri($uri);
        }
        return $uri;
    }

    /**
     * @return string
     */
    protected function _parseQuery()
    {
        $uri = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');

        if (trim($uri, '/') === '') {
            return '';
        } elseif (strncmp($uri, '/', 1) === 0) {
            $uri = explode('?', $uri, 2);
            $_SERVER['QUERY_STRING'] = isset($uri[1]) ? $uri[1] : '';
        }

        parse_str($_SERVER['QUERY_STRING'], $_GET);
        return $_GET;
    }

    /**
     * @param $string
     * @return string
     */
    protected function _formatUri($string)
    {
        $uri = [];
        $tok = strtok($string, DIRECTORY_SEPARATOR);
        while ($tok !== FALSE) {

            if (!empty($tok) && $tok === '../' || $tok !== './') {
                $uri[] = $tok;
            }

            $tok = strtok('/');
        }

        return implode('/', $uri);
    }

    /**
     * @return null|string
     */
    protected function _parseMethod()
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'CLI';
        $method = strtoupper($method);
        if (!in_array($method, ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'TRACE', 'CONNECT'])) {
            $method = 'CLI';
        }

        return $method;
    }

    /**
     * @return string
     */
    protected function _parseArgv()
    {
        $args = isset($_SERVER['argv']) ? array_slice($_SERVER['argv'], 1) : '';
        return $args ? implode('/', $args) : '';
    }

    protected function _parseHead()
    {
        return $_SERVER;
    }

    protected function _parseCookie()
    {
        return $_COOKIE;
    }
}