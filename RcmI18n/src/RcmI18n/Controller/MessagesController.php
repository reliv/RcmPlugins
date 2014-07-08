<?php
 /**
 * MessagesController.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmI18n\Controller
 * @author    Inna Davis <idavis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmI18n\Controller;

use RcmI18n\Entity\Message;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * MessagesController
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmI18n\Controller
 * @author    authorFirstAndLast <author@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class MessagesController extends AbstractRestfulController
{

    /**
     * getList
     *
     * @return mixed|\Zend\Stdlib\ResponseInterface|JsonModel
     */
    public function getList()
    {

        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $locale = $this->params()->fromRoute('locale');
        $messages = $em->getRepository('RcmI18n\Entity\Message')
            ->findBy(array('locale' => $locale));

        $translations = array();
        foreach ($messages as $message) {
            $defaultText = $message->getDefaultText();
            $text = $message->getText();
            $translations[] = [
                'locale' => $locale, 'defaultText' => $defaultText,
                'text' => $text
            ];
        }

        return new JsonModel($translations);
    }

    /**
     * Update translations
     *
     * @param mixed $id   Text that need to be translated
     * @param mixed $data Data
     *
     * @return mixed
     */
    public function update($id, $data)
    {

        if (!$this->rcmUserIsAllowed(
            'translations', 'update', 'RcmI18nTranslations'
        )
        ) {
            $response = $this->getResponse();
            $response->setStatusCode(Response::STATUS_CODE_401);
            $response->setContent($response->renderStatusLine());
            return $response;
        }
        $locale = $this->params()->fromRoute('locale');
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $message = $em->getRepository('RcmI18n\Entity\Message')->findBy(
            array('locale' => $locale, 'defaultText' => $id)
        );
        if ($message instanceof Message) {
            $message->setText($data['text']);
        } else {
            $message = new Message();
            $em->persist($message);
            $message->setLocale($locale);
            $message->setDefaultText($id);
            $message->setText($data['text']);
        }
        $em->flush();

    }
}