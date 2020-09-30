<?php
/**
 * Created by PhpStorm.
 * User: hicham benkachoud
 * Date: 02/01/2020
 * Time: 22:44
 */

namespace App\Controller;


use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Manager\PostManager;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

/**
 * Class PostController
 * @package App\Controller
 *
 */
class PostController extends BaseController
{

    /**
     * @param PostManager $postManager
     */
    public function __construct(PostManager  $postManager)
    {
        $this->manager = $postManager;
    }

    /**
     * @param PostRepository $postRepository
     * @return JsonResponse
     * @Route("/posts", name="posts", methods={"GET"})
     */
    public function getPosts(PostRepository $postRepository)
    {
        return $this->response($this->manager->findAll());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     * @Route("/posts", name="posts_add", methods={"POST"})
     */
    public function addPost(EntityManagerInterface $entityManager,  Request $request)
    {
        $user = $entityManager->find(User::class, 1);
        $post = (new Post())->setAuthor($user);

         return $this->saveData($request, PostType::class,$post);

    }

    /**
     * @param Post $post
     * @return JsonResponse
     * @Route("/posts/{id}", name="posts_get", methods={"GET"})
     */
    public function getPost(Post $post)
    {
        return $this->response($post);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param PostRepository $postRepository
     * @param $id
     * @return JsonResponse
     * @Route("/posts/{id}", name="posts_put", methods={"PUT"})
     */

    /**
     * @param Request $request
     * @param Post $post
     * @return JsonResponse
     * @Route("/posts/{id}", name="posts_put", methods={"PUT"})
     */
    public function updatePost(Request $request, Post $post)
    {
        $data = $this->saveData($request, PostType::class, $post);
        return $this->response($data);
    }


    /**
     * @param PostRepository $postRepository
     * @param $id
     * @return JsonResponse
     * @Route("/posts/{id}", name="posts_delete", methods={"DELETE"})
     */
    public function deletePost(EntityManagerInterface $entityManager, Post $post)
    {

        $entityManager->remove($post);
        $entityManager->flush();
        $data = [
            'status' => 200,
            'errors' => "Post deleted successfully",
        ];
        return $this->response($data);
    }

//@ParamConverter("post", options={"mapping": {"postSlug": "id"}})
//@Entity("post", expr="repository.findByName(postName)")
//https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html#doctrine-converter


    /**
     * @Route("/comment/{name}/new", methods="POST", name="comment_new")
     *
     *
     *
     *
     */
    public function commentNew(Request $request, Post $post )
    {

        dump($post);die('aaa');

//        $comment = new Comment();
//        $comment->setAuthor($this->getUser());
//        $post->addComment($comment);

//        $form = $this->createForm(CommentType::class, $comment);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($comment);
//            $em->flush();
//
//            // When an event is dispatched, Symfony notifies it to all the listeners
//            // and subscribers registered to it. Listeners can modify the information
//            // passed in the event and they can even modify the execution flow, so
//            // there's no guarantee that the rest of this controller will be executed.
//            // See https://symfony.com/doc/current/components/event_dispatcher.html
//            $eventDispatcher->dispatch(new CommentCreatedEvent($comment));
//
//            return $this->redirectToRoute('blog_post', ['slug' => $post->getSlug()]);
//        }
//
//        return $this->render('blog/comment_form_error.html.twig', [
//            'post' => $post,
//            'form' => $form->createView(),
//        ]);
    }



}