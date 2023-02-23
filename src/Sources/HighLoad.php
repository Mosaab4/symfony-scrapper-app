<?php

namespace App\Sources;

use App\Scraper\Contracts\SourceInterface;

class HighLoad implements SourceInterface
{

    public function getUrl(): string
    {
        return 'https://highload.today/category/';
    }

    public function getWrapperSelector(): string
    {
        return '#main > div > div.col.sidebar-center';
    }


    public function getTitleSelector(): string
    {
        return 'div a:nth-child(3)';
    }

    public function getDescriptionSelector(): string
    {
        return "div > p";
    }

    public function getImageSelector(): string
    {
        return "div a div.lenta-image img";
    }
}