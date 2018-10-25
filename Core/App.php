<?php
/**
 * App for GoMoney
 */

namespace GoMoney;

defined('CORE_PATH') OR exit('No direct script access allowed');

const GM_VERSION = '0.0.1';

if (!class_exists('App')) {
    class App
    {
        public $uri;
        public $cfg;
        public $rtr;
        public $input;
        public $view;
        public $route;

        public function __construct()
        {
            $this->uri = new Uri();
            $this->input = new InPut();
            $this->route = new Router();
        }

        /**
         * @param string $name
         * @param array $arguments
         * @return mixed
         */
        public function __call(string $name, array $arguments)
        {
            return call_user_func($name, $arguments);
        }


        /**
         * @param string $file
         * @param array $param
         * @return View
         */
        public function view(string $file, array $param = [])
        {
            return (new View($file, $param));
        }

        public function init()
        {
            if (!$this->route->class) {
                $this->view('error_404');
            }

            $class = basename(APP_PATH) . '\\' . 'Controller' . '\\' . $this->route->class;

            if (!class_exists($class)) {
                $this->view('error_404');
            }

            $class = new $class();
            $offset = stripos($this->route->action, $this->route->class);
            if ($offset !== FALSE) {
                $function = trim(substr($this->route->action, $offset + strlen($this->route->class)), '/');
            } else {
                $function = 'index';
            }

            if (in_array($function, get_class_methods($class))) {
                $return = call_user_func_array([$class, $function], is_array($this->uri->getArgs()) ? $this->uri->getArgs() : []);
                return $return;
            } else {
                $this->view('error_404');
            }
        }
    }
}

