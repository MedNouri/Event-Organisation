<?php


namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
 class ForgotPasswordController extends AbstractController
{
    /**
     * @Route("/ForgetPassword", name="ForgetPassword", methods={"GET"})
     */
    public function index(EventRepository $eventRepository): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('list');
        }

        return $this->render('security/ForgotPassword.html.twig');
    }


}
