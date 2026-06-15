<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Service;

use App\Blog\Post\Article\Application\Model\CreateArticleCommand;
use App\Blog\Post\Article\Domain\Entity\Article;
use App\Blog\Post\Article\Domain\Entity\ArticleId;
use App\Blog\Post\Article\Domain\Entity\AuthorId;
use App\Blog\Post\Article\Domain\Repository\ArticleRepositoryInterface;
use App\Blog\Post\Shared\Domain\Entity\ValueObject\CategoryId;
use Ramsey\Uuid\Uuid;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateArticleHandler implements MessageHandlerInterface
{
    private ArticleRepositoryInterface $articleRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        EventDispatcherInterface $eventDispatcher,
    ) {
        $this->articleRepository = $articleRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(CreateArticleCommand $createArticleCommand): string
    {
        $articleId = new ArticleId(Uuid::uuid4()->toString());

        $article = Article::create(
            $articleId,
            $createArticleCommand->getTitle(),
            $createArticleCommand->getBody(),
            new AuthorId($createArticleCommand->getAuthor()),
            new CategoryId($createArticleCommand->getCategory())
        );

        $this->articleRepository->save($article);

        foreach ($article->pullDomainEvents() as $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }

        return $articleId->getValue();
    }
}
