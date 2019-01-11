<?php

namespace DSI\Controller\CLI;

set_time_limit(0);

use DSI\Entity\Organisation;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Repository\CountryRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\OrganisationSizeRepo;
use DSI\Repository\OrganisationTypeRepo;
use DSI\Service\ErrorHandler;
use DSI\UseCase\UpdateOrganisation;
use DSI\UseCase\UpdateOrganisationCountryRegion;

class ImportOrganisationsController
{
    /** @var User */
    private $sysAdminUser;

    /** @var  OrganisationRepo */
    private $organisationRepo;

    public function exec()
    {
        $this->sysAdminUser = new User;
        $this->sysAdminUser->setId(1);
        $this->sysAdminUser->setRole('sys-admin');

        $this->organisationRepo = new OrganisationRepo();

        $file = __DIR__ . '/../../../../import-organisations.csv';
        if (!file_exists($file)) {
            echo 'File does not exist: ' . $file . PHP_EOL;
            return;
        }
        $this->importFrom($file);
    }

    /**
     * @param $file
     */
    private function importFrom($file)
    {
        $row = 0;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($row > 2)
                    $this->importRow($data);

                echo 'imported row ' . $row . PHP_EOL;
                $row++;
            }
            fclose($handle);
        }
    }

    /**
     * @param $data
     */
    private function importRow($data)
    {
        list(
            $unusedId,
            $title,
            $website,
            $shortDescription,
            $longDescription,
            $country,
            $region,
            $unusedLatitude,
            $unusedLongitude,
            $address,
            $type,
            $size,
            $startDate,
            $unusedLinkedProjectIDs,
            $tags,
            $networkTags
            ) = $data;

        // print_r($data);
        $organisation = new Organisation();
        $organisation->setName($title);
        $organisation->setOwner($this->sysAdminUser);
        $organisation->setUrl($website);
        $organisation->setShortDescription($shortDescription);
        $organisation->setDescription($longDescription);
        $organisation->setAddress($address);
        $organisation->setStartDate($startDate);

        $this->organisationRepo->insert($organisation);

        if (trim($country)) {
            if ($country == 'Russia')
                $country = 'Russian Federation';
            if (strtolower($country) == 'uk')
                $country = 'United Kingdom';

            if ($region == '')
                $region = $country;

            try {
                $organisation = $this->setRegion($organisation, $country, $region);
                $organisation = $this->setType($organisation, $type);
                $organisation = $this->setSize($organisation, $size);
                $organisation = $this->setTags($organisation, $tags, $networkTags);

                $this->organisationRepo->save($organisation);
                // print_r($organisation);
            } catch (\Exception $e) {
                $region = $country;
                try {
                    $organisation = $this->setRegion($organisation, $country, $region);
                    $organisation = $this->setType($organisation, $type);
                    $organisation = $this->setSize($organisation, $size);
                    $organisation = $this->setTags($organisation, $tags, $networkTags);

                    $this->organisationRepo->save($organisation);
                } catch (NotFound $e) {
                    pr('Not Found');
                    pr($e->getMessage());
                    pr($e->getTrace());
                } catch (ErrorHandler $e) {
                    pr('Error');
                    pr($e->getErrors());
                }
            }
        }
    }

    private function setRegion(Organisation $organisation, $country, $region)
    {
        $countryRepo = new CountryRepo();
        $countryObj = $countryRepo->getByName($country);

        $exec = new UpdateOrganisationCountryRegion();
        $exec->data()->countryID = $countryObj->getId();
        $exec->data()->organisationID = $organisation->getId();
        $exec->data()->region = $region;
        $exec->exec();

        return $organisation;
    }

    private function getTypeName($typeID)
    {
        $types = [
            1 => "Social enterprise, charity, foundation or other non-profit",
            2 => "For-profit business",
            3 => "Academia/Research organisation",
            4 => "Grassroots organisation or community network",
            5 => "Government/public sector",
        ];
        return $types[$typeID];
    }

    private function setType(Organisation $organisation, $type)
    {
        if ($type != '') {
            $typeRepo = new OrganisationTypeRepo();
            $type = $typeRepo->getByName($this->getTypeName($type));
            $organisation->setType($type);
        }

        return $organisation;
    }

    private function setSize(Organisation $organisation, $size)
    {
        if ($size != '' AND $size != '43014') {
            if ($size == '0-5')
                $size = '0-5 people';
            if ($size == '11-25')
                $size = '11-25 people';
            if ($size == '26-50')
                $size = '26-50 people';
            if ($size == 'Over 1000')
                $size = 'over-1000 people';

            $sizeRepo = new OrganisationSizeRepo();
            $size = $sizeRepo->getByName($size);
            $organisation->setSize($size);
        }

        return $organisation;
    }

    private function setTags(Organisation $organisation, $tags, $networkTags)
    {
        if ($tags) {
            $tagList = explode(',', $tags);
            $tagList = array_map('trim', $tagList);
            $tagList = array_filter($tagList);
        } else {
            $tagList = [];
        }

        if ($networkTags) {
            $netTagList = explode(',', $networkTags);
            $netTagList = array_map('trim', $netTagList);
            $netTagList = array_filter($netTagList);
        } else {
            $netTagList = [];
        }

        $exec = new UpdateOrganisation($this->sysAdminUser, $organisation);
        $exec->data()->tags = $tagList;
        $exec->data()->networkTags = $netTagList;
        $exec->exec();

        return $organisation;
    }
}