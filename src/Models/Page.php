<?php

namespace VirginMoneyGivingAPI\Models;

/**
 * Class Page.
 *
 * The Fundraiser page as defined here: https://developer.virginmoneygiving.com/docs/read/Create_Fundraiser_page
 */
class Page extends AbstractModel
{
    /**
     * @var string
     */
    protected $pageTitle;

    /**
     * @var string
     */
    protected $eventResourceId;

    /**
     * @var string YYYYMMDD
     */
    protected $fundraisingDate = '';

    /**
     * @var string
     */
    protected $teamPageIndicator = 'N';

    /**
     * @var string
     */
    protected $teamName = '';

    /**
     * @var string
     */
    protected $teamUrl = '';

    /**
     * @var string
     */
    protected $activityCode = '';

    /**
     * @var string
     */
    protected $activityDescription = '';

    /**
     * @var bool
     */
    protected $charityContributionIndicator = 'N';

    /**
     * @var int
     */
    protected $postEventFundraisingInterval = 3;

    /**
     * @var float
     */
    protected $fundraisingTarget;

    /**
     * @var string
     */
    protected $charityResourceId;

    /**
     * @var array
     */
    protected $charitySplits;

    /**
     * @return string
     */
    public function getPageTitle(): string
    {
        return $this->pageTitle;
    }

    /**
     * @param string $pageTitle
     *
     * @return Page
     */
    public function setPageTitle(string $pageTitle): self
    {
        $this->pageTitle = $this->convertAccentedCharacters($pageTitle);

        return $this;
    }

    /**
     * @return string
     */
    public function getEventResourceId(): string
    {
        return $this->eventResourceId;
    }

    /**
     * @param string $eventResourceId
     *
     * @return Page
     */
    public function setEventResourceId(string $eventResourceId): self
    {
        $this->eventResourceId = $eventResourceId;

        return $this;
    }

    /**
     * @return string
     */
    public function getFundraisingDate(): string
    {
        return $this->fundraisingDate;
    }

    /**
     * @param $fundraisingDate
     *
     * @throws \Exception
     *
     * @return \VirginMoneyGivingAPI\Models\Page
     */
    public function setFundraisingDate($fundraisingDate): self
    {
        if (!empty($fundraisingDate) &&
            !preg_match('/^[0-9]{4}(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])$/', $fundraisingDate)) {
            throw new \Exception('Fundraising date must be in YYYYMMDD format.');
        }
        $this->fundraisingDate = $fundraisingDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getTeamPageIndicator(): string
    {
        return $this->teamPageIndicator;
    }

    /**
     * @param string $teamPageIndicator
     *
     * @throws \Exception
     *
     * @return \VirginMoneyGivingAPI\Models\Page
     */
    public function setTeamPageIndicator(string $teamPageIndicator): self
    {
        if ($teamPageIndicator != 'N' && $teamPageIndicator != 'Y') {
            throw new \Exception('Team page indicator must be Y/N.');
        }
        $this->teamPageIndicator = $teamPageIndicator;

        return $this;
    }

    /**
     * @return string
     */
    public function getTeamName(): string
    {
        return $this->teamName;
    }

    /**
     * @param $teamName
     *
     * @return Page
     */
    public function setTeamName($teamName): self
    {
        $this->teamName = ($teamName) ? $this->convertAccentedCharacters($teamName) : null;

        return $this;
    }

    /**
     * @return string
     */
    public function getTeamUrl(): string
    {
        return $this->teamUrl;
    }

    /**
     * @param $teamUrl
     *
     * @return Page
     */
    public function setTeamUrl($teamUrl): self
    {
        $this->teamUrl = ($teamUrl) ? $teamUrl : null;

        return $this;
    }

    /**
     * @return string
     */
    public function getActivityCode(): string
    {
        return $this->activityCode;
    }

    /**
     * @param $activityCode
     *
     * @return Page
     */
    public function setActivityCode($activityCode): self
    {
        $this->activityCode = ($activityCode) ? $activityCode : null;

        return $this;
    }

    /**
     * @return string
     */
    public function getActivityDescription(): string
    {
        return $this->activityDescription;
    }

    /**
     * @param $activityDescription
     *
     * @return Page
     */
    public function setActivityDescription($activityDescription): self
    {
        $this->activityDescription = ($activityDescription) ? $this->convertAccentedCharacters($activityDescription) : null;

        return $this;
    }

    /**
     * @return string
     */
    public function getCharityContributionIndicator(): string
    {
        return $this->charityContributionIndicator;
    }

    /**
     * @param $charityContributionIndicator
     *
     * @throws \Exception
     *
     * @return \VirginMoneyGivingAPI\Models\Page
     */
    public function setCharityContributionIndicator($charityContributionIndicator): self
    {
        if ($charityContributionIndicator != 'N' && $charityContributionIndicator != 'Y') {
            throw new \Exception('Charity contribution indicator must be Y/N.');
        }
        $this->charityContributionIndicator = $charityContributionIndicator;

        return $this;
    }

    /**
     * @return int
     */
    public function getPostEventFundraisingInterval(): int
    {
        return $this->postEventFundraisingInterval;
    }

    /**
     * @param int $postEventFundraisingInterval
     *
     * @return Page
     */
    public function setPostEventFundraisingInterval(int $postEventFundraisingInterval): self
    {
        $this->postEventFundraisingInterval = $postEventFundraisingInterval;

        return $this;
    }

    /**
     * @return float
     */
    public function getFundraisingTarget(): float
    {
        return $this->fundraisingTarget;
    }

    /**
     * @param float $fundraisingTarget
     *
     * @return Page
     */
    public function setFundraisingTarget(float $fundraisingTarget): self
    {
        $this->fundraisingTarget = $fundraisingTarget;

        return $this;
    }

    /**
     * @return string
     */
    public function getCharityResourceId(): string
    {
        return $this->charityResourceId;
    }

    /**
     * @param string $charityResourceId
     *
     * @return Page
     */
    public function setCharityResourceId(string $charityResourceId): self
    {
        $this->charityResourceId = $charityResourceId;

        return $this;
    }

    /**
     * @return array
     */
    public function getCharitySplits(): array
    {
        return $this->charitySplits;
    }

    /**
     * @param array $charitySplits
     *
     * @return Page
     */
    public function setCharitySplits(array $charitySplits): self
    {
        $this->charitySplits = $charitySplits;

        return $this;
    }
}
