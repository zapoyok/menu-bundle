<?php

namespace Zapoyok\MenuBundle\Model;

interface MenuNodeManagerInterface
{
    public function getSibling(MenuNodeInterface $node = null);
}
