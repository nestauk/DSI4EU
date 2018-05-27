<?php
require_once __DIR__ . '/../../src/include.php';

use Phinx\Seed\AbstractSeed;
use Illuminate\Database\Capsule\Manager as Capsule;

class SetImpactTags extends AbstractSeed
{
    public function run()
    {
        $impactTags = [
            'Education and skills',
            'Participation and democracy',
            'Culture and arts',
            'Health and wellbeing',
            'Work and employment',
            'Neighbourhood regeneration',
            'Energy and environment',
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
