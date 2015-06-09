<?php


namespace RcmMessage\Entity;

require_once(__DIR__ . '/../autoload.php');

/**
 * Class MessageTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmMessage\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{
        public function testSetGet()
        {
            $now = new \DateTime();
            $testCase = [
                'id' => 123,
                'level' => 2,
                'subject' => 'TEST SUBJECT',
                'message' => 'TEST MESSAGE',
                'source' => 'TEST_SOURCE',
                'dateCreated' => $now,
                'dateCreatedString' => $now->format(\DateTime::ISO8601),
            ];
            $unit = new Message();

            $unit->setId($testCase['id']);
            $this->assertEquals($testCase['id'], $unit->getId());

            $unit->setLevel(0);
            $this->assertEquals(Message::LEVEL_DEFAULT, $unit->getLevel());

            $unit->setLevel($testCase['level']);
            $this->assertEquals($testCase['level'], $unit->getLevel());

            $unit->setSubject($testCase['subject']);
            $this->assertEquals($testCase['subject'], $unit->getSubject());

            $unit->setMessage($testCase['message']);
            $this->assertEquals($testCase['message'], $unit->getMessage());

            $unit->setSource('');
            $this->assertNull($unit->getSource());
            $unit->setSource($testCase['source']);
            $this->assertEquals($testCase['source'], $unit->getSource());

            $unit->setDateCreated(null);
            $this->assertNull($unit->getDateCreatedString());

            $unit->setDateCreated($testCase['dateCreated']);
            $this->assertEquals($testCase['dateCreated'], $unit->getDateCreated());

            $unit->setDateCreatedString($testCase['dateCreatedString']);
            $this->assertEquals($testCase['dateCreatedString'], $unit->getDateCreatedString());

            $this->assertEquals($testCase['id'], $unit->toArray()['id']);

            $this->assertTrue(is_array($unit->toArray()));
        }
}
