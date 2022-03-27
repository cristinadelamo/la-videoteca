<?php

namespace App\Command;

use App\Entity\Actor;
use App\Entity\Director;
use App\Entity\Film;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ImportCsvCommand extends Command
{

    protected static $defaultName = 'app:import-csv';

    public function __construct($projectDir, EntityManagerInterface $entityManager)
    {
        $this->projectDir = $projectDir;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Update database films')
        ->addArgument('file_name', InputArgument::REQUIRED, 'Name of the file to import. It has to be a csv extension. It has to be placed at uploads folder');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);

        $io = new SymfonyStyle($input, $output);
        $this->entityManager->clear();

        $csvFile = $input->getArgument('file_name');

        $filmsCsv = $this->getCsvRowsAsArrays($csvFile);

        $filmRepository = $this->entityManager->getRepository(Film::class);

        $existingCount = 0;
        $newCount = 0;

        foreach ($filmsCsv as $filmCsv) {

            $batchSize = 20;

            //if film exists continue
            if ($filmRepository->findOneBy(['imdbtitleid' => $filmCsv['imdb_title_id']])) {

                $existingCount++;

                continue;

            }

            $film = new Film();

            $filmAdded = $this->valuesNewFilm($film, $filmCsv);

            $this->entityManager->persist($filmAdded);

            if (($newCount % $batchSize) === 0 && $newCount > 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();

                $io->writeln('It\'s almost done. '.$newCount.' items have been added, for now');
                
            }

            $newCount++;

        }

        $this->entityManager->flush();
        $this->entityManager->clear();

        $io->success("$existingCount existing items. $newCount items haven been added");

        return Command::SUCCESS;

    }

    public function getCsvRowsAsArrays($csvfile)
    {
        $inputFile = $this->projectDir . '/public/uploads/'.$csvfile.'.csv';

        $decoder = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);

        return $decoder->decode(file_get_contents($inputFile), 'csv', [CsvEncoder::DELIMITER_KEY => ',']);
    }

    public function valuesNewFilm($film, $filmCsv)
    {
        $actors = explode(',', $filmCsv['actors']);
        foreach ($actors as $actor) {
            $newActor = new Actor();
            $newActor->setName($actor);
            $film->addActor($newActor);
        }
        $directors = explode(',', $filmCsv['director']);
        foreach ($directors as $director) {
            $newDirector = new Director();
            $newDirector->setName($director);
            $film->addDirector($newDirector);
        }
        $film->setImdbtitleid($filmCsv['imdb_title_id'])
            ->setTitle($filmCsv['title'])
            ->setDatepublished(new \DateTimeImmutable($filmCsv['date_published']))
            ->setGenre($filmCsv['genre'])
            ->setDuration($filmCsv['duration'])
            ->setProducer($filmCsv['production_company']);
        return $film;
    }


}