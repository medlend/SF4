<?php
/**
 * Created by PhpStorm.
 * User: hicham benkachoud
 * Date: 02/01/2020
 * Time: 22:44
 */

namespace App\Controller;


use App\Entity\Post;
use App\Form\PostType;
use App\Manager\ManagerInterface;
use App\Manager\PostManager;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;


class BaseController extends AbstractController
{
    /**
     * @var ManagerInterface
     */
    protected $manager;

    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param $status
     * @param array $headers
     * @return JsonResponse
     */
    protected function response($data, $status = 200, $headers = [])
    {
        return new JsonResponse($data, $status, $headers);
    }

    protected function transformJsonBody(\Symfony\Component\HttpFoundation\Request $request)
    {
        $data = \json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }


    protected function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }


    protected function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new HttpException(400, 'Invalid JSON body!');
        }
        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }

    protected function createValidationErrorResponse(FormInterface $form)
    {
        $errors = $this->getErrorsFromForm($form);

        $data = [
            'type' => 'validation_error',
            'title' => 'There was a validation error',
            'errors' => $errors
        ];

        $response = new JsonResponse($data, 400);
        $response->headers->set('Content-Type', 'application/problem+json');

        return $response;
    }

    protected function saveData(Request $request,string $formType, $entity)
    {
        $form = $this->createForm($formType, $entity);

        $this->processForm($request, $form);
        if (!$form->isValid()) {
            return $this->createValidationErrorResponse($form);
        }

        $post = $this->manager->save($entity);

        return new JsonResponse($post, 200, []);
    }
}