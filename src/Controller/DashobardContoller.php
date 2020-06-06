<?php


namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Form\UserType;
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
        $em = $this->getDoctrine()->getManager();


        $repousers = $em->getRepository(User::class);


        $totalUsers = $repousers->createQueryBuilder('a')
            // Filter by some parameter if you want
            // ->where('a.published = 1')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();


        $repoEvent = $em->getRepository(Event::class);

        // 3. Query how many rows are there in the Articles table
        $totalEvents = $repoEvent->createQueryBuilder('a')

            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();



        $twigData = [
            'eventT' => $totalEvents,
            'usersT' => $totalUsers,
            'todayEvent'  => 50,
            'progress' => 10
        ];

        return $this->render('Dashboard/dashboard.html.twig',$twigData);
    }


}