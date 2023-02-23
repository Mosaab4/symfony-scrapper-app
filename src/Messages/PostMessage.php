<?php

namespace App\Messages;

class PostMessage
{
    private string $title;
    private string $description;
    private string $image;
    private string $category;

    public function __construct(
        string $title, string $description,
        string $image, string $category
    )
    {
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
        $this->category = $category;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCategory(): string
    {
        return $this->category;
    }
}