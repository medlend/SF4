<?php


namespace App\Manager;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class AbstractManager implements ManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * AbstractManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param string $className
     */
    public function __construct(EntityManagerInterface $entityManager, string $className)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository($className);
    }

    public function save($entity, bool $doFlush = true)
    {
        $this->entityManager->persist($entity);

        if ($doFlush) {
            $this->entityManager->flush();
        }

        return $entity;
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }
}