<?php


namespace App\Services\config;

class Request
{
    private $get;

    private $post;

    private $session;

    private $files;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->get = new Parameter($_GET);
        $this->post = new Parameter($_POST);
        $this->session = new Session($_SESSION);
        $this->files = new Parameter($_FILES);
    }

    /**
     * @return Parameter
     */
    public function getGet()
    {
        return $this->get;
    }

    /**
     * @return Parameter
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    public function getFiles()
    {
        return $this->files;
    }

}
