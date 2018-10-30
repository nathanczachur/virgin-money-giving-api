<?php

namespace Tests\Connectors;

use GuzzleHttp\Psr7\Response;
use Tests\VmgTestBase;
use VirginMoneyGivingAPI\Connectors\FundraiserVmgConnector;
use VirginMoneyGivingAPI\Exceptions\ConnectorException;
use VirginMoneyGivingAPI\Models\Fundraiser;
use VirginMoneyGivingAPI\Models\Page;
use VirginMoneyGivingAPI\Responses\FundraiserCreateResponse;
use VirginMoneyGivingAPI\Responses\FundraiserSearchResponse;
use VirginMoneyGivingAPI\Responses\PageCreateResponse;

class FundraserVmgConnectorTest extends VmgTestBase
{
    /**
     * @throws \VirginMoneyGivingAPI\Exceptions\ConnectorException
     */
    public function testSearchNotFound()
    {
        $stream = file_get_contents('tests/Mocks/FundraiserSearch404.txt');
        $response = new Response(404, ['Content-Type' => 'application/json'], $stream);
        $this->setMockClient([$response]);

        $fundraiserConnector = new FundraiserVmgConnector('API_KEY', $this->getGuzzleClient(), true);

        $this->expectException(ConnectorException::class);
        $response = $fundraiserConnector->search('Test', 'User');

        try {
            $response = $fundraiserConnector->search('Test', 'User');
        } catch (ConnectorException $exception) {
            $this->assertNull($exception->getResponseCode());
            $this->assertSame('001.02.011', $exception->getErrorCode());
            $this->assertSame('No fundraiser found for  forename=User surname=Test', $exception->getErrorMessage());
            $this->assertEmpty($exception->getMessageDetails());
            $this->assertSame('Request params: \n  forename=User\n  surname=Test\n\n', $exception->getInputDetails());
        }
    }

    /**
     * @throws \VirginMoneyGivingAPI\Exceptions\ConnectorException
     */
    public function testSearchFound()
    {
        $stream = file_get_contents('tests/Mocks/FundraiserSearch.txt');
        $response = new Response(200, ['Content-Type' => 'application/json'], $stream);
        $this->setMockClient([$response]);
        $fundraiserConnector = new FundraiserVmgConnector('API_KEY', $this->getGuzzleClient(), true);

        $response = $fundraiserConnector->search('Russel', 'Bruce');
        $this->assertInstanceOf(FundraiserSearchResponse::class, $response);
        $this->assertTrue($response->hasMatches());
    }

    /**
     * @throws \Exception
     * @throws \VirginMoneyGivingAPI\Exceptions\ConnectorException
     */
    public function testFundraiserCreate()
    {
        $faker = \Faker\Factory::create('en_GB');

        $fundraiser = new Fundraiser();
        $fundraiser->setTitle('Mr')
            ->setForename($faker->firstName)
            ->setSurname($faker->lastName)
            ->setAddressLine1($faker->streetName)
            ->setAddressLine2($faker->streetAddress)
            ->setTownCity($faker->city)
            ->setCountyState($faker->county)
            ->setPostcode($faker->postcode)
            ->setCountryCode($faker->countryCode)
            ->setPreferredTelephone('12345678912')
            ->setEmailAddress($faker->safeEmail)
            ->setPersonalUrl(uniqid(str_replace(' ', '-', trim($fundraiser->getSurname())), false))
            ->setTermsAndConditionsAccepted('Y')
            ->setDateOfBirth('20010101');

        $stream = file_get_contents('tests/Mocks/FundraiserCreateWithAuthToken.txt');
        $response = new Response(200, ['Content-Type' => 'application/json'], $stream);
        $this->setMockClient([$response]);
        $fundraiserConnector = new FundraiserVmgConnector('API_KEY', $this->getGuzzleClient(), true);
        $response = $fundraiserConnector->createFundraiserAccount($fundraiser, 'CALLBACK_URL');

        $this->assertInstanceOf(FundraiserCreateResponse::class, $response);
        $this->assertNotEmpty($response->getModel()->getResourceId());
        $this->assertTrue($response->isCreationSuccessful());
        $this->assertFalse($response->isCustomerExists());
        $this->assertNotEmpty($response->getAccessToken());
        $this->assertSame('Token expires in 1500 seconds', $response->getMessage());
    }

    /**
     * @throws \Exception
     * @throws \VirginMoneyGivingAPI\Exceptions\ConnectorException
     */
    public function testFundraiserCreateWrongCallbackUrl()
    {
        $faker = \Faker\Factory::create('en_GB');

        $fundraiser = new Fundraiser();
        $fundraiser->setTitle('Mr')
            ->setForename($faker->firstName)
            ->setSurname($faker->lastName)
            ->setAddressLine1($faker->streetName)
            ->setAddressLine2($faker->streetAddress)
            ->setTownCity($faker->city)
            ->setCountyState($faker->county)
            ->setPostcode($faker->postcode)
            ->setCountryCode($faker->countryCode)
            ->setPreferredTelephone('12345678912')
            ->setEmailAddress($faker->safeEmail)
            ->setPersonalUrl(uniqid(str_replace(' ', '-', trim($fundraiser->getSurname())), false))
            ->setTermsAndConditionsAccepted('Y')
            ->setDateOfBirth('20010101');

        $stream = file_get_contents('tests/Mocks/FundraiserCreateNoAuthToken.txt');
        $response = new Response(200, ['Content-Type' => 'application/json'], $stream);
        $this->setMockClient([$response]);
        $fundraiserConnector = new FundraiserVmgConnector('API_KEY', $this->getGuzzleClient(), true);
        $response = $fundraiserConnector->createFundraiserAccount($fundraiser, 'CALLBACK_URL');

        $this->assertInstanceOf(FundraiserCreateResponse::class, $response);
        $this->assertNotEmpty($response->getModel()->getResourceId());
        $this->assertTrue($response->isCreationSuccessful());
        $this->assertFalse($response->isCustomerExists());
        $this->assertSame('OAuth access token not created. Error Code: <-2001>  Message: <Invalid redirect_uri> Description: <N/A>', $response->getMessage());
        $this->assertEmpty($response->getAccessToken());
    }

    /**
     * @throws \Exception
     * @throws \VirginMoneyGivingAPI\Exceptions\ConnectorException
     */
    public function testPageCreate()
    {
        // Set the requests we need to mock in the order they will be called.
        $stream = file_get_contents('tests/Mocks/FundraiserCreateWithAuthToken.txt');
        $mockFundraiserResponse = new Response(200, ['Content-Type' => 'application/json'], $stream);
        $stream = file_get_contents('tests/Mocks/PageCreateSucess.txt');
        $mockPageResponse = new Response(200, ['Content-Type' => 'application/json'], $stream);
        $this->setMockClient([$mockFundraiserResponse, $mockPageResponse]);

        $faker = \Faker\Factory::create('en_GB');

        $fundraiser = new Fundraiser();
        $fundraiser->setTitle('Mr')
            ->setForename($faker->firstName)
            ->setSurname($faker->lastName)
            ->setAddressLine1($faker->streetName)
            ->setAddressLine2($faker->streetAddress)
            ->setTownCity($faker->city)
            ->setCountyState($faker->county)
            ->setPostcode($faker->postcode)
            ->setCountryCode($faker->countryCode)
            ->setPreferredTelephone('12345678912')
            ->setEmailAddress($faker->safeEmail)
            ->setPersonalUrl(uniqid(str_replace(' ', '-', trim($fundraiser->getSurname())), false))
            ->setTermsAndConditionsAccepted('Y')
            ->setDateOfBirth('20010101');

        $fundraiserConnector = new FundraiserVmgConnector('API_KEY', $this->getGuzzleClient(), true);
        $fundraiserResponse = $fundraiserConnector->createFundraiserAccount($fundraiser, 'CALLBACK_URL');

        $this->assertInstanceOf(FundraiserCreateResponse::class, $fundraiserResponse);
        $this->assertNotEmpty($fundraiserResponse->getModel()->getResourceId());
        $this->assertNotEmpty($fundraiserResponse->getAccessToken());

        $page = new Page();
        $page->setPageTitle($fundraiser->getForename().' '.$fundraiser->getSurname().' London Marathon.')
            ->setEventResourceId('8b74655f-bbb8-4cba-8733-1181e79527f7')
            ->setFundraisingTarget(2000.00)
            ->setCharityResourceId('8da32779-1c1b-4d20-8714-df3219836618')
            ->setCharitySplits([
                [
                    'charityResourceId'   => '6a5880c9-13e4-4cf4-987e-931f3899b9d5',
                    'charitySplitPercent' => 50,
                ],
                [
                    'charityResourceId'   => '8da32779-1c1b-4d20-8714-df3219836618',
                    'charitySplitPercent' => 50,
                ],
            ]);

        // Now create the page.
        $response = $fundraiserConnector->createFundraiserPage($page, $fundraiser, $fundraiserResponse->getAccessToken());

        $this->assertInstanceOf(PageCreateResponse::class, $response);
        $this->assertTrue($response->isCreationSuccessful());
        $this->assertNotEmpty($response->getPageURI());
        $this->assertSame('Page created successfully', $response->getMessage());
    }
}
