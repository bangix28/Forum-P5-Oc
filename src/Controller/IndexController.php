<?php


namespace App\Controller;


class IndexController
{
    public function indexMethod(){
        return $this->render('base.html');
    }

}
