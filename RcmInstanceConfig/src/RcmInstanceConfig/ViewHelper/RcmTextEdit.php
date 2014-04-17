<?php

namespace RcmInstanceConfig\ViewHelper;

use Rcm\View\Helper\AbstractRcmEdit;

class RcmTextEdit extends AbstractRcmEdit
{
    protected $htmlPurifier;

    function __construct(\HTMLPurifier $htmlPurifier)
    {
        $this->htmlPurifier = $htmlPurifier;
    }

    public function __invoke(
        $name,
        $defaultContent = null,
        $elementType = 'div',
        $elementAttributes = array()
    )
    {
        if (array_key_exists($name, $this->view->instanceConfig)) {
            $defaultContent = $this->view->instanceConfig[$name];
        }

        /**
         * Strip out any web script to prevent XSS
         */
        $defaultContent = $this->htmlPurifier->purify($defaultContent);

        return $this->buildEdit(
            $name, $defaultContent, $elementType, $elementAttributes, false
        );
    }
} 