<?php

namespace App\Command;

use App\Entity\Wallet;
use App\Service\FileGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'api:generate-wallet-transactions-log',
    description: 'Generates log of translations for a single wallet',
    hidden: false,
    aliases: ['api:gwtl']
)]
class GenerateWalletTransactionsLog extends Command
{
    private EntityManagerInterface $entityManager;
    private FileGeneratorInterface $fileGenerator;

    public function __construct(EntityManagerInterface $entityManager, FileGeneratorInterface $fileGenerator)
    {
        $this->entityManager = $entityManager;
        $this->fileGenerator = $fileGenerator;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('wallet_id', InputArgument::REQUIRED, 'The id of a wallet');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $id = $input->getArgument('wallet_id');
        if (!intval($id)) {
            $output->writeln('Invalid wallet id');

            return Command::FAILURE;
        }

        $entityManager = $this->entityManager;

        $wallet = $entityManager->getRepository(Wallet::class)->find($id);
        if (!$wallet) {
            $output->writeln('No wallet found for id: '.$id);

            return Command::FAILURE;
        }

        $transactions = $wallet->getTransactions();
        $datetime = new \DateTime();
        $filename = 'wallet'.$id.' - '.$datetime->format('Y-m-d H:i:s');
        $path = $this->fileGenerator->generate($transactions, $filename);
        $output->writeln('Transaction log for wallet '.$id.' saved in file: '.$path);

        return Command::SUCCESS;
    }
}
