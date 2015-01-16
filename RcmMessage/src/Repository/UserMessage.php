<?php

namespace RcmMessage\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * Class UserMessage
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmMessage
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class UserMessage extends EntityRepository
{
    /**
     * getUserMessages
     *
     * @param string      $userId     User Id to display message form
     * @param string|null $source     Source identifier or null to ignore
     * @param string|null $level      Level (see UserMessage entity for static values) or null to ignore
     * @param string|null $hasViewed  If user has viewed the message or null to ignore
     *
     * @return ArrayCollection
     */
    public function getMessages(
        $userId,
        $source = null,
        $level = null,
        $hasViewed = null
    ) {

        $level = $this->getIntNullValue($level);
        $hasViewed = $this->getBoolNullValue($hasViewed);

        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('userMessage');
        $queryBuilder->from('\RcmMessage\Entity\UserMessage', 'userMessage');
        $queryBuilder->join('userMessage.message', 'message');
        $queryBuilder->where('userMessage.userId = :userId');
        $queryBuilder->setParameter('userId', $userId);

        if(!empty($level)) {
            $queryBuilder->andWhere('message.level = :level');
            $queryBuilder->setParameter('level', $level);
        }

        if(!empty($source)) {
            $queryBuilder->andWhere('message.source = :source');
            $queryBuilder->setParameter('source', $source);
        }

        if($hasViewed === true) {
            $queryBuilder->andWhere($queryBuilder->expr()->isNotNull('userMessage.dateViewed'));
        }

        if($hasViewed === false) {
            $queryBuilder->andWhere($queryBuilder->expr()->isNull('userMessage.dateViewed'));
        }

        $queryBuilder->orderBy('message.level', 'DESC');
        $queryBuilder->orderBy('message.dateCreated', 'ASC');

        try {
            return $queryBuilder->getQuery()->getResult();
        } catch (NoResultException $e) {
            return [];
        }
    }

    /**
     * getIntNullValue
     *
     * @param $var
     *
     * @return int|null
     */
    protected function getIntNullValue($var)
    {
        if(null === $var) {
            return null;
        }

        return (int) $var;
    }

    /**
     * getBoolNullValue
     *
     * @param $var
     *
     * @return bool|null
     */
    protected function getBoolNullValue($var)
    {

        if(null === $var || is_bool($var)){
            return $var;
        }

        if('' === $var){
            return null;
        }

        return (bool) (int) $var;
    }

    /**
     * getUserMessages
     *
     * @param string $userId
     * @param string $source
     * @param string $level
     *
     * @return ArrayCollection
     */
    public function getMessage(
        $userId,
        $messageId,
        $source = null,
        $level = null
    ) {
        // @todo
    }

    /**
     * getUserMessages
     *
     * @param string $userId
     * @param string $source
     * @param string $level
     *
     * @return ArrayCollection
     */
    public function hasMessages(
        $userId,
        $source = null,
        $level = null
    ){
        // @todo
    }
}