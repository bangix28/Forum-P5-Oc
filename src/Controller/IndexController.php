<?php
namespace App\Controller;

class IndexController extends MainController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function indexMethod(){
    $user = $this->orm->entityManager()->find(':User', 1);
    return $this->render('index/index.html.twig', [
        'posts' => $this->orm->entityManager()->getRepository(':post')->findBy(array('user' => $user))]);
    }

    public function informationMethod()
    {
        return $this->render('index/information.html.twig');
    }

}
