<?php

namespace RcmI18n\Controller;

use Rcm\View\Model\ApiJsonModel;
use RcmI18n\Entity\Message;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * Class ApiAdminBuildMessagesController
 *
 * ApiAdminBuildMessagesController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmI18n\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ApiAdminBuildMessagesController extends AbstractRestfulController
{
    protected $defaultLocale = 'en_US';

    /**
     * getConfig
     *
     * @return array|object
     */
    protected function getConfig()
    {
        $serviceLocator = $this->getServiceLocator();
        return $serviceLocator->get('config');
    }

    /**
     * getDefaultLocale
     *
     * @return array
     */
    protected function getDefaultLocale()
    {
        $config = $this->getConfig();
        return $config['RcmI18n']['defaultLocale'];
    }

    /**
     * getList - Builds new list from config as needed
     *
     * @return ApiJsonModel|\Zend\Stdlib\ResponseInterface
     */
    public function update($id, $data)
    {
        if (!$this->rcmIsAllowed('translations', 'update')) {
            $response = $this->getResponse();
            $response->setStatusCode(Response::STATUS_CODE_401);
            $response->setContent($response->renderStatusLine());
            return $response;
        }

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        if ($id == 'default') {
            $defaultLocale = $this->getDefaultLocale();
        } else {
            $defaultLocale = $id;
        }

        $config = $this->getConfig();

        $translationGroups = $config['RcmI18n']['translations'];

        $updatedTranslations = [];
        $unchangedTranslations = [];

        foreach ($translationGroups as $translations) {

            foreach ($translations as $newTranslation) {

                try {
                    $defaultMessage = $em->getRepository('RcmI18n\Entity\Message')
                        ->findOneBy(
                            [
                                'locale' => $defaultLocale,
                                'defaultText' => $newTranslation['defaultText']
                            ]
                        );
                } catch (\Exception $e) {
                    $defaultMessage = null;

                    // @todo better error checking, return errors with API result
                }

                if (empty($defaultMessage)) {

                    $newMessage = new Message();
                    $newMessage->setLocale($defaultLocale);
                    $newMessage->setDefaultText($newTranslation['defaultText']);
                    $newMessage->setText($newTranslation['text']);

                    $em->persist($newMessage);
                    $updatedTranslations[] = $newMessage;
                } else {
                    $unchangedTranslations[] = $defaultMessage;
                }
            }
        }

        try {
            $em->flush();
        } catch (\Exception $e) {
            return new ApiJsonModel([], 1, $e->getMessage());
        }

        $result = [
            'updated' => $updatedTranslations,
            'unchanged' => $unchangedTranslations,
        ];

        return new ApiJsonModel(
            $result,
            0,
            "Messages built successfully for {$defaultLocale}"
        );
    }
}
