<?php

namespace RcmAdmin\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

class DisplayErrors extends AbstractHelper
{
    public function __invoke($errors)
    {
        return $this->renderErrors($errors);
    }

    public function renderErrors($errors)
    {
        if (empty($errors)) {
            return null;
        }

        $message = '';

        foreach ($errors as &$error) {
            foreach ($error as $errorCode => &$errorMsg) {
                $message .= $this->errorMapper($errorCode, $errorMsg);
            }

        }

        return $message;
    }

    public function errorMapper($errorCode, $errorMsg) {
        switch ($errorCode) {
        case 'pageName':
        case 'pageExists':
            return '<p class="urlErrorMessage">'.$errorMsg.'</p>'."\n";

        default:
            return '<p class="errorMessage">'.$errorMsg.'</p>'."\n";
        }
    }
}
