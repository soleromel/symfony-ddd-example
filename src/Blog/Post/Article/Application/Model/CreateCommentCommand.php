<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Model;

final class CreateCommentCommand
{
    private string $articleId;
    private string $email;
    private string $message;

    public function __construct(string $articleId, string $email, string $message)
    {
        $this->articleId = $articleId;
        $this->email = $email;
        $this->message = $message;
    }

    public function getArticleId(): string { return $this->articleId; }
    public function getEmail(): string { return $this->email; }
    public function getMessage(): string { return $this->message; }
}
