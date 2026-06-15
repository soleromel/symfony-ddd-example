<?php

declare(strict_types=1);

namespace App\Blog\User\Application\Event;

use App\Blog\Post\Article\Application\Event\OnPublicationRequestedEvent;
use Symfony\Contracts\EventDispatcher\Event;

final class OnUserVerifiedEvent extends Event
{
    private string $title;
    private string $body;
    private string $author;
    private string $categorySlug;
    private OnPublicationRequestedEvent $originEvent;

    public function __construct(
        string $title,
        string $body,
        string $author,
        string $categorySlug,
        OnPublicationRequestedEvent $originEvent
    ) {
        $this->title = $title;
        $this->body = $body;
        $this->author = $author;
        $this->categorySlug = $categorySlug;
        $this->originEvent = $originEvent;
    }

    public function getTitle(): string { return $this->title; }
    public function getBody(): string { return $this->body; }
    public function getAuthor(): string { return $this->author; }
    public function getCategorySlug(): string { return $this->categorySlug; }
    public function getOriginEvent(): OnPublicationRequestedEvent { return $this->originEvent; }
}
