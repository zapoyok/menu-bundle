<?php

namespace Zapoyok\MenuBundle\Model;

interface MenuManagerInterface
{
    /**
     * Creates an empty node instance.
     *
     * @return MenuNodeInterface
     */
    public function create();

    /**
     * Delete a node.
     *
     * @param MenuNodeInterface $node
     */
    public function delete(MenuNodeInterface $node);

    /**
     * Save a node.
     */
    public function save(MenuNodeInterface $node);

    public function findOneById($id);

    public function findOneBy($criteres);
}
