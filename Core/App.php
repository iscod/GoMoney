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
         * @param string $message
         * @param int $code
         * @param mixed $data
         */
        static function returnSuccess(string $message = "success", int $code = 200, mixed $data)
        {
            print_r($data);
        }

        /**
         * @param string $file
         * @param array $param
         * @return View
         */
        public function view(string $file, array $param)
        {
            return (new View($file, $param));
        }

        public function x($data)
        {
            return $data['a'] * 100;
        }

        public function run()
        {
            var_dump($this->route->getRoute());
            var_dump($this->uri->getUriString());
die();
//            $new = new $this->uri->getController();

            var_dump($this->uri->getArgs());
            var_dump($this->input->post());
//            var_dump(VIEW_PATH);
//            $x = new Uri();
//            var_dump($x);
//            var_dump($x->getArgs());
//            load_class($this->cfg['$this->uri->getArgs()'], APP_PATH . 'Controller');
//            $class = $this->rtr->class;
//            var_dump($class);
//            var_dump($this->uri->getArgs());
        }
    }
}

