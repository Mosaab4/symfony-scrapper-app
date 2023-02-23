<?php

namespace App\Messages;

use App\Scraper\Contracts\SourceInterface;

class ScrapMessage
{
    private SourceInterface $source;
    private string $category;

    public function __construct(SourceInterface $source, string $category)
    {
        $this->source = $source;
        $this->category = $category;
    }

    public function getSource(): SourceInterface
    {
        return $this->source;
    }

    public function getCategory(): string
    {
        return $this->category;
    }
}