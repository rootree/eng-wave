<?php

namespace Application\Controller;

use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController as ZendAbstractActionController;
use Zend\View\Model\JsonModel;
use Application\Model\Entity\Word as WordEntity;
use Application\Model\Entity\WordsGroup as WordsGroupEntity;
use Application\Model\Entity\Strategy as StrategyEntity;
use Application\Model\Entity\Download as DownloadEntity;

use Application\Service\Store as StoreService;

class DownloadController extends AbstractActionController
{

    public function indexAction()
    {
        $downloadID = intval($this->params()->fromRoute('id'));
        if (!$downloadID) {
            return $this->notFoundAction ();
        }

        $downloadEntity = $this->getDownloadById($downloadID);
        if (!$downloadEntity) {
            return $this->notFoundAction ();
        }

        return $this->sendDownload($downloadEntity);
    }

    public function hashAction()
    {
        $downloadHash = $this->params()->fromRoute('hash');
        if (!$downloadHash) {
            return $this->notFoundAction ();
        }

        /** @var \Application\Service\Download $downloadService  */
        $downloadService = $this->getServiceLocator()->get('Application\Service\Download');
        $downloadEntity = $downloadService->getDownloadByHash($downloadHash);
        if (!$downloadEntity) {
            return $this->notFoundAction ();
        }

        return $this->sendDownload($downloadEntity);
    }

    private function sendDownload(DownloadEntity $downloadEntity)
    {
        /** @var StoreService $storeService  */
        $storeService = $this->getServiceLocator()->get('Application\Service\Store');
        $path = $storeService->getSpeakPath($downloadEntity->getId(), StoreService::TYPE_DOWNLOAD);

        if (!file_exists($path)) {
            return $this->notFoundAction ();
        }

        $response = new \Zend\Http\Response\Stream();
        $response->setStream(fopen($path, 'r'));
        $response->setStatusCode(200);

        $downloadName = $downloadEntity->getFkWordsGroup()->getTitle() . $storeService->getExtension(StoreService::TYPE_DOWNLOAD);

        $headers = new \Zend\Http\Headers();
        $headers
            ->addHeaderLine('Content-Type','application/octet-stream', true)
            ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $downloadName . '"')
            ->addHeaderLine('Content-Length', filesize($path))
            ->addHeaderLine('Content-Transfer-Encoding','binary');

        $response->setHeaders($headers);

        return $response;
    }
}