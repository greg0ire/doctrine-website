<?php

declare(strict_types=1);

namespace Doctrine\Website\Commands;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class BuildDatabaseCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('build-database')
            ->setDescription('Creates the database and its schema for temporary data on website build');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $metadata   = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($metadata);

        $output->writeln('<info>Database for data sources was created</info>');

        return 0;
    }
}
