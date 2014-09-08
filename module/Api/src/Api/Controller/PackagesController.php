<?php

namespace Api\Controller;

use Zend\Http\Request;

use Application\Controller\AbstractActionController;
use Zend\View\Model\JsonModel, Zend\Json\Json as ZendJson;
use Application\Service\Store as StoreService;
use Api\Model\Exception as ApiException;

class PackagesController extends AbstractApiController
{
    public function installAction()
    {
        $packageID = intval($this->params()->fromRoute('id'));
        if (!$packageID) {
            throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
        }

        $packageEntity = $this->getPackageById($packageID);
        if (!$packageEntity) {
            throw new ApiException(null, ApiException::PACKAGE_NOT_FOUND);
        }

        /** @var \Application\Service\Package $packageService  */
        $packageService = $this->getServiceLocator()->get('Application\Service\Package');
        $wordsGroupEntity = $packageService->install($packageEntity, $this->getUser());

        if (!$wordsGroupEntity) {
            throw new ApiException(null, ApiException::PACKAGE_INSTALL_FAILED);
        }

        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $entityManager->refresh($wordsGroupEntity);

        return new JsonModel([
            'success' => true,
            'group' => $this->prepareGroup($wordsGroupEntity),
        ]);
    }
}