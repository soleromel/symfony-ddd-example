<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Controller\Api;

use App\Blog\Post\Article\Application\Model\FindArticleQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/articles/{id}", name="api_article", methods={"GET"})
 */
final class GetArticleController extends AbstractController
{
    use HandleTrait;

    private SerializerInterface $serializer;

    public function __construct(MessageBusInterface $messageBus, SerializerInterface $serializer)
    {
        $this->messageBus = $messageBus;
        $this->serializer = $serializer;
    }

    public function __invoke(string $id): JsonResponse
    {
        $result = $this->handle(new FindArticleQuery($id));

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($result, 'json')
        );
    }
}
