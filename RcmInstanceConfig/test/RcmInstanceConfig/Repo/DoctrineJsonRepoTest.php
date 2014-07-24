<?php


namespace RcmInstanceConfigTest\Entity;

require_once __DIR__ . '/../../../../../Rcm/test/Base/DoctrineTestCase.php';

use RcmInstanceConfig\Entity\InstanceConfig;
use RcmInstanceConfig\Repo\DoctrineJsonRepo;
use RcmTest\Base\DoctrineTestCase;

class DoctrineJsonRepoTest extends DoctrineTestCase
{
    /**
     * @var \RcmInstanceConfig\Repo\DoctrineJsonRepo
     */
    protected $repo;

    protected $doctrineRepo;

    public function setUp()
    {
        $this->addModule('RcmInstanceConfig');
        parent::setup();
        $this->repo = new DoctrineJsonRepo($this->entityManager);
        $this->doctrineRepo = $this->entityManager
            ->getRepository(
                '\Rcm\Entity\InstanceConfig'
            );
    }

    /**
     * @covers \RcmInstanceConfig\Repo\DoctrineJsonRepo
     */
    public function testSelect()
    {
        $id = 1;
        $configData = array('numbers' => array(1, 1, 1));

        $entity = new InstanceConfig();
        $entity->setInstanceId($id);
        $entity->setConfig($configData);
        $this->entityManager->persist($entity);
        $this->entityManager->flush($entity);


    }

    /**
     * @covers \RcmInstanceConfig\Repo\DoctrineJsonRepo
     */
    public function testInsert()
    {
        $id = 2;
        $configData = array('numbers' => array(2, 2, 2));

        $this->repo->insert($id, $configData);


        $entity = $this->doctrineRepo->findOneBy(array('instanceId' => $id));

        $this->assertEquals(
            $configData,
            $entity->getConfig()
        );
    }

    /**
     * @covers \RcmInstanceConfig\Repo\DoctrineJsonRepo
     */
    public function testDelete()
    {
        $id = 3;
        $configData = array('numbers' => array(3, 3, 3));

        $entity = new InstanceConfig();
        $entity->setInstanceId($id);
        $entity->setConfig($configData);
        $this->entityManager->persist($entity);
        $this->entityManager->flush($entity);

        $this->repo->delete($id);

        $entity = $this->doctrineRepo->findOneBy(array('instanceId' => $id));

        $this->assertEquals(
            $entity,
            null
        );
    }
} 