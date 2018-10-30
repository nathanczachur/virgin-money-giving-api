<?php

namespace Tests\Models;

use Tests\VmgTestBase;
use VirginMoneyGivingAPI\Models\Fundraiser;

class FundraiserTest extends VmgTestBase
{
    public function testTitle()
    {
        $fundraiser = new Fundraiser();
        $this->expectException(\Exception::class);
        $fundraiser->setTitle('Doctor');

        $fundraiser->setTitle('Ms');
        $this->assertSame('Ms', $fundraiser->getTitle());
    }

    public function testEmail()
    {
        $fundraiser = new Fundraiser();
        $this->expectException(\Exception::class);
        $fundraiser->setEmailAddress('NOT_AN_EMAIL_FORMAT');

        $fundraiser->setEmailAddress('test@example.com');
        $this->assertSame('test@example.com', $fundraiser->getEmailAddress());
    }

    public function testPostcodeNoArgument()
    {
        // Set exception expectations
        $this->expectException(\ArgumentCountError::class);
        $this->expectExceptionMessage('Too few arguments to function '.Fundraiser::class.'::setPostcode()');

        // Set up test
        $fundraiser = new Fundraiser();

        // Run test
        $fundraiser->setPostcode();
    }

    public function testValidPostcode()
    {
        // Initiate faker library. Might be over kill for 1 test?
        $faker = \Faker\Factory::create('en_GB');

        // Set up test
        $fundraiser = new Fundraiser();
        $validPostCode = $faker->postcode;

        // Run test
        $fundraiser->setPostcode($validPostCode);

        // Assert expected state
        $this->assertSame($validPostCode, $fundraiser->getPostcode());
    }

    public function testInvalidPostcode()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('The postcode does not match the required UK Government postcode standard.');

        // Set up test
        $fundraiser = new Fundraiser();
        $invalidPostCode = 'NoTaPoStCoDe';

        // Run test
        $fundraiser->setPostcode($invalidPostCode);

        // Assert expected state
        $this->assertSame($invalidPostCode, $fundraiser->getPostcode());
    }

    public function tesTelephoneNumber()
    {
        $fundraiser = new Fundraiser();
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('The phone number must only contain numbers.');
        $fundraiser->setPreferredTelephone('THIS IS A STRING');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('The phone number must only contain numbers.');
        $fundraiser->setPreferredTelephone('The phone number must be 16 characters or less.');

        $fundraiser->setPreferredTelephone('12345678');
        $this->assertSame('123456789', $fundraiser->getPreferredTelephone());
    }

    public function testTermsAndConditionsAccepted()
    {
        $fundraiser = new Fundraiser();
        $this->expectException(\Exception::class);
        $fundraiser->setTermsAndConditionsAccepted('THIS IS A STRING');

        $fundraiser->setTermsAndConditionsAccepted('Y');
        $this->assertSame('Y', $fundraiser->getTermsAndConditionsAccepted());
    }

    public function testDateOfBirth()
    {
        $fundraiser = new Fundraiser();
        $this->expectException(\Exception::class);
        $fundraiser->setDateOfBirth('THIS IS A STRING');

        $fundraiser->setDateOfBirth('20010101');
        $this->assertSame('20010101', $fundraiser->getDateOfBirth());
    }
}
