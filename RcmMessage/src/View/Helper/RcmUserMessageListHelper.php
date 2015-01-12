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
            $userId,
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
        $userId,
        $messages
    ) {
        $messageHtml
            = '<script type="text/javascript" src="/modules/rcm-message/js/rcm-message.js"></script>';
        $messageHtml .= '<div class="rcmMessage userMessageList" data-ng-controller="rcmMessageList">';

        if (count($messages) < 1) {
            // @todo Translate this
            $messageHtml .= '<div class="userMessageListEmpty">No Messages</div>';
        }

        foreach ($messages as $userMessage) {
            /** @var \RcmMessage\Entity\Message $message */
            $message = $userMessage->getMessage();
            $cssName = $this->getCssName($message->getLevel());
            $messageSubject = $message->getSubject();
            $messageBody = $message->getMessage();
            $messageHtml
                .= '
            <div class="alert' . $cssName . '" ng-hide="hiddenUserMessageIds[\''
                . $userId . ':' . $userMessage->getId() . '\']" role="alert">
              <button type="button" class="close" ng-click="dismissUserMessage('
                . $userId . ', ' . $userMessage->getId() . ')" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
              <span class="subject">
              ' . $messageSubject . ':
              </span>
              <span class="body">
              ' . $messageBody . '
              </span>
            </div>
            ';
        }
        $messageHtml .= '</div>';

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