<?php

namespace DSI\Controller;

use DSI\Entity\Country;
use DSI\Entity\Funding;
use DSI\Entity\FundingSource;
use DSI\Repository\CountryRepository;
use DSI\Repository\FundingRepository;
use DSI\Repository\FundingSourceRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class FundingController
{
    public $format = 'html';

    /** @var URL */
    private $urlHandler;

    public function exec()
    {
        $this->urlHandler = $urlHandler = new URL();
        $authUser = new Auth();
        if ($authUser->getUserId())
            $loggedInUser = (new UserRepository())->getById($authUser->getUserId());
        else
            $loggedInUser = null;

        if ($this->format == 'json') {
            $fundings = (new FundingRepository())->getFutureOnes();

            echo json_encode([
                'sources' => $this->jsonSources(),
                'months' => $this->jsonMonths(),
                'fundings' => $this->jsonFundings($fundings),
                'countries' => $this->jsonCountriesFromFundings($fundings),
                'years' => $this->jsonYearsFromFundings($fundings),
            ]);
        } else {
            $pageTitle = 'Funding Opportunities';
            $userCanAddFunding = (bool)($loggedInUser AND ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin()));
            require __DIR__ . '/../../../www/funding.php';
        }
    }

    /**
     * @return array
     */
    private function jsonSources()
    {
        $sources = (new FundingSourceRepository())->getAll();

        $fundingSources = array_map(function (FundingSource $fundingSource) {
            return [
                'id' => $fundingSource->getId(),
                'title' => $fundingSource->getTitle(),
            ];
        }, $sources);
        array_unshift($fundingSources, [
            'id' => 0,
            'title' => '- Funding Sources -',
        ]);
        return $fundingSources;
    }

    /**
     * @param Funding[] $fundings
     * @return array
     */
    private function jsonFundings($fundings)
    {
        return array_map(function (Funding $funding) {
            return [
                'id' => $funding->getId(),
                'title' => $funding->getTitle(),
                'description' => $funding->getDescription(),
                'url' => $funding->getUrl(),
                'closingDate' => $funding->getClosingDate('d M Y'),
                'closingYear' => $funding->getClosingDate('Y'),
                'closingMonth' => $funding->getClosingDate('m'),
                'country' => $funding->getCountryName(),
                'countryID' => $funding->getCountryID(),
                'fundingSourceID' => $funding->getSourceID(),
                'fundingSource' => $funding->getSource()->getTitle(),
                'isNew' => $funding->isNew(),
                'editUrl' => $this->urlHandler->editFunding($funding->getId()),
            ];
        }, $fundings);
    }

    /**
     * @return array
     */
    private function jsonMonths()
    {
        return [
            ['id' => 0, 'title' => '- Before Month -'],
            ['id' => 1, 'title' => 'January'],
            ['id' => 2, 'title' => 'February'],
            ['id' => 3, 'title' => 'March'],
            ['id' => 4, 'title' => 'April'],
            ['id' => 5, 'title' => 'May'],
            ['id' => 6, 'title' => 'June'],
            ['id' => 7, 'title' => 'July'],
            ['id' => 8, 'title' => 'August'],
            ['id' => 9, 'title' => 'September'],
            ['id' => 10, 'title' => 'October'],
            ['id' => 11, 'title' => 'November'],
            ['id' => 12, 'title' => 'December'],
        ];
    }

    /**
     * @param Funding[] $fundings
     * @return array
     */
    private function jsonYearsFromFundings($fundings)
    {
        $years = [date('Y')];
        foreach ($fundings AS $funding) {
            $years[] = $funding->getClosingDate('Y');
        }
        $years = array_unique($years);
        sort($years);

        $endingYears = array_map(function ($year) {
            return [
                'id' => $year,
                'title' => $year,
            ];
        }, $years);

        array_unshift($endingYears, [
            'id' => 0,
            'title' => '- Before Year -',
        ]);
        return $endingYears;
    }

    /**
     * @param Funding[] $fundings
     * @return \DSI\Entity\Country[]
     */
    private function jsonCountriesFromFundings($fundings)
    {
        $countryIDs = [];
        foreach ($fundings AS $funding) {
            $countryIDs[] = $funding->getCountryID();
        }

        $countries = (new CountryRepository())->getByIds($countryIDs);
        $countries = array_map(function (Country $country) {
            return [
                'id' => $country->getId(),
                'title' => $country->getName(),
            ];
        }, $countries);
        array_unshift($countries, [
            'id' => 0,
            'title' => '- All countries -',
        ]);
        return $countries;
    }
}