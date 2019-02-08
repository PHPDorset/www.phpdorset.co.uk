<?php

namespace App\Controller;

use App\Entity\Talk;
use App\Repo\TalkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomepageController extends AbstractController
{
    private $repository;

    public function __construct(TalkRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/")
     */
    public function viewAction(): Response
    {
        $currentDate = (new \DateTimeImmutable('now'));

        $talksThisMonthInTheFuture = 0;

        $months = [];

        $thisMonth = new \DateTimeImmutable('first day of this month');

        $nextMonth = $thisMonth->add(new \DateInterval('P1M'));
        $lastMonth = $thisMonth->sub(new \DateInterval('P1M'));

        $nextMonthTalks = $this->repository->fetchTalks($nextMonth->format('Y'), strtolower($nextMonth->format('F')));
        $thisMonthTalks = $this->repository->fetchTalks($thisMonth->format('Y'), strtolower($thisMonth->format('F')));
        $lastMonthTalks = $this->repository->fetchTalks($lastMonth->format('Y'), strtolower($lastMonth->format('F')));

        if (is_array($thisMonthTalks)) {
            $talksThisMonthInTheFuture = array_filter(
                $thisMonthTalks,
                function (Talk $talk) use ($currentDate) {
                    return $talk->getDate() >= $currentDate;
                }
            );
        }

        if (count($talksThisMonthInTheFuture) < 1) {
            $months[$nextMonth->format('c')] = $nextMonthTalks;
        }

        $months[$thisMonth->format('c')] = $thisMonthTalks;
        $months[$lastMonth->format('c')] = $lastMonthTalks;

        return $this->render('homepage.twig', ['months' => $months, 'currentDate' => $currentDate->format('Y-m-d')]);
    }
}
