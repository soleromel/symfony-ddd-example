<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Model;

final class CreateArticleCommand
{
    private string $title;
    private string $body;
    private string $author;
    private string $category;

    public function __construct(string $title, string $body, string $author, string $category)
    {
        $this->title = $title;
        $this->body = $body;
        $this->author = $author;
        $this->category = $category;
    }

    public function getTitle(): string { return $this->title; }
    public function getBody(): string { return $this->body; }
    public function getAuthor(): string { return $this->author; }
    public function getCategory(): string { return $this->category; }
}
