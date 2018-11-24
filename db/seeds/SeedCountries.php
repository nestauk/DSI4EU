<?php


use Phinx\Seed\AbstractSeed;
use Models\Country;

class SeedCountries extends AbstractSeed
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
        $countryNames = "Afghanistan
Albania
Algeria
Andorra
Angola
Antigua and Barbuda
Argentina
Armenia
Australia
Austria
Azerbaijan
Bahamas
Bahrain
Bangladesh
Barbados
Belarus
Belgium
Belize
Benin
Bhutan
Bolivia (Plurinational State of)
Bosnia and Herzegovina
Botswana
Brazil
Brunei Darussalam
Bulgaria
Burkina Faso
Burundi
Cabo Verde
Cambodia
Cameroon
Canada
Central African Republic
Chad
Chile
China
Colombia
Comoros
Congo
Costa Rica
Côte D'Ivoire
Croatia
Cuba
Cyprus
Czech Republic
Democratic People's Republic of Korea
Democratic Republic of the Congo
Denmark
Djibouti
Dominica
Dominican Republic
Ecuador
Egypt
El Salvador
Equatorial Guinea
Eritrea
Estonia
Eswatini
Ethiopia
Fiji
Finland
France
Gabon
Gambia (Republic of The)
Georgia
Germany
Ghana
Greece
Grenada
Guatemala
Guinea
Guinea Bissau
Guyana
Haiti
Honduras
Hong Kong (SAR)
Hungary
Iceland
India
Indonesia
Iran (Islamic Republic of)
Iraq
Ireland
Israel
Italy
Jamaica
Japan
Jordan
Kazakhstan
Kenya
Kiribati
Kosovo (Republic of)
Kuwait
Kyrgyzstan
Lao People’s Democratic Republic
Latvia
Lebanon
Lesotho
Liberia
Libya
Liechtenstein
Lithuania
Luxembourg
Madagascar
Malawi
Malaysia
Maldives
Mali
Malta
Marshall Islands
Mauritania
Mauritius
Mexico
Micronesia (Federated States of)
Monaco
Mongolia
Montenegro
Morocco
Mozambique
Myanmar
Namibia
Nauru
Nepal
Netherlands
New Zealand
Nicaragua
Niger
Nigeria
Norway
Oman
Pakistan
Palau
Palestine
Panama
Papua New Guinea
Paraguay
Peru
Philippines
Poland
Portugal
Qatar
Republic of Korea
Republic of Moldova
Romania
Russian Federation
Rwanda
Saint Kitts and Nevis
Saint Lucia
Saint Vincent and the Grenadines
Samoa
San Marino
Sao Tome and Principe
Saudi Arabia
Senegal
Serbia
Seychelles
Sierra Leone
Singapore
Slovakia
Slovenia
Solomon Islands
Somalia
South Africa
South Sudan
Spain
Sri Lanka
Sudan
Suriname
Sweden
Switzerland
Syrian Arab Republic
Tajikistan
Taiwan
Thailand
The former Yugoslav Republic of Macedonia
Timor-Leste
Togo
Tonga
Trinidad and Tobago
Tunisia
Turkey
Turkmenistan
Tuvalu
Uganda
Ukraine
United Arab Emirates
United Kingdom of Great Britain and Northern Ireland
United Republic of Tanzania
United States of America
Uruguay
Uzbekistan
Vanuatu
Venezuela, Bolivarian Republic of
Viet Nam
Yemen
Zambia
Zimbabwe";

        collect(explode(PHP_EOL, $countryNames))
            ->map(function ($countryName) {
                return trim($countryName);
            })
            ->filter()
            ->each(function ($countryName) {
                if (!Country::where(Country::Name, $countryName)->first()) {
                    echo("does not exist: {$countryName}\n");
                    $country = new Country();
                    $country->{Country::Name} = $countryName;
                    $country->save();
                } else {
                    echo "country exists: {$countryName} \n";
                }
            });
    }
}
