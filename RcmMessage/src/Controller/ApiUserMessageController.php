<?php


namespace RcmMessage\Controller;

use Rcm\Http\Response;
use Rcm\View\Model\ApiJsonModel;
use RcmMessage\Entity\Message;
use RcmMessage\Entity\UserMessage;
use Zend\Mvc\Controller\AbstractRestfulController;


/**
 * Class ApiUserMessageController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmMessage\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ApiUserMessageController extends AbstractRestfulController
{
    /**
     * getEntityManager
     *
     * @return \Doctrine\ORM\EntityManagerInterface
     */
    protected function getEntityManager()
    {
        return $this->serviceLocator->get('Doctrine\ORM\EntityManager');
    }

    /**
     * getUserMessageRepository
     *
     * @return \RcmMessage\Repository\UserMessage
     */
    protected function getUserMessageRepository()
    {
        return $this->getEntityManager()->getRepository(
            '\RcmMessage\Entity\UserMessage'
        );
    }

    /**
     * getCurrentUser
     *
     * @return \RcmUser\User\Entity\User
     */
    protected function getCurrentUser()
    {
        return $this->rcmUserGetCurrentUser();
    }

    /**
     * canAccess
     *
     * @return bool
     */
    protected function canAccess()
    {
        $userId = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'userId',
                null
            );

        $currentUser = $this->getCurrentUser();

        if (empty($currentUser)) {
            return false;
        }

        if ($currentUser->getId() == $userId) {
            return true;
        }

        //ACCESS CHECK if not current user
        return $this->rcmIsAllowed(
            'sites',
            'admin'
        );
    }

    /**
     * getList
     *
     * @return ApiJsonModel|\Zend\Stdlib\ResponseInterface
     */
    public function getList()
    {
        if (!$this->canAccess()) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return $this->getResponse();
        }

        $userMessageRepo = $this->getUserMessageRepository();
        $userId = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'userId',
                null
            );

        $messages = $userMessageRepo->findBy(['userId' => $userId]);

        return new ApiJsonModel($messages);
    }

    /**
     * get
     *
     * @param mixed $id
     *
     * @return ApiJsonModel|\Zend\Stdlib\ResponseInterface
     */
    public function get($id)
    {
        if (!$this->canAccess()) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return $this->getResponse();
        }

        $userMessageRepo = $this->getUserMessageRepository();
        $userId = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'userId',
                null
            );

        $message = $userMessageRepo->findOneBy(
            [
                'id' => $id,
                'userId' => $userId
            ]
        );

        if(empty($message)){
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
            return new ApiJsonModel($message, 404, 'Not Found');
        }

        return new ApiJsonModel($message);
    }

    /**
     * create
     *
     * @param mixed $data
     *
     * @return ApiJsonModel|\Zend\Stdlib\ResponseInterface
     */
    public function create($data)
    {
        if (!$this->rcmIsAllowed(
            'sites',
            'admin'
        )) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return $this->getResponse();
        }

        $userId = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'userId',
                null
            );

        $newUserMessage = new UserMessage($userId);

        $newUserMessage->populate($data, ['id', 'dateViewed', 'message']);

        // @todo Should we force creation????
        $newMessage = new Message();

        $newMessage->populate($data['message'], ['id', 'dateCreated']);

        $newUserMessage->setMessage($newMessage);

        $entityManager = $this->getEntityManager();

        try{
            $entityManager->persist($newUserMessage);
            $entityManager->flush();
        } catch(\Exception $e){
            return new ApiJsonModel(null, 1, $e->getMessage());
        }

        return new ApiJsonModel($newUserMessage);
    }

    /**
     * update
     *
     * @param string $id
     * @param mixed  $data
     *
     * @return ApiJsonModel|\Zend\Stdlib\ResponseInterface
     */
    public function update($id, $data)
    {
        if (!$this->canAccess()) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return $this->getResponse();
        }

        $userMessageRepo = $this->getUserMessageRepository();
        $userId = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'userId',
                null
            );

        $message = $userMessageRepo->findOneBy(
            [
                'id' => $id,
                'userId' => $userId
            ]
        );

        if(empty($message)){
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
            return new ApiJsonModel($message, 404, 'Not Found');
        }

        $message->populate($data, ['id', 'dateViewed', 'message']);

        $entityManager = $this->getEntityManager();

        try{
            $entityManager->persist($message);
            $entityManager->flush();
        } catch(\Exception $e){
            return new ApiJsonModel(null, 1, $e->getMessage());
        }

        return new ApiJsonModel($message);
    }
}