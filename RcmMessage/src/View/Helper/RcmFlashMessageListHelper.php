<?php


namespace RcmMessage\View\Helper;

use RcmMessage\Repository\UserMessage;
use RcmMessage\Entity\Message as MessageEntity;
use RcmUser\Service\RcmUserService;
use Zend\View\Helper\AbstractHelper;

/**
 * Class RcmFlashMessageListHelper
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmMessage\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmFlashMessageListHelper extends AbstractHelper
{
    /**
     * __invoke
     *
     * @param bool $clear
     *
     * @return string
     */
    public function __invoke(
        $clear = true
    ) {

        return $this->render(
            $clear
        );
    }

    /**
     * render
     *
     * @param bool $clear
     *
     * @return string
     */
    protected function render(
        $clear = true
    ) {
        $view = $this->getView();

        $flashMessenger = $view->flashMessenger();
        //$flashMessenger->addMessage('DEFAULT Message');
        //$flashMessenger->addInfoMessage('INFO Message <a href="/">CLICK</a>');
        //$flashMessenger->addWarningMessage('WARN Message');
        //$flashMessenger->addErrorMessage('ERR Message');
        //$flashMessenger->addSuccessMessage('SUCCESS Message');
        $messageHtml = '<link href="/modules/rcm-message/css/styles.css" media="screen,print" rel="stylesheet" type="text/css">';

        $messageHtml .= '<div class="rcmMessage flashMessageList">';

        $messageHtml .=  $flashMessenger->render('error',   array('alert', 'alert-dismissable', 'alert-danger'));
        $messageHtml .=  $flashMessenger->render('warning', array('alert', 'alert-dismissable', 'alert-warning'));
        $messageHtml .=  $flashMessenger->render('info',    array('alert', 'alert-dismissable', 'alert-info'));
        $messageHtml .=  $flashMessenger->render('default', array('alert', 'alert-dismissable', 'alert-info'));
        $messageHtml .=  $flashMessenger->render('success', array('alert', 'alert-dismissable', 'alert-success'));

        $messageHtml .= '</div>';

        if($clear) {
            $flashMessenger->clearMessages();
        }

        return $messageHtml;
    }

    /**
     * getCssName
     *
     * @param $level
     *
     * @return string
     */
    protected function getCssName($level)
    {
        if (empty($level)) {
            return ' alert-info';
        }
        $cssMap = [
            MessageEntity::LEVEL_CRITICAL => ' alert-danger',
            MessageEntity::LEVEL_ERROR => ' alert-danger',
            MessageEntity::LEVEL_WARNING => ' alert-warning',
            MessageEntity::LEVEL_INFO => ' alert-info',
            MessageEntity::LEVEL_SUCCESS => ' alert-success',
        ];

        if (isset($cssMap[$level])) {
            return $cssMap[$level];
        }

        return ' alert-info';
    }
}