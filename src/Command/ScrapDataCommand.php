<?php

namespace App\Command;

use App\Sources\HighLoad;
use App\Messages\ScrapMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class ScrapDataCommand extends Command
{
    protected static $defaultName = 'scrap:data';

    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus, string $name = null)
    {
        parent::__construct($name);

        $this->bus = $bus;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $question = new ChoiceQuestion(
            'Please Select the Category You want to scrap',
            array_keys($this->getCategories()),
            0
        );

        $question->setErrorMessage('Category %s is invalid.');

        $category = $helper->ask($input, $output, $question);

        $output->writeln('You have just selected: ' . $category);
        $output->writeln("Scraping will start now");

        $selected_category = $this->getCategories()[$category];

        $message = new ScrapMessage(new HighLoad(), $selected_category);

        $this->bus->dispatch($message);

        return Command::SUCCESS;
    }

    private function getCategories(): array
    {
        return [
            'News'               => 'novosti',
            'Frontend'           => 'front-end',
            'Backend'            => 'back-end',
            'Blockchain'         => 'blokchejn-i-kripta',
            'Rust tutorial'      => 'uchebnik-po-rust',
            'Mobile Application' => 'mobile-app',
            'Books'              => 'knigi',
            'Collections'        => 'podborki',
            'Stories'            => 'istorii',
            'Solutions'          => 'resheniya',
            'Theory'             => 'teoriya',
            'Iron'               => 'zhelezo',
            'Interview'          => 'intervyu',
            'Special Projects'   => 'spetsproekty',
        ];
    }
}