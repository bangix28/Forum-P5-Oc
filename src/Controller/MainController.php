<?php


namespace App\Controller;

use App\Services\config\Request;
use Config\Orm;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

/**
 * Class MainController
 * Manages the Main Features
 * @package App\Controller
 */
abstract class MainController
{
    /**
     * @var Environment|null
     */
    protected $twig = null;

    protected $request;

    protected $orm;

    /**
     * MainController constructor
     * Creates the templates Engine & adds its Extensions
     */
    public function __construct()
    {
        $this->orm = new Orm();
        $this->twig = new Environment(new FilesystemLoader('../templates'), array(
            'cache' => false,
            'debug' => true
        ));
        $this->twig->addExtension(new DebugExtension());
        $this->twig->addGlobal('session', $_SESSION);
        $this->request = new Request();
    }

    public function render($view, array $params = [])
    {
        return $this->twig->render($view, $params);
    }
}
