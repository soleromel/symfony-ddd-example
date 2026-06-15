<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Service;

use App\Blog\Post\Article\Application\Model\FindArticleQuery;
use App\Blog\Post\Article\Domain\Entity\Article;
use App\Blog\Post\Article\Domain\Entity\ArticleId;
use App\Blog\Post\Article\Domain\Repository\ArticleRepositoryInterface;
use App\Blog\Post\Article\Domain\Repository\CommentRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class ArticleFinderHandler implements MessageHandlerInterface
{
    private ArticleRepositoryInterface $articleRepository;
    private CommentRepositoryInterface $commentRepository;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        CommentRepositoryInterface $commentRepository,
    ) {
        $this->articleRepository = $articleRepository;
        $this->commentRepository = $commentRepository;
    }

    public function __invoke(FindArticleQuery $findArticleQuery): array
    {
        $articleId = new ArticleId($findArticleQuery->getArticleId());

        $article = $this->articleRepository->find($articleId->getValue());
        $comments = $this->commentRepository->findByArticleId($articleId);

        return [
            'article' => $article,
            'comments' => $comments,
        ];
    }
}
