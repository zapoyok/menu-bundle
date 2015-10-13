<?php

namespace Zapoyok\MenuBundle\Admin;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Form\FormMapper;

class MenuAdmin extends AbstractMenuNodeAdmin
{
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $subject = $this->getSubject();
        $isNew   = $subject->getId() ? false : true;
    }

    public function getNewInstance()
    {
        /** @var $new Menu */
        $new = parent::getNewInstance();
        $new->setParentDocument(null);

        return $new;
    }

    /**
     * (non-PHPdoc).
     *
     * @see \Sonata\AdminBundle\Admin\Admin::configureSideMenu()
     */
    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, ['edit'])) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;

        $id = $admin->getRequest()->get('id');

        $menu->addChild('menu.menu', [
            'uri'                => $admin->generateUrl('edit', ['id' => $id]),
            /* @Ignore */'label' => $this->trans('zapoyok_menu.menu.menu'),
        ]);

        $menu->addChild('menu.node', [
            'uri'                => $admin->generateUrl('zapoyok_menu.admin.menunode.list', ['id' => $id]),
            /* @Ignore */'label' => $this->trans('zapoyok_menu.menu.nodes'),
        ]);
    }
}
