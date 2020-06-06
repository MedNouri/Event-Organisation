<?php
namespace App\EventSubscriber;

use App\Repository\EventRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
class CalendarSubscriber implements EventSubscriberInterface
{
    private $eventRepository;
    private $router;

    public function __construct(
        EventRepository $bookingRepository,
        UrlGeneratorInterface $router
    ) {
        $this->eventRepository = $bookingRepository;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {


        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
        $bookings = $this->eventRepository
            ->createQueryBuilder('Event')
            ->where('Event.beginAt BETWEEN :start and :end OR Event.endAt BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;

        foreach ($bookings as $booking) {
            // this create the events with your data (here booking data) to fill calendar
            $bookingEvent = new Event(
                $booking->getTitle(),
                $booking->getBeginAt(),
                $booking->getEndAt()
            );

            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $bookingEvent->setOptions([
                'backgroundColor' => 'red',
                'borderColor' => 'red',
            ]);
              $bookingEvent->addOption(
                 'url',
                $this->router->generate('event_show', [
                    'id' => $booking->getId(),
               ])
               );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($bookingEvent);
        }
    }
}

//$calendar->addEvent(new Event(
   // 'Event 1',
    //new \DateTime('Tuesday this week'),
  //  new \DateTime('Wednesdays this week')
//));

// If the end date is null or not defined, it creates a all day event
//$calendar->addEvent(new Event(
// 'All day event',
//  new \DateTime('Friday this week')
//));