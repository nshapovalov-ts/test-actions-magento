<?php

/**
 * Tradesquare_AjaxResponse
 *
 * @copyright   Copyright (c) 2022 TRADESQUARE PTY LTD (www.tradesquare.com.au)
 * @author      Dmitriy Fionov <dmitriy@tradesquare.com.au>
 */

declare(strict_types=1);

namespace Tradesquare\AjaxResponse\Model;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Phrase;

/**
 * Class ResponseManagement
 */



class ResponseManagement
{
    /**
     * Constructor
     *
     * @param \Magento\Framework\Controller\ResultFactory $resultFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly ManagerInterface $messageManager

    ) {
    }

    /**
     * Send Success Response
     *
     * @param array $data
     * @param \Magento\Framework\Phrase|null $successMessage
     * @param bool $updateMessageManager
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function success(



        array $data = [],
        ?Phrase $successMessage = null,
        bool $updateMessageManager = false
    ): ResultInterface {
        $responseData = [
            'is_success' => true,
            'response_data' => $data,
            'success_message' => $successMessage
        ];

        if ($successMessage && $updateMessageManager) {
            $this->updateSuccessMessageManager($successMessage);
        }

        return $this->getJsonResult($responseData);
    }


    public function fail(Phrase $errorMessage, bool $updateMessageManager = false, array $data = []): ResultInterface
    {



        $responseData = [
            'is_success' => false,
            'response_data' => $data,
            'error_message' => $errorMessage
        ];

        if ($updateMessageManager) {
            $this->updateErrorMessageManager($errorMessage);
        }

        return $this->getJsonResult($responseData);
    }

    /**
     * Get Json Result
     *
     * @param array $data
     * @return \Magento\Framework\Controller\ResultInterface
     */
    private function getJsonResult(array $data): ResultInterface
    {
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($data);

        return $resultJson;
    }


    private function updateErrorMessageManager(?Phrase $message): void
    {



        $this->messageManager->addErrorMessage($message);
    }

    /**
     * Add Success Message to MessageManager
     *
     * @param \Magento\Framework\Phrase|null $message
     */
    private function updateSuccessMessageManager(?Phrase $message): void
    {
        $this->messageManager->addSuccessMessage($message);
    }
}
