<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Entity\Wallet;
use App\Repository\WalletRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
        return  $this->json('Created new wallet with id '.$wallet->getId());
    }

    #[Route('/check_balance/{id}', name: 'check_balance', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function checkBalance(EntityManagerInterface $entityManager, int $id): Response
    {
        $wallet = $entityManager->getRepository(Wallet::class)->find($id);
        
        if (!$wallet) {
            throw $this->createNotFoundException(
                'No wallet found for id '. $id
            );
        }

        $data = [
            'id' => $wallet->getId(),
            'balance' => $wallet->getBalance()
        ];

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/deposit/{id}', name:'deposit', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function deposit(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        $wallet = $entityManager->getRepository(Wallet::class)->find($id);

        if (!$wallet) {
            throw $this->createNotFoundException(
                'No wallet found for id '. $id
            );
        }

        $amount = $request->getPayload()->get("amount");
        if (!is_numeric($amount) || $amount < 0) {
            throw new HttpException(
                400,
                'Incorrect amount'
            );
        }

        $wallet->setBalance($wallet->getBalance() + $amount);
        $wallet->setLastUpdated(new \DateTime());

        $transaction = new Transaction($wallet, $amount);        
        $entityManager->persist($transaction);

        $entityManager->flush();

        return $this->json($amount . ' deposited to wallet with id '.$wallet->getId(), Response::HTTP_OK);
    }

    #[Route('/withdraw/{id}', name:'withdraw', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function withdraw(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        $wallet = $entityManager->getRepository(Wallet::class)->find($id);

        if (!$wallet) {
            throw $this->createNotFoundException(
                'No wallet found for id '. $id
            );
        }

        $amount = ($request->getPayload()->get("amount"));
        if (!is_numeric($amount) || $amount < 0) {
            throw new HttpException(
                400,
                'Incorrect amount'
            );
        }

        if ($wallet->getBalance() < $amount) {
                throw new HttpException(
                    400,
                    'Not enough funds to withdraw'
                );
            }

        $wallet->setBalance($wallet->getBalance() - $amount);
        $wallet->setLastUpdated(new \DateTime());

        $transaction = new Transaction($wallet, -$amount);        
        $entityManager->persist($transaction);

        $entityManager->flush();

        return $this->json($amount . ' withdrawn from wallet with id '.$wallet->getId(), Response::HTTP_OK);
    }
}