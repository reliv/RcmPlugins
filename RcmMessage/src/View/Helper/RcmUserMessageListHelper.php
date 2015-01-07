<?php


namespace RcmMessage\View\Helper;

use RcmMessage\Repository\UserMessage;
use RcmMessage\Entity\Message as MessageEntity;
use RcmUser\Service\RcmUserService;
use Zend\View\Helper\AbstractHelper;

/**
 * Class RcmUserMessageListHelper
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
class RcmUserMessageListHelper extends AbstractHelper
{
    /**
     * @var string
     */
    protected $currentUserId = null;

    /**
     * @var RcmUserService
     */
    protected $rcmUserService;

    /**
     * @var UserMessage
     */
    protected $userMessageRepo;

    /**
     * @param UserMessage $userMessageRepo
     * @param RcmUserService $rcmUserService
     */
    public function __construct(
        UserMessage $userMessageRepo,
        RcmUserService $rcmUserService
    ) {
        $this->userMessageRepo = $userMessageRepo;
        $this->rcmUserService = $rcmUserService;

        $currentUser = $this->rcmUserService->getCurrentUser(null);

        if (!empty($currentUser)) {
            $this->currentUserId = $currentUser->getId();
        }
    }

    /**
     * __invoke
     *
     * @param null|string $userId
     * @param null|string $source
     * @param null|string $level
     * @param null|bool   $hasViewed
     *
     * @return string
     */
    public function __invoke(
        $source = null,
        $level = null,
        $hasViewed = null,
        $userId = null
    ) {
        if (empty($userId)) {
            $userId = $this->currentUserId;
        }

        if (empty($userId)) {
            return '';
        }

        $messages = $this->userMessageRepo->getMessages(
            $userId,
            $source,
            $level,
            $hasViewed
        );

        return $this->render(
            $messages
        );
    }

    /**
     * render
     *
     * @param $messages
     *
     * @return string
     */
    protected function render(
        $messages
    ) {
        $messageHtml = '';

        foreach ($messages as $userMessage) {
            $message = $userMessage->getMessage();
            $cssName = $this->getCssName($message->getLevel());
            $messageBody = $message->getMessage() ;
            $messageHtml .= '
            <div class="alert' . $cssName . ' alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss-message="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
              <span>
              '. $messageBody . '
              </span>
            </div>
            ';
        }

        //$flashMessenger = $this->flashMessenger();
        //
        //$flashMessenger->addMessage('DEFAULT Message');
        //$flashMessenger->addInfoMessage('INFO Message <a href="/">CLICK</a>');
        //$flashMessenger->addWarningMessage('WARN Message');
        //$flashMessenger->addErrorMessage('ERR Message');
        //$flashMessenger->addSuccessMessage('SUCCESS Message');
        //
        ////$flashMessenger->clearMessages();
        //
        //echo $flashMessenger->render('error',   array('alert', 'alert-dismissable', 'alert-danger'));
        //echo $flashMessenger->render('warning', array('alert', 'alert-dismissable', 'alert-warning'));
        //echo $flashMessenger->render('info',    array('alert', 'alert-dismissable', 'alert-info'));
        //echo $flashMessenger->render('default', array('alert', 'alert-dismissable', 'alert-info'));
        //echo $flashMessenger->render('success', array('alert', 'alert-dismissable', 'alert-success'));
        //
        //echo '<pre>';

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