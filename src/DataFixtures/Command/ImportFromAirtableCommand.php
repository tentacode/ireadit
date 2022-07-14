<?php

declare(strict_types=1);

namespace App\DataFixtures\Command;

use App\Brick\Metadatas\MetadatasScrapper;
use App\Entity\Link;
use App\Entity\LinkEvent;
use App\Implementation\Airtable\AirtableClient;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Webmozart\Assert\Assert;

#[AsCommand(name: 'fixtures:import-from-airtable', description: 'Import links from Airtable',)]
class ImportFromAirtableCommand extends Command
{
    public function __construct(
        private AirtableClient $airtableClient,
        private UserRepository $userRepository,
        private EntityManagerInterface $em,
        private MetadatasScrapper $metadataScrapper,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $tentacodeUser = $this->userRepository->findOneBy([
            'email' => 'gabriel@tentacode.test',
        ]);

        Assert::notNull($tentacodeUser);

        // dd($tentacodeUser);
        $airtableLinks = $this->airtableClient->get('toread');

        foreach ($airtableLinks['records'] as $airtableLink) {
            try {
                $fields = $airtableLink['fields'];

                $linkUrl = $fields['Url'];
                $io->comment($linkUrl);

                $metadatas = $this->metadataScrapper->scrapMetadatas($linkUrl);

                $link = new Link();
                $link->setTitle($fields['Nom']);
                $link->setUrl($linkUrl);
                $link->setType($fields['Type']);
                $link->setMetas($metadatas);

                $this->em->persist($link);

                $linkEvent = new LinkEvent();
                $linkEvent->setLink($link);
                $linkEvent->setAuthor($tentacodeUser);
                $linkEvent->setType('added');

                $this->em->persist($linkEvent);
            } catch (\Throwable $e) {
                $io->error($e->getMessage());
            }
        }

        $this->em->flush();

        $io->success('Imported from airtable.');

        return Command::SUCCESS;
    }
}
