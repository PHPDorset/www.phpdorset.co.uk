<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HelpOutController extends AbstractController
{
    /**
     * @Route("/get-involved")
     */
    public function viewAction(): Response
    {
        return $this->render('get_involved.twig');
    }
}
