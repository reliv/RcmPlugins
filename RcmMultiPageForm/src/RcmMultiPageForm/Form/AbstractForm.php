<?php
/**
 * Contains code that all our forms share
 */

namespace RcmMuliPageForm\Form;


use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods;

class AbstractForm extends Form
{
    public function __construct(
        $pluginName, InputFilter $inputFilter, $post, $options = array()
    )
    {
        parent::__construct($pluginName . 'Form', $options);

        $this->setAttribute('method', 'post');
        $this->setHydrator(new ClassMethods(false));
        $this->setInputFilter($inputFilter);
        $this->setData($post);

        //Helps prevent this form's posts from affecting other plugins
        $this->add(
            array(
                'name' => 'rcmPluginName',
                'attributes' => array(
                    'type' => 'hidden',
                    'value' => $pluginName
                )
            )
        );

        $this->add(
            array(
                'type' => 'Zend\Form\Element\Csrf',
                'name' => 'csrf'
            )
        );

        $this->add(
            array(
                'name' => 'continue',
                'attributes' => array(
                    'type' => 'submit',
                    'value' => 'Send'
                )
            )
        );
    }
} 