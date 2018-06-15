<?php

use Phinx\Seed\AbstractSeed;
use Illuminate\Database\Capsule\Manager as Capsule;

class SetImpactTags extends AbstractSeed
{
    public function run()
    {
        Capsule::table(\Models\Tag::TABLE)
            ->update([
                \Models\Tag::IsMain => 0,
                \Models\Tag::Order => 0,
            ]);

        $replaces = [
            ['Education and skills', 'Skills and learning'],
            ['Participation and democracy', 'Digital democracy'],
            ['Culture and arts', 'Culture and arts'],
            ['Health and wellbeing', 'Health and care'],
            ['Work and employment', 'Work and employment'],
            ['Neighbourhood regeneration', 'Cities and urban development'],
            ['Energy and environment', 'Food, environment and climate change'],
            ['Science', 'Science'],
            ['Finance and economy', 'Finance and economy'],
        ];
        foreach ($replaces AS $replace) {
            /** @var \Models\Tag $tag */
            $tag = \Models\Tag::where(\Models\Tag::Name, $replace[0])->first();
            if ($tag) {
                $tag->setName($replace[1]);
                $tag->save();
            }
        }

        $impactTags = [
            'Health and care',
            'Skills and learning',
            'Food, environment and climate change',
            'Migration and integration',
            'Digital democracy',
            'Cities and urban development',
            'Work and employment',
            'Culture and arts',
            'Science',
            'Finance and economy',
        ];
        foreach ($impactTags AS $i => $tag) {
            \Models\Tag::firstOrCreate([
                \Models\Tag::Name => $tag
            ]);

            Capsule::table(\Models\Tag::TABLE)
                ->where(\Models\Tag::Name, $tag)
                ->update([
                    \Models\Tag::IsMain => 1,
                    \Models\Tag::Order => count($impactTags) - $i,
                ]);
        }

        $technologyTags = [
            'Social networks and social media',
            'Crowdsourcing, crowdmapping and crowdfunding',
            'Open data',
            'Mobile and web apps',
            'Open-source technologies',
            'Peer-to-peer networks',
            'Online learning and MOOCs',
            'Online marketplaces and noticeboards',
            'Big data',
            'Sensors and internet of things',
            'Geotagging, GPS and GIS',
            'Digital fabrication and 3D-printing',
            'Wearables and personal monitoring',
            'AI and machine learning',
            'Digital democracy tools',
            'Blockchain and distributed networks',
            'Robotics',
            'VR and AR',
        ];
        foreach ($technologyTags AS $i => $tag) {
            \Models\Tag::firstOrCreate([
                \Models\Tag::Name => $tag
            ]);

            Capsule::table(\Models\Tag::TABLE)
                ->where(\Models\Tag::Name, $tag)
                ->update([
                    \Models\Tag::IsTechnologyMain => 1,
                    \Models\Tag::TechnologyOrder => count($technologyTags) - $i,
                ]);
        }
    }
}
