<?php

namespace RcmAdmin\Form;

use Rcm\Service\PageManager;
use Zend\Form\Form;
use Zend\Validator\Uri;

class NewPageForm extends Form
{

    /**
     * Constructor
     *
     * @param PageManager $pageManager Rcm Page Manager
     */
    public function __construct(PageManager $pageManager)
    {
        $pageList = $pageManager->getPageListByType('t');

        parent::__construct();

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

    }
}