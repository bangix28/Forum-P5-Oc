<?php


namespace App;

use App\Services\config\Request;
use RuntimeException;

class Router
{
    /*
     * default path to all controller
     */
    const DEFAULT_PATH = "App\Controller\\";

    /*
     * Default controller
     */
    const DEFAULT_CONTROLLER = "IndexController";

    /*
     * Default method
     */
    const DEFAULT_METHOD = "index";

    /*
     * Requested Controller
     * @var string
     */
    private $controller = self::DEFAULT_CONTROLLER;

    /*
     *Requested method
     * @var string
     */
    private $method = self::DEFAULT_METHOD;

    private $request;

    public function __construct()
    {
        $this->parseUrl();
        $this->setController();
        $this->setMethod();
    }

    /*
     *
     */
    public function parseUrl()
    {
        $access = filter_input(INPUT_GET, "access");

        if (!isset($access)){
            $access = "index";
        }

        $access = explode("!",$access);
        $this->controller = $access[0];
        $this->method = count($access) == 1 ? "index" : $access[1];
    }

    /*
     *
     */
    public function setController(){

        $this->controller = ucfirst(strtolower($this->controller)) . "Controller";
        $this->controller = self::DEFAULT_PATH . $this->controller;

            if(!class_exists($this->controller)){
                throw new RuntimeException('Erreur 404 ');
            }
    }

    /*
     *
     */
    public function setMethod(){

        $this->method = strtolower($this->method) . "Method";
         if (!method_exists($this->controller,$this->method)){
             throw new RuntimeException('Erreur 404 ');
         }
    }

    /*
     *
     */
    public function run(){
        $this->controller = new $this->controller;
        $response = call_user_func([$this->controller,$this->method]);

        echo filter_var($response);
    }
}
