<?php


namespace App\Manager;


interface ManagerInterface
{
    public function findAll();
    public function save($entity, bool $doFlush = true);
}