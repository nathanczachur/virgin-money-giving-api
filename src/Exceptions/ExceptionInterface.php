<?php

namespace VirginMoneyGivingAPI\Exceptions;

interface ExceptionInterface
{
    public function setResponseCode($responseCode);

    public function getResponseCode();

    public function setErrorCode(string $errorCode);

    public function getErrorCode();

    public function setErrorMessage(string $errorMessage);

    public function getErrorMessage();

    public function setMessageDetails(array $messageDetails);

    public function getMessageDetails();

    public function setInputDetails($inputDetails);

    public function getInputDetails();
}
