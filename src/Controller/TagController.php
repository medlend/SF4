<?php
/**
 * Created by PhpStorm.
 * User: hicham benkachoud
 * Date: 02/01/2020
 * Time: 22:44
 */

namespace App\Controller;


use App\Entity\Post;
use App\Entity\Tag;
use App\Form\PostType;
use App\Form\TagType;
use App\Manager\AbstractManager;
use App\Manager\ManagerInterface;
use App\Manager\PostManager;
use App\Manager\TagManager;
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
class TagController extends  BaseController
{

    /**
     * TagController constructor.
     * @param TagManager $tagManager
     */
    public function __construct(TagManager $tagManager)
    {
        $this->manager = $tagManager;
    }

    /**
     * @return JsonResponse
     * @Route("/tags", name="tags", methods={"GET"})
     */
    public function getPosts()
    {
        return $this->response($this->manager->findAll());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     * @Route("/tags", name="tags_add", methods={"POST"})
     */
    public function addPost(Request $request)
    {
        return $this->saveData($request, TagType::class, new Tag());
    }

    /**
     * @param Tag $tag
     * @return JsonResponse
     * @Route("/tags/{id}", name="tags_get", methods={"GET"})
     */
    public function getPost(Tag $tag)
    {
        return $this->response($tag);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return JsonResponse
     * @Route("/tags/{id}", name="tags_put", methods={"PUT"})
     */
    public function updatePost(Request $request, Post $post)
    {
        return $this->saveData($request, PostType::class, $post);
    }

    /**
     * @return JsonResponse
     * @Route("/tags/{id}", name="tags_delete", methods={"DELETE"})
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