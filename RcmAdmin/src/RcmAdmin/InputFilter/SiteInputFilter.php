<?php


/**
 * Class SiteInputFilter
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class SiteInputFilter extends \Zend\InputFilter\InputFilter
{

    protected $filterConfig
        = array(

            'username' => array(
                'name' => 'username',
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 100,
                        ),
                    ),
                    // Help protect from XSS
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => "/^[a-zA-Z0-9-_@'.]+$/",
                            //'pattern' => "/[<>]/",
                            'messageTemplates' => array(
                                \Zend\Validator\Regex::NOT_MATCH => "Username can only contain letters, numbers and charactors: . - _ @ '."
                            )
                        ),
                    ),
                ),
            ),
            'password' => array(
                'name' => 'password',
                'required' => true,
                'filters' => array(),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 6,
                            'max' => 100,
                        ),
                    ),
                    /*
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '^(?=.*\d)(?=.*[a-zA-Z])$'
                        ),
                    ),
                    */
                ),
            ),
            'email' => array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StripTags'),
                    // Help protect from XSS
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    array('name' => 'Zend\Validator\EmailAddress'),
                ),
            ),
            'name' => array(
                'name' => 'name',
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StripTags'),
                    // Help protect from XSS
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
                'validators' => array(),
            ),
        );

    public function __construct()
    {

        $factory = $this->getFactory();

        foreach($this->filterConfig as $field => $config){
            $this->add(
                $factory->createInput(
                    $config
                )
            );
        }
    }
} 