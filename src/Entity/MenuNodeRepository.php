<?php

namespace Zapoyok\MenuBundle\Entity;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Zapoyok\MenuBundle\Model\MenuNodeInterface;

class MenuNodeRepository extends NestedTreeRepository
{
    /**
     * Determine if two nodes are sibling in a tree structure.
     *
     * @param MenuNodeInterface $node
     * @param MenuNodeInterface $candidateSibling
     *
     * @return bool
     */
    public function isSibling(MenuNodeInterface $node, MenuNodeInterface $candidateSibling)
    {
        $siblings = $this->getSibling($node);
        if (!count($siblings)) {
            return false;
        }

        foreach ($siblings as $s) {
            if ($s->getId() == $candidateSibling->getId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param MenuNodeInterface $node
     *
     * @return NULL
     */
    public function getSibling(MenuNodeInterface $node = null)
    {
        if (is_null($node)) {
            return;
        }
        $parent = $node->getParent();

        return $this->getChildren($parent, true);
    }
}
