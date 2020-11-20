<?php

namespace App\DataFixtures\ORM;

use App\Committee\CommitteeFactory;
use App\DataFixtures\AutoIncrementResetter;
use App\Entity\CommitteeElection;
use App\Entity\PostAddress;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCommitteeData extends Fixture implements DependentFixtureInterface
{
    public const COMMITTEE_1_UUID = '515a56c0-bde8-56ef-b90c-4745b1c93818';
    public const COMMITTEE_2_UUID = '182d8586-8b05-4b70-a727-704fa701e816';
    public const COMMITTEE_3_UUID = 'b0cd0e52-a5a4-410b-bba3-37afdd326a0a';
    public const COMMITTEE_4_UUID = 'd648d486-fbb3-4394-b4b3-016fac3658af';
    public const COMMITTEE_5_UUID = '464d4c23-cf4c-4d3a-8674-a43910da6419';
    public const COMMITTEE_6_UUID = '508d4ac0-27d6-4635-8953-4cc8600018f9';
    public const COMMITTEE_7_UUID = '40b6e2e5-2499-438b-93ab-ef08860a1845';
    public const COMMITTEE_8_UUID = '93b72179-7d27-40c4-948c-5188aaf264b6';
    public const COMMITTEE_9_UUID = '62ea97e7-6662-427b-b90a-23429136d0dd';
    public const COMMITTEE_10_UUID = '79638242-5101-11e7-b114-b2f933d5fe66';
    public const COMMITTEE_11_UUID = 'eb050b5e-9444-49ec-b3dc-005c98024507';
    public const COMMITTEE_12_UUID = '138140a5-1dd2-11b2-88c6-671b351502ee';

    private $committeeFactory;

    public function __construct(CommitteeFactory $committeeFactory)
    {
        $this->committeeFactory = $committeeFactory;
    }

    public function load(ObjectManager $manager)
    {
        AutoIncrementResetter::resetAutoIncrement($manager, 'committees');

        // Create some default committees and make people join them
        $committee1 = $this->committeeFactory->createFromArray([
            'uuid' => self::COMMITTEE_1_UUID,
            'created_by' => LoadAdherentData::ADHERENT_3_UUID,
            'created_at' => '2017-01-12 13:25:54',
            'name' => 'En Marche Paris 8',
            'slug' => 'en-marche-paris-8',
            'description' => 'Le comité « En Marche ! » des habitants du 8ème arrondissement de Paris.',
            'address' => PostAddress::createFrenchAddress('60 avenue des Champs-Élysées', '75008-75108', null, 48.8705073, 2.3132432),
            'phone' => '+33187264236',
            'facebook_page_url' => 'https://facebook.com/enmarche-paris-8',
            'twitter_nickname' => 'enmarche75008',
        ]);
        $committee1->approved('2017-01-12 15:54:18');
        $this->addReference('committee-1', $committee1);

        $committee2 = $this->committeeFactory->createFromArray([
            'uuid' => self::COMMITTEE_2_UUID,
            'created_by' => LoadAdherentData::ADHERENT_6_UUID,
            'created_at' => '2017-01-12 19:34:12',
            'name' => 'En Marche Marseille 3',
            'description' => "En Marche ! C'est aussi à Marseille !",
            'address' => PostAddress::createFrenchAddress('30 Boulevard Louis Guichoux', '13003-13203', null, 43.3256095, 5.374416),
            'phone' => '+33673643424',
        ]);
        $this->addReference('committee-2', $committee2);

        $committee3 = $this->committeeFactory->createFromArray([
            'uuid' => self::COMMITTEE_3_UUID,
            'created_by' => LoadAdherentData::ADHERENT_7_UUID,
            'created_at' => '2017-01-26 16:08:24',
            'name' => 'En Marche Dammarie-les-Lys',
            'slug' => 'en-marche-dammarie-les-lys',
            'description' => 'Les jeunes avec En Marche !',
            'address' => PostAddress::createFrenchAddress('824 Avenue du Lys', '77190-77152', null, 48.5182194, 2.6220158),
            'phone' => '+33673654349',
            'name_locked' => true,
        ]);
        $committee3->approved('2017-01-27 09:18:33');
        $this->addReference('committee-3', $committee3);

        $committee4 = $this->committeeFactory->createFromArray([
            'uuid' => self::COMMITTEE_4_UUID,
            'created_by' => LoadAdherentData::ADHERENT_4_UUID,
            'created_at' => '2017-01-19 08:36:55',
            'name' => 'Antenne En Marche de Fontainebleau',
            'description' => 'Vous êtes Bellifontain ? Nous aussi ! Rejoignez-nous !',
            'address' => PostAddress::createFrenchAddress('40 Rue Grande', '77300-77186', null, 48.4047652, 2.6987591),
            'phone' => '+33673654349',
        ]);
        $committee4->approved();
        $committee4->setCurrentElection(new CommitteeElection($this->getReference('designation-3')));
        $this->addReference('committee-4', $committee4);

        $committee5 = $this->committeeFactory->createFromArray([
            'uuid' => self::COMMITTEE_5_UUID,
            'created_by' => LoadAdherentData::ADHERENT_7_UUID,
            'created_at' => '2017-01-19 10:54:28',
            'name' => 'En Marche - Comité de Évry',
            'description' => 'En Marche pour une nouvelle vision, du renouveau pour la France.',
            'address' => PostAddress::createFrenchAddress("Place des Droits de l'Homme et du Citoyen", '91000-91228', null, 48.6241569, 2.4265995),
            'phone' => '+33673654349',
        ]);
        $committee5->approved();
        $committee5->setCurrentElection(new CommitteeElection($this->getReference('designation-2')));
        $this->addReference('committee-5', $committee5);

        $committee6 = $this->committeeFactory->createFromArray([
            'uuid' => self::COMMITTEE_6_UUID,
            'created_by' => LoadAdherentData::ADHERENT_9_UUID,
            'created_at' => '2017-03-18 20:12:33',
            'name' => 'En Marche - Comité de Rouen',
            'description' => 'En Marche pour la France et la ville de Rouen.',
            'address' => PostAddress::createFrenchAddress('2 Place du Général de Gaulle', '76000-76540', null, 49.443232, 1.099971),
            'phone' => '+33234823644',
        ]);
        $committee6->approved('2017-03-19 09:17:24');
        $committee6->setCurrentElection(new CommitteeElection($this->getReference('designation-1')));
        $this->addReference('committee-6', $committee6);

        $committee7 = $this->committeeFactory->createFromArray([
            'uuid' => self::COMMITTEE_7_UUID,
            'created_by' => LoadAdherentData::ADHERENT_10_UUID,
            'created_at' => '2017-03-19 08:14:45',
            'name' => 'En Marche - Comité de Berlin',
            'description' => 'En Marche pour la France et nos partenaires Allemands.',
            'address' => PostAddress::createForeignAddress('DE', '10369', 'Berlin', '7 Hohenschönhauser Str.', null, 52.5330939, 13.4662418),
            'phone' => '+492211653540',
        ]);
        $committee7->approved('2017-03-19 13:43:26');
        $committee7->setCurrentElection(new CommitteeElection($this->getReference('designation-1')));
        $this->addReference('committee-7', $committee7);

        $committee8 = $this->committeeFactory->createFromArray([
            'uuid' => self::COMMITTEE_8_UUID,
            'created_by' => LoadAdherentData::ADHERENT_11_UUID,
            'created_at' => '2017-04-10 17:34:18',
            'name' => 'En Marche - Comité de Singapour',
            'description' => 'En Marche pour la France mais depuis Singapour.',
            'address' => PostAddress::createForeignAddress('SG', '368645', 'Singapour', '47 Jln Mulia', null, 1.3329126, 103.8795163),
            'phone' => '+6566888868',
        ]);
        $committee8->approved('2017-04-10 20:23:18');
        $committee8->setCurrentElection(new CommitteeElection($this->getReference('designation-5')));
        $this->addReference('committee-8', $committee8);

        $committee9 = $this->committeeFactory->createFromArray([
            'uuid' => self::COMMITTEE_9_UUID,
            'created_by' => LoadAdherentData::ADHERENT_12_UUID,
            'created_at' => '2017-04-09 12:16:22',
            'name' => 'En Marche - Comité de New York City',
            'description' => 'Les expats sont en En Marche.',
            'address' => PostAddress::createForeignAddress('US', '10019', 'New York', '226 W 52nd St', null, 40.7625289, -73.9859927),
            'phone' => '+12123150100',
        ]);
        $committee9->approved('2017-04-09 13:27:42');
        $committee9->setCurrentElection(new CommitteeElection($this->getReference('designation-5')));
        $this->addReference('committee-9', $committee9);

        $committee10 = $this->committeeFactory->createFromArray([
            'uuid' => self::COMMITTEE_10_UUID,
            'created_by' => LoadAdherentData::REFERENT_1_UUID,
            'created_at' => '2017-05-09 12:18:22',
            'name' => 'En Marche - Suisse',
            'description' => 'En Marche pour la France et nos partenaires en Suisse.',
            'address' => PostAddress::createForeignAddress('CH', '8057', 'Zürich', '32 Zeppelinstrasse', null, 47.3950786, 8.5361402),
            'phone' => '+33673654349',
        ]);
        $committee10->approved('2017-05-09 13:17:42');
        $committee10->setCurrentElection(new CommitteeElection($this->getReference('designation-5')));
        $this->addReference('committee-10', $committee10);

        $committee11 = $this->committeeFactory->createFromArray([
            'uuid' => self::COMMITTEE_11_UUID,
            'created_by' => LoadAdherentData::ADHERENT_7_UUID,
            'created_at' => '2017-05-12 12:18:22',
            'name' => 'En Marche - Suisse refused',
            'description' => 'En Marche pour la France et nos partenaires en Suisse. (refused)',
            'address' => PostAddress::createForeignAddress('CH', '8057', 'Zürich', '32 Zeppelinstrasse', null, 47.3950786, 8.5361402),
            'phone' => '+33673654567',
        ]);
        $committee11->approved('2017-05-13 13:17:42');
        $committee11->refused('2017-05-14 13:17:42');
        $this->addReference('committee-11', $committee11);

        $committee12 = $this->committeeFactory->createFromArray([
            'uuid' => self::COMMITTEE_12_UUID,
            'created_by' => LoadAdherentData::REFERENT_1_UUID,
            'created_at' => '2020-10-29 09:00:00',
            'name' => 'En Marche - Allemagne',
            'description' => 'En Marche Allemagne.',
            'address' => PostAddress::createForeignAddress('DE', '10789', 'Berlin', 'Breitscheidplatz', null, 52.5065133, 13.1445545),
            'phone' => '+33673654349',
        ]);
        $committee12->approved('2020-10-29 12:00:00');
        $committee12->setCurrentElection(new CommitteeElection($this->getReference('designation-5')));
        $this->addReference('committee-12', $committee12);

        $manager->persist($committee1);
        $manager->persist($committee2);
        $manager->persist($committee3);
        $manager->persist($committee4);
        $manager->persist($committee5);
        $manager->persist($committee6);
        $manager->persist($committee7);
        $manager->persist($committee8);
        $manager->persist($committee9);
        $manager->persist($committee10);
        $manager->persist($committee11);
        $manager->persist($committee12);

        // Make adherents join committees
        $adherent2 = $this->getReference('adherent-2');
        $adherent3 = $this->getReference('adherent-3');
        $adherent4 = $this->getReference('adherent-4');
        $adherent5 = $this->getReference('adherent-5');
        $adherent6 = $this->getReference('adherent-6');
        $adherent7 = $this->getReference('adherent-7');
        $adherent9 = $this->getReference('adherent-9');
        $adherent10 = $this->getReference('adherent-10');
        $adherent11 = $this->getReference('adherent-11');
        $adherent12 = $this->getReference('adherent-12');
        $adherent13 = $this->getReference('adherent-13');
        $adherent14 = $this->getReference('adherent-14');
        $adherent19 = $this->getReference('adherent-19');
        $adherent20 = $this->getReference('senatorial-candidate');
        $adherent21 = $this->getReference('municipal-manager-1');
        $adherent22 = $this->getReference('adherent-20');
        $referent = $this->getReference('adherent-8');
        $assessor = $this->getReference('assessor-1');

        $manager->persist($membership = $adherent7->superviseCommittee($committee3, \DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-26 16:08:24')));
        $membership->enableVote();
        $manager->persist($adherent7->superviseCommittee($committee4));
        $manager->persist($adherent7->superviseCommittee($committee5));
        $manager->persist($adherent2->followCommittee($committee1));
        $manager->persist($adherent2->followCommittee($committee6));
        $manager->persist($adherent4->followCommittee($committee1));
        $manager->persist($membership = $adherent5->hostCommittee($committee1));
        $membership->enableVote();
        $manager->persist($adherent6->followCommittee($committee2));
        $manager->persist($adherent4->followCommittee($committee2));
        $manager->persist($membership = $adherent3->superviseCommittee($committee1, \DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-12 13:25:54')));
        $membership->enableVote();
        $manager->persist($adherent3->hostCommittee($committee3));
        $manager->persist($adherent3->followCommittee($committee4));
        $manager->persist($adherent3->followCommittee($committee5));
        $manager->persist($adherent22->followCommittee($committee5));
        $manager->persist($adherent3->followCommittee($committee6));
        $manager->persist($adherent3->followCommittee($committee7));
        $manager->persist($adherent3->followCommittee($committee8));
        $manager->persist($adherent3->followCommittee($committee9));
        $manager->persist($membership = $adherent9->superviseCommittee($committee6));
        $membership->enableVote();
        $manager->persist($membership = $adherent10->superviseCommittee($committee7));
        $membership->enableVote();
        $manager->persist($adherent9->followCommittee($committee5));
        $manager->persist($membership = $adherent11->superviseCommittee($committee8));
        $membership->enableVote();
        $manager->persist($membership = $adherent12->superviseCommittee($committee9));
        $membership->enableVote();
        $manager->persist($adherent11->followCommittee($committee9));
        $manager->persist($membership = $referent->superviseCommittee($committee10));
        $membership->enableVote();
        $manager->persist($adherent13->followCommittee($committee10));
        $manager->persist($adherent14->followCommittee($committee10));
        $manager->persist($adherent13->followCommittee($committee11));
        $manager->persist($adherent14->followCommittee($committee11));
        $manager->persist($assessor->followCommittee($committee5));
        $manager->persist($assessor->followCommittee($committee6));
        $manager->persist($assessor->followCommittee($committee7));
        $manager->persist($assessor->followCommittee($committee8));
        $manager->persist($adherent19->followCommittee($committee4));
        $manager->persist($membership = $adherent19->followCommittee($committee5));
        $membership->enableVote();
        $manager->persist($adherent20->superviseCommittee($committee12, new \DateTime('-2 months')));
        $manager->persist($adherent13->followCommittee($committee12));
        $manager->persist($adherent14->followCommittee($committee12));
        $manager->persist($adherent21->followCommittee($committee12, new \DateTime('-2 months')));

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            LoadAdherentData::class,
            LoadDesignationData::class,
        ];
    }
}
