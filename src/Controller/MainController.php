<?php


namespace App\Controller;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

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

    /**
     * MainController constructor
     * Creates the Template Engine & adds its Extensions
     */
    public function __construct()
    {
        $this->twig = new Environment(new FilesystemLoader('../Template'), array(
            'cache' => false,
            'debug' => true
        ));
        $this->twig->addExtension(new DebugExtension());
    }

    public function render($view, array $params = [])
    {
        return $this->twig->render($view, $params);
    }
}
