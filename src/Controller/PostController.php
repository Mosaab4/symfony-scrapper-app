<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends AbstractController
{
    private PostRepository $repository;
    private PaginatorInterface $paginator;

    public function __construct(PostRepository $repository, PaginatorInterface $paginator)
    {
        $this->repository = $repository;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/posts")
     */
    public function index(Request $request)
    {
        if(!$this->getUser()){
            return new RedirectResponse("login");
        }

        $posts = $this->paginator->paginate(
        // Doctrine Query, not results
            $this->repository->all(),
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            9
        );

        return $this->render('posts.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/delete/{id}")
     */
    public function destroy($id)
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        $post = $this->repository->find($id);

        if (!$post){
            throw new NotFoundHttpException("NOT Found");
        }

        $this->repository->remove($post,true);

        return new RedirectResponse("/posts");
    }
}