<?php


use Phinx\Seed\AbstractSeed;
use Models\Country;

class SetCountries extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $countries = [
            [Country::Id => '1', Country::Name => 'Russian Federation'],
            [Country::Id => '2', Country::Name => 'Netherlands'],
            [Country::Id => '3', Country::Name => 'Bulgaria'],
            [Country::Id => '4', Country::Name => 'United States'],
            [Country::Id => '5', Country::Name => 'France'],
            [Country::Id => '6', Country::Name => 'Italy'],
            [Country::Id => '7', Country::Name => 'Spain'],
            [Country::Id => '8', Country::Name => 'Switzerland'],
            [Country::Id => '9', Country::Name => 'Portugal'],
            [Country::Id => '10', Country::Name => 'United Kingdom'],
            [Country::Id => '11', Country::Name => 'Australia'],
            [Country::Id => '12', Country::Name => 'Germany'],
            [Country::Id => '13', Country::Name => 'Austria'],
            [Country::Id => '14', Country::Name => 'Sweden'],
            [Country::Id => '15', Country::Name => 'Tunisia'],
            [Country::Id => '16', Country::Name => 'Cote d\'Ivoire'],
            [Country::Id => '17', Country::Name => 'Norway'],
            [Country::Id => '18', Country::Name => 'Finland'],
            [Country::Id => '19', Country::Name => 'Belgium'],
            [Country::Id => '20', Country::Name => 'Greece'],
            [Country::Id => '21', Country::Name => 'Romania'],
            [Country::Id => '22', Country::Name => 'Montenegro'],
            [Country::Id => '23', Country::Name => 'Ireland'],
            [Country::Id => '24', Country::Name => 'Czech Republic'],
            [Country::Id => '25', Country::Name => 'Peru'],
            [Country::Id => '26', Country::Name => 'South Korea'],
            [Country::Id => '27', Country::Name => 'South Africa'],
            [Country::Id => '28', Country::Name => 'Georgia'],
            [Country::Id => '29', Country::Name => 'Iceland'],
            [Country::Id => '30', Country::Name => 'Slovakia'],
            [Country::Id => '31', Country::Name => 'Macedonia'],
            [Country::Id => '32', Country::Name => 'Denmark'],
            [Country::Id => '33', Country::Name => 'Pakistan'],
            [Country::Id => '34', Country::Name => 'Canada'],
            [Country::Id => '35', Country::Name => 'Colombia'],
            [Country::Id => '36', Country::Name => 'Hong Kong'],
            [Country::Id => '37', Country::Name => 'Hungary'],
            [Country::Id => '38', Country::Name => 'Estonia'],
            [Country::Id => '39', Country::Name => 'Jamaica'],
            [Country::Id => '40', Country::Name => 'Luxembourg'],
            [Country::Id => '41', Country::Name => 'Poland'],
            [Country::Id => '42', Country::Name => 'Chile'],
            [Country::Id => '43', Country::Name => 'India'],
            [Country::Id => '44', Country::Name => 'Serbia'],
            [Country::Id => '45', Country::Name => 'Cyprus'],
            [Country::Id => '46', Country::Name => 'Israel'],
            [Country::Id => '47', Country::Name => 'Turkey'],
            [Country::Id => '48', Country::Name => 'Lithuania'],
            [Country::Id => '49', Country::Name => 'Moldova'],
            [Country::Id => '50', Country::Name => 'Iran'],
            [Country::Id => '51', Country::Name => 'Mexico'],
            [Country::Id => '52', Country::Name => 'Slovenia'],
            [Country::Id => '53', Country::Name => 'China'],
            [Country::Id => '54', Country::Name => 'Ecuador'],
            [Country::Id => '55', Country::Name => 'Uruguay'],
            [Country::Id => '56', Country::Name => 'Albania'],
            [Country::Id => '57', Country::Name => 'Brazil'],
            [Country::Id => '58', Country::Name => 'Egypt'],
            [Country::Id => '59', Country::Name => 'Indonesia'],
            [Country::Id => '60', Country::Name => 'Jordan'],
            [Country::Id => '61', Country::Name => 'Belarus'],
            [Country::Id => '62', Country::Name => 'Argentina'],
            [Country::Id => '63', Country::Name => 'Croatia'],
            [Country::Id => '64', Country::Name => 'Armenia'],
            [Country::Id => '65', Country::Name => 'Qatar'],
            [Country::Id => '66', Country::Name => 'Bosnia and Herzegovina'],
            [Country::Id => '67', Country::Name => 'Reunion'],
            [Country::Id => '68', Country::Name => 'Cameroon'],
            [Country::Id => '69', Country::Name => 'Kenya'],
            [Country::Id => '70', Country::Name => 'EU'],
            [Country::Id => '71', Country::Name => 'Kosovo'],
            [Country::Id => '72', Country::Name => 'Latvia'],
            [Country::Id => '73', Country::Name => 'Malta'],
            [Country::Id => '74', Country::Name => 'Ukraine'],
            [Country::Id => '75', Country::Name => 'Europe'],
            [Country::Name => 'Japan'],
        ];

        Country::truncate();
        foreach ($countries AS $country) {
            $countryInst = new Country();
            if (isset($country[Country::Id]))
                $countryInst->{Country::Id} = $country[Country::Id];
            $countryInst->{Country::Name} = $country[Country::Name];
            $countryInst->save();
        }
    }
}
