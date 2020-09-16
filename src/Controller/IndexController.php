<?php
namespace App\Controller;
use App\Services\config\Request;

class IndexController extends MainController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function indexMethod(){
    return $this->render('index/index.html.twig', ['posts' => $this->orm->entityManager()->getRepository(':post')->findAll()]);
    }

}
