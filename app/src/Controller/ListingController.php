<?php

/**
 * Listing Controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 *  @class ListingController.
 */
#[Route('/')]
class ListingController extends AbstractController
{
    /**
     * Index Action.
     *
     * @return Response HTTP response
     */
    #[Route('', name: 'listings_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('listing/index.html.twig', []);
    }
}
