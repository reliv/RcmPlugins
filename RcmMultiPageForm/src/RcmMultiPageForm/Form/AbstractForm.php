<?php
/**
 * Contains code that all our forms share
 */

namespace RcmMuliPageForm\Form;


use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class AbstractForm extends Form
{
    public function __construct(
        $pluginName, $post, $options = array()
    )
    {
        parent::__construct($pluginName . 'Form', $options);

        $this->setAttribute('method', 'post');
        $this->setHydrator(new ClassMethods(false));

        if (!empty($post)) {
            $this->setData($post);
        }

        //Prevent this form's posts from effecting other plugins
        $this->add(
            array(
                'name' => 'rcmPluginName',
                'attributes' => array(
                    'type' => 'hidden',
                    'value' => $pluginName
                )
            )
        );

//        $this->add(
//            array(
//                'type' => 'Zend\Form\Element\Csrf',
//                'name' => 'csrf'
//            )
//        );
    }
} 