<?php

namespace RcmAdmin\Form;

use Rcm\Service\LayoutManager;
use Rcm\Service\PageManager;
use Zend\Form\ElementInterface;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Validator\Uri;

class PageForm extends Form implements ElementInterface
{

    /** @var \Rcm\Service\PageManager  */
    protected $pageManager;

    /** @var \Rcm\Service\LayoutManager  */
    protected $layoutManager;

    /**
     * Constructor
     *
     * @param PageManager   $pageManager   Rcm Page Manager
     * @param LayoutManager $layoutManager Rcm Page Manager
     */
    public function __construct(
        PageManager   $pageManager,
        LayoutManager $layoutManager
    ) {
        $this->pageManager = $pageManager;
        $this->layoutManager = $layoutManager;

        parent::__construct();
    }

    /**
     * Initialize the form
     *
     * @return void
     */
    public function init()
    {
        $pageList = $this->pageManager->getPageListByType('t');
        $pageList['blank'] = 'Blank Page (Experts Only)';

        $this->add(
            array(
                'name' => 'url',
                'options' => array(
                    'label' => 'Page Url',
                ),
                'type'  => 'text',
                'validators' => array(
                    new Uri(),
                ),
            )
        );

        $this->add(
            array(
                'name' => 'title',
                'options' => array(
                    'label' => 'Page Title',
                ),
                'type'  => 'text',
            )
        );

        $this->add(
            array(
                'name' => 'page-template',
                'options' => array(
                    'label' => 'Page Template',
                    'value_options' => $pageList,
                ),
                'type'  => 'Zend\Form\Element\Select',
            )
        );


        $this->add(
            array(
                'name' => 'page-layout',
                'options' => array(
                    'label' => 'Page Layout',
                    'layouts' => $this->layoutManager->getThemeLayoutConfig(),
                ),
                'type'  => 'pageLayout',
            )
        );
    }

}