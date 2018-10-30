<?php

namespace Tests\Models;

use Tests\VmgTestBase;
use VirginMoneyGivingAPI\Models\Page;

class PageTest extends VmgTestBase
{
    public function testFundraisingDate()
    {
        $fundraiser = new Page();
        $this->expectException(\Exception::class);
        $fundraiser->setFundraisingDate('THIS IS A STRING');

        $fundraiser->setFundraisingDate('20010101');
        $this->assertSame('20010101', $fundraiser->getFundraisingDate());
    }

    public function testTeamPageIndicator()
    {
        $fundraiser = new Page();
        $this->expectException(\Exception::class);
        $fundraiser->setTeamPageIndicator('THIS IS A STRING');

        $fundraiser->setTeamPageIndicator('Y');
        $this->assertSame('Y', $fundraiser->getTeamPageIndicator());
    }

    public function testCharityContributionIndicator()
    {
        $fundraiser = new Page();
        $this->expectException(\Exception::class);
        $fundraiser->setCharityContributionIndicator('THIS IS A STRING');

        $fundraiser->setCharityContributionIndicator('Y');
        $this->assertSame('Y', $fundraiser->getCharityContributionIndicator());
    }
}
