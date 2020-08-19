<?php


namespace App\Controller;


class IndexController extends MainController
{
    public function indexMethod(){
        return $this->render('base.html.twig');
    }

}
