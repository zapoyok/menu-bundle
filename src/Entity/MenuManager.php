<?php

namespace Zapoyok\MenuBundle\Entity;

use Doctrine\ORM\EntityManager;
use Zapoyok\MenuBundle\Model\MenuManagerInterface;
use Zapoyok\MenuBundle\Model\MenuNodeInterface;

class MenuManager implements MenuManagerInterface
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function create()
    {
        return new Menu();
    }

    public function findOneById($id)
    {
        return $this->getRepository()->findOneBy(['id' => $id]);
    }

    public function findOneBy($criteres)
    {
        return $this->getRepository()->findOneBy($criteres);
    }

    public function delete(MenuNodeInterface $message)
    {
        return;
    }

    public function getRepository()
    {
        return $this->em->getRepository('ZapoyokMenuBundle:Menu');
    }

    public function save(MenuNodeInterface $entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}
