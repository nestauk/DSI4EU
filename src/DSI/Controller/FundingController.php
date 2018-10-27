<?php

namespace DSI\Controller;

use DSI\Entity\Country;
use DSI\Entity\Funding;
use DSI\Entity\FundingSource;
use DSI\Repository\CountryRepo;
use DSI\Repository\FundingRepo;
use DSI\Repository\FundingSourceRepo;
use DSI\Repository\FundingTargetRepo;
use DSI\Repository\FundingTypeRepo;
use DSI\Service\Auth;
use Services\URL;
use Services\View;

class FundingController
{
    public $format = 'html';

    /** @var URL */
    private $urlHandler;

    public function exec()
    {
        $this->urlHandler = $urlHandler = new URL();
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        if ($this->format == 'json') {
            $fundings = (new FundingRepo())->getFutureOnes();

            echo json_encode([
                'sources' => $this->jsonSources(),
                'months' => $this->jsonMonths(),
                'fundings' => $this->jsonSortedFundings($fundings),
                'countries' => $this->jsonCountriesFromFundings($fundings),
                'years' => $this->jsonYearsFromFundings($fundings),
            ]);
        } else {
            $fundingTypes = (new FundingTypeRepo())->getAll();
            $fundingTargets = (new FundingTargetRepo())->getAll();
            $userCanAddFunding = (bool)($loggedInUser AND ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin()));
            View::setPageTitle('Funding and support - DSI4EU');
            View::setPageDescription(__('Browse our list of funding and support opportunities for digital social innovation (DSI) and tech for good in Europe.'));
            require __DIR__ . '/../../../www/views/funding.php';
        }
    }

    /**
     * @return array
     */
    private function jsonSources()
    {
        $sources = (new FundingSourceRepo())->getAll();

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
            $fundingType = $funding->getType();
            return [
                'id' => $funding->getId(),
                'title' => $funding->getTitle(),
                'description' => $funding->getDescription(),
                'url' => $funding->getUrl(),
                'closingDate' => $funding->getClosingDate('d M Y'),
                'closingMonth' => $funding->getClosingDate('Ym'),
                'country' => $funding->getCountryName(),
                'countryID' => $funding->getCountryID(),
                'fundingTypeID' => $funding->getTypeID(),
                'type' => [
                    'title' => $fundingType ? $fundingType->getTitle() : '',
                    'color' => $fundingType ? $fundingType->getColor() : '',
                ],
                'fundingTargets' => $funding->getTargetIDs(),
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
            ['id' => '00', 'title' => '- ' . __('Before Month') . ' -'],
            ['id' => '01', 'title' => __('January')],
            ['id' => '02', 'title' => __('February')],
            ['id' => '03', 'title' => __('March')],
            ['id' => '04', 'title' => __('April')],
            ['id' => '05', 'title' => __('May')],
            ['id' => '06', 'title' => __('June')],
            ['id' => '07', 'title' => __('July')],
            ['id' => '08', 'title' => __('August')],
            ['id' => '09', 'title' => __('September')],
            ['id' => '10', 'title' => __('October')],
            ['id' => '11', 'title' => __('November')],
            ['id' => '12', 'title' => __('December')],
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
            if ($funding->getClosingDate('Y'))
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
            'id' => '0000',
            'title' => '- ' . __('Before Year') . ' -',
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

        $countries = (new CountryRepo())->getByIds($countryIDs);
        $countries = array_map(function (Country $country) {
            return [
                'id' => $country->getId(),
                'title' => $country->getName(),
            ];
        }, $countries);
        array_unshift($countries, [
            'id' => 0,
            'title' => '- ' . __('All countries') . ' -',
        ]);
        return $countries;
    }

    /**
     * @param $fundings Funding[]
     * @return array
     */
    private function jsonSortedFundings($fundings)
    {
        $jsonFundings = $this->jsonFundings($fundings);

        usort($jsonFundings, function ($jsonFundingA, $jsonFundingB) {
            if (!$jsonFundingA['closingMonth'] AND $jsonFundingB['closingMonth']) return 1;
            if ($jsonFundingA['closingMonth'] AND !$jsonFundingB['closingMonth']) return -1;
            return ($jsonFundingA['closingMonth'] < $jsonFundingB['closingMonth']) ? -1 : 1;
        });

        return $jsonFundings;
    }
}