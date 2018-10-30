<?php

namespace VirginMoneyGivingAPI\Exceptions;

abstract class AbstractConnectorException extends \Exception implements ExceptionInterface
{
    protected $responseCode;

    protected $errorCode;

    protected $errorMessage;

    protected $messageDetails;

    protected $inputDetails;

    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null, $responseBody = null)
    {
        parent::__construct($message, $code, $previous);

        // Because the VMG API returns 403 as XML and 404 as JSON we need to handle both.
        if (!empty($responseBody)) {
            // If we have XML set the messaging manually.
            if (strpos($responseBody, '<?xml') !== false) {
                $this->setResponseCode(403);
                $this->setErrorCode('000.00.002');
                $this->setErrorMessage('You are not authorised to access this API.');
            } else {
                // If we have JSON set from the API.
                $responseContents = json_decode($responseBody);
                if ($responseContents->errors) {
                    $vmgErrors = $responseContents->errors[0];
                    $this->setResponseCode($vmgErrors->responseCode);
                    $this->setErrorCode($vmgErrors->errorCode);
                    $this->setErrorMessage($vmgErrors->errorMessage);
                    $this->setMessageDetails($vmgErrors->messageDetails);
                    $this->setInputDetails($vmgErrors->inputDetails);
                }
            }
        }
    }

    public function setResponseCode($responseCode)
    {
        $this->responseCode = ($responseCode) ? $responseCode : null;
    }

    public function getResponseCode()
    {
        return $this->responseCode;
    }

    public function setErrorCode(string $errorCode)
    {
        $this->errorCode = $errorCode;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function setErrorMessage(string $errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function setMessageDetails(array $messageDetails)
    {
        $this->messageDetails = $messageDetails;
    }

    public function getMessageDetails()
    {
        return $this->messageDetails;
    }

    public function setInputDetails($inputDetails)
    {
        $this->inputDetails = ($inputDetails) ? $inputDetails : null;
    }

    public function getInputDetails()
    {
        return $this->inputDetails;
    }
}
