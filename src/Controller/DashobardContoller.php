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


        $today = new \DateTime();
        $beginDate=$today->format('Y-m-d');
        $todayEvent = $repoEvent->createQueryBuilder('a')

            // Filter by some parameter if you want
             ->where('a.beginAt >= ?1')
             ->setParameter(1, $today)
            ->select('count(a.beginAt)')
            ->getQuery()
            ->getSingleScalarResult();


        $progressEvent = $repoEvent->createQueryBuilder('a')


            ->where('a.endAt < ?1')
            ->setParameter(1, $today)
            ->select('count(a.beginAt)')
            ->getQuery()
            ->getSingleScalarResult();

        $twigData = [
            'eventT' => $totalEvents,
            'usersT' => $totalUsers,
            'todayEvent'  => $todayEvent,
            'progress' => $progressEvent
        ];

        return $this->render('Dashboard/dashboard.html.twig',$twigData);
    }


}