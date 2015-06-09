<?php


namespace RcmMessage\Model;

use RcmMessage\Entity\UserMessage;

require_once(__DIR__ . '/../autoload.php');

/**
 * Class MessageManagerTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmMessage\Model
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class MessageManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testSetGet()
    {
        $now = new \DateTime();

        $testCase = [
            'id' => 123,
            'userId' => 4444,
            'dateViewed' => $now,
            'message' => 'TEST MESSAGE',
            'dateViewed' => $now,
            'dateViewedString' => $now->format(\DateTime::ISO8601),
        ];

        $unit = new UserMessage(321);

        $unit->setId($testCase['id']);
        $this->assertEquals($testCase['id'], $unit->getId());

        $this->assertEquals(321, $unit->getUserId());
        $unit->setUserId($testCase['userId']);
        $this->assertEquals($testCase['userId'], $unit->getUserId());

        $unit->setMessage($testCase['message']);
        $this->assertEquals($testCase['message'], $unit->getMessage());

        $unit->setViewed();
        $this->assertTrue($unit->hasViewed());

        $dateViewed = $unit->getDateViewed();
        $this->assertInstanceOf('\DateTime', $dateViewed);
        $unit->setDateViewed(new \DateTime());
        // still the same
        $this->assertInstanceOf('\DateTime', $dateViewed);
        $this->assertEquals(
            $dateViewed->format(\DateTime::ISO8601),
            $unit->getDateViewedString()
        );

        $unit->setViewed(false);
        $this->assertFalse($unit->hasViewed());

        $this->assertNull($unit->getDateViewedString());

        $unit->setDateViewedString($testCase['dateViewedString']);

        $this->assertEquals(
            $testCase['dateViewedString'],
            $unit->getDateViewedString()
        );


        $this->assertTrue(is_array($unit->toArray()));

        $this->assertEquals($testCase['id'], $unit->toArray()['id']);
    }
}
