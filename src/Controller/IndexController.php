<?php
namespace App\Controller;
require_once __DIR__.'/../../config/bootstrap.php';


class IndexController extends MainController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function indexMethod(){
    return $this->render('index/index.html.twig');
    }

}
