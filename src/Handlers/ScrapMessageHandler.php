<?php

namespace App\Handlers;

use Exception;
use Psr\Log\LoggerInterface;
use App\Messages\PostMessage;
use App\Messages\ScrapMessage;
use Symfony\Component\Panther\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

class ScrapMessageHandler implements MessageHandlerInterface
{
    private MessageBusInterface $bus;
    private LoggerInterface $logger;

    public function __construct(MessageBusInterface $bus, LoggerInterface $logger)
    {
        $this->bus = $bus;
        $this->logger = $logger;
    }

    public function __invoke(ScrapMessage $message)
    {
        try {
            $source = $message->getSource();

            $client = Client::createFirefoxClient();

            $url = $source->getUrl() . $message->getCategory();

            $crawler = $client->request('GET', $url);

            $crawler->filter($source->getWrapperSelector())
                ->children()
                ->each(function (Crawler $c) use ($source, $message) {
                    if (!$c->filter($source->getTitleSelector())->count()) {
                        return;
                    }

                    $title = $c->filter($source->getTitleSelector())->text();
                    $description = $c->filter($source->getDescriptionSelector())->last()->text();
                    $image = $c->filter($source->getImageSelector())->attr('data-lazy-src');

                    $category = $message->getCategory();

                    $postMessage = new PostMessage($title, $description, $image, $category);

                    $this->bus->dispatch($postMessage);
                });
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
            throw new UnrecoverableMessageHandlingException();
        }
    }
}