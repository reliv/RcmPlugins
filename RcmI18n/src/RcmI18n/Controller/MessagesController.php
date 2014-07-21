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
        $defaultMessages
            = $messages = $em->getRepository('RcmI18n\Entity\Message')
            ->findBy(array('locale' => 'en_US'));
        $localeMessages = $em->getRepository('RcmI18n\Entity\Message')
            ->findBy(array('locale' => $locale));

        $translations = array();
        foreach ($defaultMessages as $defaultMessage) {
            $defaultText = $defaultMessage->getDefaultText();
            $text = null;
            foreach ($localeMessages as $localeMessage) {
                if ($localeMessage->getDefaultText() == $defaultText) {
                    $text = $localeMessage->getText();
                    break;
                }
            }
            $translations[] = [
                'locale' => $locale,
                'defaultText' => utf8_decode($defaultText),
                'text' => utf8_decode($text)
            ];
        }
        return new JsonModel($translations);
    }

    /**
     * Update translations
     *
     * @param mixed $defaultText Text that need to be translated
     * @param mixed $data        Data
     *
     * @return mixed|\Zend\Stdlib\ResponseInterface|JsonModel
     * @throws \Exception
     */
    public function update($defaultText, $data)
    {
        if (!$this->rcmUserIsAllowed(
            'translations',
            'update',
            'RcmI18nTranslations'
        )
        ) {
            $response = $this->getResponse();
            $response->setStatusCode(Response::STATUS_CODE_401);
            $response->setContent($response->renderStatusLine());
            return $response;
        }

        $locale = $this->params()->fromRoute('locale');
        $cleanDefaultText = $this->rcmHtmlPurify($defaultText);
        $cleanLocal = $this->rcmHtmlPurify($locale);
        $cleanText = $this->rcmHtmlPurify($data['text']);

        if(
           $cleanLocal != $locale
        ||
            $cleanDefaultText != $defaultText ||
            $cleanText != $data['text']
        ){

            $response = $this->getResponse();
            $response->setStatusCode(Response::STATUS_CODE_400);
            $response->setContent(
                $response->renderStatusLine() .' - Data contains unacceptable HTML.'
            );
            return $response;
        }

        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $message = $em->getRepository('RcmI18n\Entity\Message')->findOneBy(
            array('locale' => $cleanLocal, 'defaultText' => $cleanDefaultText)
        );

        if ($message instanceof Message) {
            $message->setText($cleanText);
        } else {
            $message = new Message();
            $message->setLocale($cleanLocal);
            $message->setDefaultText($cleanDefaultText);
            $message->setText($cleanText);

            $em->persist($message);
        }
        $em->flush();

        return new JsonModel();
    }
}
