<?php

namespace Zapoyok\MenuBundle\Provider;

use Zapoyok\MenuBundle\Model\MenuManagerInterface;
use Knp\Menu\FactoryInterface;
use Knp\Menu\Provider\MenuProviderInterface;

class MenuProvider implements MenuProviderInterface
{
    /**
     * @var FactoryInterface
     */
    protected $factory = null;

    /**
     * @var MenuManagerInterface
     */
    protected $menuManager = null;

    /**
     * @param FactoryInterface $factory the menu factory used to create the menu item
     */
    public function __construct(FactoryInterface $factory, MenuManagerInterface $menuManager)
    {
        $this->factory     = $factory;
        $this->menuManager = $menuManager;
    }

    /**
     * Retrieves a menu by its name.
     *
     * @param string $name
     * @param array  $options
     *
     * @throws \InvalidArgumentException if the menu does not exists
     * @return \Knp\Menu\ItemInterface
     *
     */
    public function get($name, array $options = [])
    {
        $menu     = $this->menuManager->findOneBy(['name' => $name]);
        $menuItem = $this->factory->createFromNode($menu);
        if (empty($menuItem)) {
            throw new \InvalidArgumentException("Menu at '$name' is misconfigured (f.e. the route might be incorrect) and could therefore not be instanciated");
        }
        //$menuItem->setCurrentUri($this->request->getRequestUri());
        return $menuItem;
    }

    /**
     * Checks whether a menu exists in this provider.
     *
     * @param string $name
     * @param array  $options
     *
     * @return bool
     */
    public function has($name, array $options = [])
    {
        return (null !== $this->menuManager->findOneBy(['name' => $name]));
    }
}
