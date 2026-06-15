<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Controller\Api;

use App\Blog\Post\Article\Application\Model\CreateCommentCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/comments/", name="api_comment_post", methods={"POST"})
 */
final class PostCommentController extends AbstractController
{
    use HandleTrait;

    private SerializerInterface $serializer;

    public function __construct(MessageBusInterface $messageBus, SerializerInterface $serializer)
    {
        $this->messageBus = $messageBus;
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $parameters = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $command = new CreateCommentCommand(
            $parameters['article_id'],
            $parameters['email'],
            $parameters['message']
        );

        $comment = $this->handle($command);

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($comment, 'json'),
            Response::HTTP_CREATED
        );
    }
}
