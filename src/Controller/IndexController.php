<?php
// src/Controller/IndexController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


// ...
class IndexController extends AbstractController
{
    /**
     * @Route("/lucky/number")
     */
    public function number()
    {
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->connect();
        $connected = $em->getConnection()->isConnected();


        return $this->render('index.html.html.twig', [
            'number' => $connected,
        ]);
    }
}
