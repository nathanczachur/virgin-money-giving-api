<?php

namespace VirginMoneyGivingAPI\Connectors;

use VirginMoneyGivingAPI\AbstractVmgConnector;
use VirginMoneyGivingAPI\Models\Fundraiser;
use VirginMoneyGivingAPI\Models\Page;
use VirginMoneyGivingAPI\Responses\FundraiserCreateResponse;
use VirginMoneyGivingAPI\Responses\FundraiserSearchResponse;
use VirginMoneyGivingAPI\Responses\PageCreateResponse;
use function Rap2hpoutre\ConvertAccentCharacters\convert_accent_characters;

class FundraiserVmgConnector extends AbstractVmgConnector
{
    /**
     * Given a first name and surname this function will search VMG for a matching
     * fundraisers.
     *
     * https://developer.virginmoneygiving.com/docs/read/Fundraiser_Search
     *
     * @param $surname
     * @param $forename
     *
     * @throws \VirginMoneyGivingAPI\Exceptions\ConnectorException
     *
     * @return \VirginMoneyGivingAPI\Responses\FundraiserSearchResponse
     */
    public function search($surname, $forename)
    {
        $method = 'GET';
        $path = '/fundraisers/v1/search.json?surname='.
            convert_accent_characters($surname).
            '&forename='.convert_accent_characters($forename).'&';

        // We don't try and catch the exceptions here
        $response = $this->request($path, $method);

        return new FundraiserSearchResponse($response);
    }

    /**
     * Create a fundraiser account.
     *
     * https://developer.virginmoneygiving.com/docs/read/Create_Fundraiser_Account
     *
     * @param \VirginMoneyGivingAPI\Models\Fundraiser $fundraiser
     * @param string                                  $callbackUrl
     *
     * @throws \VirginMoneyGivingAPI\Exceptions\ConnectorException
     *
     * @return \VirginMoneyGivingAPI\Responses\FundraiserCreateResponse
     */
    public function createFundraiserAccount(Fundraiser $fundraiser, string $callbackUrl)
    {
        $method = 'POST';
        $path = '/fundraisers/v1/newaccount?redirect_uri='.$callbackUrl.'&';

        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ],

            'body' => \GuzzleHttp\json_encode([
                'title'                        => $fundraiser->getTitle(),
                'forename'                     => $fundraiser->getForename(),
                'surname'                      => $fundraiser->getSurname(),
                'addressLine1'                 => $fundraiser->getAddressLine1(),
                'addressLine2'                 => $fundraiser->getAddressLine2(),
                'townCity'                     => $fundraiser->getTownCity(),
                'countyState'                  => $fundraiser->getCountyState(),
                'postcode'                     => $fundraiser->getPostcode(),
                'countryCode'                  => $fundraiser->getCountryCode(),
                'preferredTelephone'           => $fundraiser->getPreferredTelephone(),
                'emailAddress'                 => $fundraiser->getEmailAddress(),
                'personalUrl'                  => $fundraiser->getPersonalUrl(),
                'termsAndConditionsAccepted'   => $fundraiser->getTermsAndConditionsAccepted(),
                'charityMarketingIndicator'    => 'N',
                'allCharityMarketingIndicator' => 'N',
                'virginMarketingIndicator'     => 'N',
                'dateOfBirth'                  => $fundraiser->getDateOfBirth(),
                'vmgMarketingIndicator'        => 'N',
            ]),
        ];

        // Don't try and catch any errors, let them bubble up.
        $response = $this->request($path, $method, $options);

        return new FundraiserCreateResponse($response, $fundraiser);
    }

    /**
     * Create a fundraiser page.
     *
     * https://developer.virginmoneygiving.com/docs/read/Create_Fundraiser_page
     *
     * @param \VirginMoneyGivingAPI\Models\Page       $fundraiserPage
     * @param \VirginMoneyGivingAPI\Models\Fundraiser $fundraiser
     * @param string                                  $accessToken
     *
     * @throws \VirginMoneyGivingAPI\Exceptions\ConnectorException
     *
     * @return \VirginMoneyGivingAPI\Responses\PageCreateResponse
     */
    public function createFundraiserPage(Page $fundraiserPage, Fundraiser $fundraiser, string $accessToken)
    {
        $method = 'POST';
        $path = '/fundraisers/v1/account/secure/'.$fundraiser->getResourceId().'/newpage?';

        $options = [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer '.$accessToken,
            ],
            'body' => \GuzzleHttp\json_encode([
                'pageTitle'                    => $fundraiserPage->getPageTitle(),
                'eventResourceId'              => $fundraiserPage->getEventResourceId(),
                'fundraisingDate'              => $fundraiserPage->getFundraisingDate(),
                'teamPageIndicator'            => $fundraiserPage->getTeamPageIndicator(),
                'teamName'                     => $fundraiserPage->getTeamName(),
                'teamUrl'                      => $fundraiserPage->getTeamUrl(),
                'activityCode'                 => $fundraiserPage->getActivityCode(),
                'activityDescription'          => $fundraiserPage->getActivityDescription(),
                'charityContributionIndicator' => $fundraiserPage->getCharityContributionIndicator(),
                'postEventFundraisingInterval' => $fundraiserPage->getPostEventFundraisingInterval(),
                'fundraisingTarget'            => $fundraiserPage->getFundraisingTarget(),
                'charitySplits'                => $fundraiserPage->getCharitySplits(),
            ]),
        ];

        // Don't try and catch any errors, let them bubble up.
        $response = $this->request($path, $method, $options);

        return new PageCreateResponse($response, $fundraiserPage);
    }
}
