<?php

namespace Zapoyok\MenuBundle\Entity;

use Zapoyok\MenuBundle\Model\MenuManagerInterface;
use Zapoyok\MenuBundle\Model\MenuNodeInterface;
use Zapoyok\MenuBundle\Model\MenuNodeManagerInterface;

class MenuNodeManager extends MenuManager implements MenuManagerInterface, MenuNodeManagerInterface
{
    public function create()
    {
        return new MenuNode();
    }

    public function isSibling(MenuNodeInterface $node, MenuNodeInterface $candidateSibling)
    {
        return $this->getRepository()->isSibling($node, $candidateSibling);
    }

    public function getNodesHierarchyQueryBuilder(MenuNodeInterface $node = null)
    {
        return $this->getRepository()->getNodesHierarchyQueryBuilder($node);
    }

    public function getSibling(MenuNodeInterface $node = null)
    {
        return $this->getRepository()->getSibling($node);
    }

    public function persistAsPrevSiblingOf($object, $sibling)
    {
        return $this->getRepository()->persistAsPrevSiblingOf($object, $sibling);
    }

    public function persistAsNextSiblingOf($object, $sibling)
    {
        return $this->getRepository()->persistAsNextSiblingOf($object, $sibling);
    }

    public function getRepository()
    {
        return $this->em->getRepository('ZapoyokMenuBundle:MenuNode');
    }
}
