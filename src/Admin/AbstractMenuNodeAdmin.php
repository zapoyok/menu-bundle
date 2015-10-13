<?php

namespace Zapoyok\MenuBundle\Admin;

use Zapoyok\MenuBundle\Model\MenuNodeInterface;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Common base admin for Menu and MenuNode.
 */
abstract class AbstractMenuNodeAdmin extends Admin
{
    protected $contentAwareFactory;
    protected $menuRoot;
    protected $translationDomain = 'menu';

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('nameWithLevel')
            ->add('level')
            ->add('uri', 'text')
            ->add('route', 'text')

            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('form.menunode.tab.General')
                ->with('form.menunode.general')
                    ->add('name', 'text', [
                        /* @Ignore */
                        'label' => $this->trans('form.menunode.name', [], 'admin'),
                        /* @Ignore */
                        'help' => $this->trans('help.menunode.name', [], 'admin'),
                    ])
                    ->add('label', 'text', [
                        /* @Ignore */
                        'label' => $this->trans('form.menunode.label', [], 'admin'),
                        /* @Ignore */
                        'help' => $this->trans('help.menunode.label', [], 'admin'),
                    ])
                ->end()
            ->end()
        ;
    }

    public function getExportFormats()
    {
        return [];
    }

//     public function getContentAwareFactory()
//     {
//         return $this->contentAwareFactory;
//     }

//     public function setContentAwareFactory(ContentAwareFactory $contentAwareFactory)
//     {
//         $this->contentAwareFactory = $contentAwareFactory;
//     }

//     public function setContentRoot($contentRoot)
//     {
//         $this->contentRoot = $contentRoot;
//     }

//     public function setMenuRoot($menuRoot)
//     {
//         $this->menuRoot = $menuRoot;
//     }

//     public function setContentTreeBlock($contentTreeBlock)
//     {
//         $this->contentTreeBlock = $contentTreeBlock;
//     }

    public function toString($object)
    {
        if ($object instanceof MenuNodeInterface && $object->getLabel()) {
            return $object->getLabel();
        }
    }
}
