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
use App\Form\UserType;
use App\Manager\ManagerInterface;
use App\Manager\PostManager;
use App\Manager\UserManager;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 * @package App\Controller
 *
 */
class UserController extends BaseController
{

    /**
     * @param ManagerInterfacer $manager
     */
    public function __construct(UserManager  $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param PostRepository $postRepository
     * @return JsonResponse
     * @Route("/users", name="users", methods={"GET"})
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
     * @Route("/users", name="users_add", methods={"POST"})
     */
    public function addPost(Request $request)
    {
        return $this->saveData($request, UserType::class, new User());
    }

    /**
     * @param Post $post
     * @return JsonResponse
     * @Route("/users/{id}", name="users_get", methods={"GET"})
     */
    public function getPost(User $user)
    {
        return $this->response($user);
    }


    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     * @Route("/users/{id}", name="users_put", methods={"PUT"})
     */
    public function updatePost(Request $request, User $user)
    {
        $data = $this->saveData($request, PostType::class, $user);
        return $this->response($data);
    }

    /**
     * @return JsonResponse
     * @Route("/users/{id}", name="users_delete", methods={"DELETE"})
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

}