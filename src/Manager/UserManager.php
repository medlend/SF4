<?php


namespace App\Manager;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class UserManager extends AbstractManager
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * PostManager constructor.
     * @param EntityManagerInterface $em
     * @param RequestStack $request
     */
    public function __construct(EntityManagerInterface $em, RequestStack $request)
    {
        $className = 'App\Entity\User';
        parent::__construct($em, $className);
        $this->requestStack = $request;
    }

//    public function findAll()
//    {
//        $page = $this->getPage();
//        return $this->repository->findList($page);
//    }

    private function getPage()
    {
        return intval($this->requestStack->getCurrentRequest()->query->get('page',1));
    }

}