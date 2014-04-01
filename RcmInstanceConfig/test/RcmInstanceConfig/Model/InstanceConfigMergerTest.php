<?php


namespace RcmInstanceConfigTest\Entity;

require_once __DIR__ . '/../../../../../Rcm/test/Base/BaseTestCase.php';

use RcmInstanceConfig\Model\InstanceConfigMerger;
use RcmTest\Base\BaseTestCase;

class InstanceConfigMergerTest extends BaseTestCase
{
    /**
     * @var \RcmInstanceConfig\Model\InstanceConfigMerger
     */
    protected $merger;

    public function setUp()
    {
        $this->addModule('RcmInstanceConfig');
        parent::setUp();
        $this->merger = new InstanceConfigMerger();
    }

    public function testMergeConfigArrays()
    {
        $merged = $this->merger->mergeConfigArrays(
            array(
                'keyedArray' => array('a' => 1, 'c' => 3),
                'nonKeyedArray' => array('a', 'b', 'c'),
                'keyedArrayInDefaultOnly' => array('x' => 'y'),
                'overwrite' => 'original',
                'nonOverWritten' => 'original'
            ),
            array(
                'keyedArray' => array('b' => 2),
                'nonKeyedArray' => array('d'),
                'keyedArrayInChangesOnly' => array('z' => 'x'),
                'overwrite' => 'new',
                'inChangesOnly' => 'new'
            )
        );
        $this->assertEquals(
            $merged,
            array(
                'keyedArray' => array('a' => 1, 'b' => 2, 'c' => 3),
                'nonKeyedArray' => array('d'),
                'keyedArrayInDefaultOnly' => array('x' => 'y'),
                'keyedArrayInChangesOnly' => array('z' => 'x'),
                'overwrite' => 'new',
                'inChangesOnly' => 'new',
                'nonOverWritten' => 'original'
            )
        );

        $merged = $this->merger->mergeConfigArrays(
            array('one' => 1),
            null
        );
        $this->assertEquals($merged, array('one' => 1));

        $merged = $this->merger->mergeConfigArrays(
            null,
            array('one' => 1)
        );
        $this->assertEquals($merged, array('one' => 1));
    }
} 