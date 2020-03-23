<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SuccessController extends AbstractController
{
    /**
     * @Route("/success", name="success")
     */
    public function index()
    {
        return $this->render('success/success.html.twig');
    }
}
