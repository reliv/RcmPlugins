<?php

namespace RcmAdmin\Form\Element;

use Zend\Form\Element\Radio;

class PageLayout extends Radio
{

    /**
     * Set options for an element. Accepted options are:
     * - label: label to associate with the element
     * - label_attributes: attributes to use when the label is rendered
     * - layouts: list of values and labels for the radio options
     *
     * @param array|\Traversable $options Options to set
     *
     * @return PageLayout
     */
    public function setOptions($options)
    {
        parent::setOptions($options);

        $value_options = array();

        if (isset($this->options['layouts'])) {
            foreach ($this->options['layouts'] as $key => &$layout) {
                $value_options[$key] = $layout['display'];
            }
        }

        $this->setValueOptions($value_options);

        return $this;
    }
}