<?php

namespace App\Blog\Post\Article\Domain\Repository;

use App\Blog\Post\Article\Domain\Entity\ArticleId;
use App\Blog\Post\Article\Domain\Entity\Comment;

interface CommentRepositoryInterface
{
    /** @return Comment[] */
    public function findByArticleId(ArticleId $articleId): array;

    public function save(Comment $comment): void;
}
