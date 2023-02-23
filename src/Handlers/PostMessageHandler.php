<?php

namespace App\Handlers;

use App\Entity\Post;
use App\Messages\PostMessage;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class PostMessageHandler implements MessageHandlerInterface
{
    private PostRepository $repository;
    private ManagerRegistry $doctrine;

    public function __construct(PostRepository $repository, ManagerRegistry $doctrine)
    {
        $this->repository = $repository;
        $this->doctrine = $doctrine;
    }

    public function __invoke(PostMessage $message)
    {
        $oldPost = $this->repository
            ->findOneBy([
                'title'    => $message->getTitle(),
                'category' => $message->getCategory(),
            ]);


        if ($oldPost) {
            $entityManager = $this->doctrine->getManager();

            $new_log = array_merge($oldPost->getUpdateLog(), [
                date('d-m-y h:i:s')
            ]);

            $oldPost->setUpdateLog($new_log);

            $entityManager->flush();
            return;
        }

        $entityManager = $this->doctrine->getManager();

        $post = new Post();
        $post->setTitle($message->getTitle());
        $post->setImage($message->getImage());
        $post->setDescription($message->getDescription());
        $post->setCategory($message->getCategory());

        $post->setUpdateLog([date('d-m-y h:i:s')]);

        $entityManager->persist($post);

        $entityManager->flush();
    }
}