<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\Service;

use App\Blog\Post\Category\Application\Model\CreateCategoryCommand;
use App\Blog\Post\Category\Domain\Entity\Category;
use App\Blog\Post\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Blog\Post\Shared\Domain\Entity\ValueObject\CategoryId;
use Psr\EventDispatcher\EventDispatcherInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateCategoryService implements MessageHandlerInterface
{
    private EventDispatcherInterface $eventDispatcher;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        EventDispatcherInterface $eventDispatcher,
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->categoryRepository = $categoryRepository;
    }

    public function __invoke(CreateCategoryCommand $createCategoryCommand): CategoryId
    {
        $categoryId = new CategoryId(Uuid::uuid4()->toString());

        $category = Category::create(
            $categoryId,
            $createCategoryCommand->getName(),
            $createCategoryCommand->getSlug()
        );

        $this->categoryRepository->save($category);

        foreach ($category->pullDomainEvents() as $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }

        return $categoryId;
    }
}
