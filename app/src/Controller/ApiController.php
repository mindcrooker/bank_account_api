<?php

namespace App\Controller;

use App\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api', name: 'app_api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    #[Route('/create_wallet', name:'create_wallet', methods: ['POST'])]
    public function createWallet(EntityManagerInterface $entityManager): Response
    {
        $wallet = new Wallet();
        $wallet->setBalance(0.0);
        $wallet->setLastUpdated(new \DateTime());

        $entityManager->persist($wallet);

        $entityManager->flush();
        return new Response('Saved new wallet with id '.$wallet->getId());
    }
}
