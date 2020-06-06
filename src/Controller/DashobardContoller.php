<?php


namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
class DashobardContoller extends AbstractController
{
    /**
     * @Route("/index", name="indexPage", methods={"GET"})
     */
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('Dashboard/dashboard.html.twig');
    }


}