<?php

namespace RcmAngularJs\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class AngularTemplateParser
 *
 * Limited Angular Template parsing
 * - If this is useful, might be extended with a library to do more detailed parsing
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAngularJs\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AngularTemplateParser extends AbstractHelper
{
    /**
     * __invoke
     *
     * @param string $template
     * @param array  $values
     *
     * @return string
     */
    public function __invoke($template, $values)
    {
        return $this->parse($template, $values);
    }

    /**
     * parse
     *
     * @param string $template String of parsable html or text
     * @param array  $values 'fieldName' => 'fieldValue'
     *
     * @return string
     */
    protected function parse($template, $values)
    {
        foreach ($values as $name => $value) {
            $template
                = str_replace('{{' . $name . '}}', $value, $template);
        }

        return $template;
    }
}
