<?php

namespace App\Command;

use App\Entity\Film;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
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
        $this->setDescription('Update database films');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filmsCsv = $this->getCsvRowsAsArrays();

        $filmRepository = $this->entityManager->getRepository(Film::class);

        $existingCount = 0;
        $newCount = 0;

        foreach ($filmsCsv as $filmCsv) {

            if ($existingFilm = $filmRepository->findOneBy(['imdbtitleid' => $filmCsv['imdb_title_id']])) {

                $this->updateExistingFilm($existingFilm, $filmCsv);

                $existingCount++;

                continue;

            }

            $this->createNewFilm($filmCsv);

            $newCount++;

        }

        $this->entityManager->flush();


        $io = new SymfonyStyle($input, $output);

        $io->progressStart(100);

        $io->success("$existingCount existing items have been updated. $newCount items haven been added");

        return Command::SUCCESS;

    }

    public function getCsvRowsAsArrays()
    {
        $inputFile = $this->projectDir . '/public/uploads/IMDB_movies.csv';

        $decoder = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);

        return $decoder->decode(file_get_contents($inputFile), 'csv', [CsvEncoder::DELIMITER_KEY => ',']);
    }

    public function updateExistingFilm($existingFilm, $filmCsv)
    {
        $filmUpdated = $this->valuesNewFilm($existingFilm, $filmCsv);

        $this->entityManager->persist($filmUpdated);
    }

    public function createNewFilm($filmCsv)
    {
        $film = new Film();
        $filmAdded = $this->valuesNewFilm($film, $filmCsv);

        $this->entityManager->persist($filmAdded);
    }

    public function valuesNewFilm($film,$filmCsv)
    {
        $film->setImdbtitleid($filmCsv['imdb_title_id'])
            ->setTitle($filmCsv['title'])
            ->setDatepublished(new \DateTimeImmutable($filmCsv['date_published']))
            ->setGenre($filmCsv['genre'])
            ->setDuration($filmCsv['duration'])
            ->setProducer($filmCsv['production_company']);
        return $film;
    }


}