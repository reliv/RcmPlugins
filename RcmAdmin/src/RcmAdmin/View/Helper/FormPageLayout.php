<?php

namespace RcmAdmin\View\Helper;

use RcmAdmin\Form\Element\PageLayout;
use Zend\Form\ElementInterface;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\View\Helper\FormMultiCheckbox;

class FormPageLayout extends FormMultiCheckbox
{
    /**
     * Return input type
     *
     * @return string
     */
    protected function getInputType()
    {
        return 'radio';
    }

    /**
     * Get element name
     *
     * @param ElementInterface $element Zend Form Element Interface
     *
     * @return string
     */
    protected static function getName(ElementInterface $element)
    {
        return $element->getName();
    }

    /**
     * Over writes render method to put layout array into the correct format for
     * Zend Form
     *
     * @param ElementInterface $element Zend Element Interface
     *
     * @return string|void
     * @throws InvalidArgumentException
     */
    public function render(ElementInterface $element)
    {
        if (!$element instanceof PageLayout) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s requires that the element is of type PageLayout',
                    __METHOD__
                )
            );
        }

        $options = $element->getOptions();
        $element->setLabelOption('disable_html_escape', true);

        $inputOptions = array();

        foreach ($options['layouts'] as $key => &$layout) {
            $inputOptions[$key]
                = '<span class="pageLayoutLabel">'
                . '    <img src="'.$layout['screenShot'].'" />'
                . '    '.$layout['display']
                . '    <span class="pageLayoutImageOverlay"></span>'
                . '</span>';
        }


        $name = static::getName($element);

        $options = $inputOptions;

        $attributes         = $element->getAttributes();
        $attributes['name'] = $name;
        $attributes['type'] = $this->getInputType();
        $selectedOptions    = (array) $element->getValue();

        $rendered = $this->renderOptions(
            $element,
            $options,
            $selectedOptions,
            $attributes
        );

        // Render hidden element
        $useHiddenElement
            = method_exists($element, 'useHiddenElement') && $element->useHiddenElement()
            ? $element->useHiddenElement()
            : $this->useHiddenElement;

        if ($useHiddenElement) {
            $rendered = $this->renderHiddenElement($element, $attributes) . $rendered;
        }

        return $rendered;
    }
}
