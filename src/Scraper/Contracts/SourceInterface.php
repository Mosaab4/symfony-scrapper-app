<?php

namespace App\Scraper\Contracts;

interface SourceInterface
{
    public function getUrl(): string;


    public function getWrapperSelector(): string;

    public function getTitleSelector(): string;

    public function getDescriptionSelector(): string;

    public function getImageSelector(): string;
}