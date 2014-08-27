<?php

namespace Api\Controller;

use Zend\Http\Request;

use Application\Controller\AbstractActionController;
use Zend\View\Model\JsonModel, Zend\Json\Json as ZendJson;
use Application\Model\Entity\Word as WordEntity;
use Application\Model\Entity\WordsGroup as WordsGroupEntity;
use Application\Model\Entity\Language as LanguageEntity;
use Application\Service\Store as StoreService;
use Application\Model\Entity\Download as DownloadEntity;
use Api\Model\Exception as ApiException;

class DownloadsController extends AbstractApiController
{
    public function contentAction()
    {
        /** @var \Application\Service\Download $downloadService  */
        $downloadService = $this->getServiceLocator()->get('Application\Service\Download');

        $userEntity = $this->getUser();
        $downloadEntities = $downloadService->getDownloads($userEntity);

        return new JsonModel([
            'downloads' => $this->prepareDownloads($downloadEntities),
        ]);
    }

    public function dropAction()
    {
        $downloadID = intval($this->params()->fromRoute('id'));
        if (!$downloadID) {
            throw new ApiException('Загрузка не найдена');
        }

        $downloadEntity = $this->getDownloadById($downloadID);
        if (!$downloadEntity) {
            throw new ApiException('Загрузка не найдена #2');
        }

        /** @var \Application\Service\Download $downloadService  */
        $downloadService = $this->getServiceLocator()->get('Application\Service\Download');
        $downloadService->dropDownload($downloadEntity);

        return new JsonModel([
            'success' => true,
        ]);
    }

    public function addAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getPostParams($request);
            if (!$data) {
                throw new ApiException('Параметры для добавления загрузки не найдены');
            }

            /** @var \Doctrine\ORM\EntityManager $entityManager  */
            $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $downloadForm = new \Api\Form\Download($entityManager);
            $downloadEntity = new DownloadEntity();

            $downloadForm->bind($downloadEntity);
            $downloadForm->setData($data);

            if ($downloadForm->isValid()) {

                $fkStrategy = intval($downloadForm->get('fkStrategy')->getValue());
                $fkWordsGroup = intval($downloadForm->get('fkWordsGroup')->getValue());
                if (!$fkStrategy || !$fkWordsGroup) {
                    throw new ApiException('Параметры для добавления загрузки не найдены');
                }

                $strategyEntity = $this->getStrategyById($fkStrategy);
                $wordsGroupEntity = $this->getGroupById($fkWordsGroup);
                if (!$strategyEntity || !$wordsGroupEntity) {
                    throw new ApiException('Параметры для добавления загрузки не найдены');
                }

                $entityManager->beginTransaction();

                /** @var DownloadEntity $newDownloadEntity */
                $newDownloadEntity = $downloadForm->getData();
                $newDownloadEntity->setFkUser($this->getUser());
                $newDownloadEntity->setCreatedAt(new \DateTime());
                $newDownloadEntity->setStatus(DownloadEntity::STATUS_INITIAL);

                $legacy = [
                    'group' => $this->prepareGroup($wordsGroupEntity),
                    'strategy' => $this->prepareStrategy($strategyEntity),
                ];
                $newDownloadEntity->setLegacy($legacy);

                /** @var \Application\Service\Download $downloadService  */
                $downloadService = $this->getServiceLocator()->get('Application\Service\Download');
                $newDownloadEntity = $downloadService->saveDownloadEntity($newDownloadEntity);
                if ($newDownloadEntity) {
                    $entityManager->commit();
                    return new JsonModel([
                        'download' => $this->prepareDownload($newDownloadEntity),
                        'success' => true,
                    ]);
                } else {
                    $entityManager->rollback();
                    throw new ApiException('Произошла логическая ошибка');
                }
            } else {
                // 'error' => $wordsGroupForm->getMessages(),
                throw new ApiException('Заполните все обязательные поля');
            }
        } else {
            throw new ApiException('Параметры для добавления загрузки не найдены');
        }
    }
}