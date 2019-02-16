<?php

use Illuminate\Database\Seeder;

class EnteringHotelsDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //


        $hotelArray = array
        (
/*
            //SHG chain
            array(
                'id_albergo' => 15685,
                'dc' => 3128,
                'name' => 'Hotel PortaMaggiore',
                'address' => 'Piazza di Porta Maggiore, 25, 00185 Roma RM, Italy',
                'city' => 'Rome',
                'country' => 'Italy',
                'phone' => '+39 06 7027927',
                'email' => 'info@shghotelportamaggiore.com',
                'website' => 'hotelportamaggiore.it',
                'chain' => 'salutehospitalitygroup.com'
            ),
            array(
                'id_albergo' => 16102,
                'dc' => 2391,
                'name' => 'SHG Hotel Antonella',
                'address' => 'Via Pontina, 00040 Pomezia RM, Italy',
                'city' => 'Pomezia',
                'country' => 'Italy',
                'phone' => '+39 06 911481',
                'email' => 'info@shghotelantonella.com',
                'website' => 'shghotelantonella.com',
                'chain' => 'salutehospitalitygroup.com'
            ),
            array(
                'id_albergo' => 16834,
                'dc' => 4753,
                'name' => 'SHG Hotel Villa Carlotta',
                'address' => 'Via Mazzini, 121, 28832 Belgirate VB, Italy',
                'city' => 'Belgirate',
                'country' => 'Italy',
                'phone' => '+39 0322 76461',
                'email' => 'info@shghotelvillacarlotta.com',
                'website' => 'shghotelvillacarlotta.com',
                'chain' => 'salutehospitalitygroup.com'
            ),
            array(
                'id_albergo' => 11338,
                'dc' => 333,
                'name' => 'SHG Hotel Salute Palace',
                'address' => 'Dorsoduro 222/a, 30123 Venice VE, Italy',
                'city' => 'Venice',
                'country' => 'Italy',
                'phone' => '+39 041 5235404',
                'email' => 'info@salutepalace.com',
                'website' => 'salutepalace.com',
                'chain' => 'salutehospitalitygroup.com'
            ),
            array(
                'id_albergo' => 5986,
                'dc' => 158,
                'name' => 'Villa Porro Pirelli',
                'address' => 'Via Tabacchi, 20, 21056 Induno Olona VA, Italy',
                'city' => 'Varese',
                'country' => 'Italy',
                'phone' => '+39 0332 840540',
                'email' => 'info@villaporropirelli.com',
                'website' => 'villaporropirelli.com',
                'chain' => 'salutehospitalitygroup.com'
            ),
            array(
                'id_albergo' => 11226,
                'dc' => 445,
                'name' => 'SHG Hotel de la Ville',
                'address' => 'Viale Verona, 12, 36100 Vicenza VI, Italy',
                'city' => 'Vicenza',
                'country' => 'Italy',
                'phone' => '+39 0444 549001',
                'email' => 'info@hoteldelavillevicenza.com',
                'website' => 'hoteldelavillevicenza.com',
                'chain' => 'salutehospitalitygroup.com'
            ),
            array(
                'id_albergo' => 14538,
                'dc' => 8856,
                'name' => 'SHG Hotel Catullo',
                'address' => 'Viale del Lavoro, 35, 37036 San Martino Buon Albergo VR, Italy',
                'city' => 'Verona',
                'country' => 'Italy',
                'phone' => '+39 045 99 50 00',
                'email' => 'info@shghotelcatullo.com',
                'website' => 'shghotelcatullo.com',
                'chain' => 'salutehospitalitygroup.com'
            ),
            array(
                'id_albergo' => 12104,
                'dc' => 8389,
                'name' => 'SHG Hotel Verona',
                'address' => 'Via Unità d\'Italia, 346, 37132 Verona VR, Italy',
                'city' => 'Verona',
                'country' => 'Italy',
                'phone' => '+39 045 895 2501',
                'email' => 'info@shghotelverona.com',
                'website' => 'shghotelverona.com',
                'chain' => 'salutehospitalitygroup.com'
            ),
            array(
                'id_albergo' => 12879,
                'dc' => 8759,
                'name' => 'SHG Grand Hotel Milano Malpensa',
                'address' => 'Via Lazzaretto, 1, 21019 Somma Lombardo VA, Italy',
                'city' => 'Somma Lombardo',
                'country' => 'Italy',
                'phone' => '+39 0331 951220',
                'email' => 'info@milanohotelmalpensa.com',
                'website' => 'grand-hotelmilanomalpensa.com',
                'chain' => 'salutehospitalitygroup.com'
            ),
            array(
                'id_albergo' => 17170,
                'dc' => 9129,
                'name' => 'Hotel Bologna',
                'address' => 'Via Risorgimento, 186, 40069 Zola Predosa BO, Italy',
                'city' => 'Zola Predosa',
                'country' => 'Italy',
                'phone' => '+39 051 751 101',
                'email' => 'info@shghotelbologna.com',
                'website' => 'shghotelbologna.com',
                'chain' => 'salutehospitalitygroup.com'
            ),

            //premierhotels
            array(
                'id_albergo' => '995',
                'dc' => '110811',
                'name' => 'Hotel Alla Rocca',
                'address' => 'Loc. Bazzano, Via G. Matteotti, 76, 40053 Valsamoggia BO, Italy',
                'city' => 'Valsamoggia',
                'country' => 'Italy',
                'phone' => '+39 051 831217',
                'email' => 'info@allarocca.com ',
                'website' => 'allarocca.com',
                'chain' => 'premierhotels.it'
            ),

            array(
                'id_albergo' => '997',
                'dc' => '1810812',
                'name' => 'Hotel Cube Ravenna',
                'address' => '48100, Via Luigi Masotti, 2, 48124 Ravenna RA, Italy',
                'city' => 'Ravenna',
                'country' => 'Italy',
                'phone' => '+39 0544 464691',
                'email' => 'info@hotelcube.net',
                'website' => 'hotelcube.net',
                'chain' => 'premierhotels.it'
            ),

            array(
                'id_albergo' => '991',
                'dc' => '1810845',
                'name' => 'Hotel Sorriso Milano Marittima',
                'address' => 'Via VIII Traversa, 19, 48015 Milano Marittima RA, Italy',
                'city' => 'Milano Marittima',
                'country' => 'Italy',
                'phone' => '+39 0544 994063',
                'email' => 'info@sorrisohotel.net',
                'website' => 'sorrisohotel.net',
                'chain' => 'premierhotels.it'
            ),

            array(
                'id_albergo' => '985',
                'dc' => '1810823',
                'name' => 'Hotel Le Palme Milano Marittima',
                'address' => 'Traversa VII Mare, 12, 48015 Milano Marittima, Cervia RA, Italy',
                'city' => 'Milano Marittima',
                'country' => 'Italy',
                'phone' => '+39 0544 994661',
                'email' => 'lepalme@premierhotels.it',
                'website' => 'hotellepalme.it',
                'chain' => 'premierhotels.it'
            ),

            array(
                'id_albergo' => '1305',
                'dc' => '27020862',
                'name' => 'Hotel Premier & Suites Premier Resort Milano Marittima',
                'address' => 'Via VII Traversa Mare, 15, 48015 Milano Marittima RA, Italy',
                'city' => 'Milano Marittima',
                'country' => 'Italy',
                'phone' => '+39 0544 995839',
                'email' => 'premiersuites@premierhotels.it',
                'website' => 'premierandsuites.it',
                'chain' => 'premierhotels.it'
            ),

            array(
                'id_albergo' => '983',
                'dc' => '1810812',
                'name' => 'Hotel Waldorf - Premier Resort Milano Marittima',
                'address' => 'Traversa VII Mare, 17, 48015 Milano Marittima RA, Italy',
                'city' => 'Milano Marittima',
                'country' => 'Italy',
                'phone' => '+39 0544 994343',
                'email' => 'waldorf@premierhotels.it',
                'website' => 'hotelwaldorf.it',
                'chain' => 'premierhotels.it'
            ),

            //italianhospitalitycollection

            array(
                'id_albergo' => '6049',
                'dc' => '543',
                'name' => 'Chia Laguna Hotel',
                'address' => 'Viale Belvedere, 09010 Domus De Maria CA, Italy',
                'city' => 'Sardinia',
                'country' => 'Italy',
                'phone' => '+39 070 92391',
                'email' => 'info@chialagunaresort.com',
                'website' => 'chialagunaresort.com',
                'chain' => 'italianhospitalitycollection.com'
            ),
            array(
                'id_albergo' => '10333',
                'dc' => '531',
                'name' => 'Chia Hotel Spazio Oasi',
                'address' => 'Viale Belvedere, 09010 Chia, Domus de Maria, Sardinia, Italy',
                'city' => 'Sardinia',
                'country' => 'Italy',
                'phone' => '+39 070 9239 3431',
                'email' => 'info@chialagunaresort.com',
                'website' => 'en.chialagunaresort.com/en/our-hotels/hotel-spazio-oasi/description/106-0.html',
                'chain' => 'italianhospitalitycollection.com'
            ),


            array(
                'id_albergo' => '6052',
                'dc' => '237',
                'name' => 'Chia Laguna - Hotel Village ',
                'address' => 'Viale Belvedere, 09010 Chia, Domus de Maria, Sardinia, Italy',
                'city' => 'Sardinia',
                'country' => 'Italy',
                'phone' => '+39 070 92391',
                'email' => 'info@chialagunaresort.com',
                'website' => 'en.chialagunaresort.com/en/our-hotels/hotel-village/hotel-village/150-0.html',
                'chain' => 'italianhospitalitycollection.com'
            ),

            array(
                'id_albergo' => '6058',
                'dc' => '781',
                'name' => 'Chia Laguna - Hotel Baia',
                'address' => 'Viale Belvedere, 09010 Chia, Domus de Maria, Sardinia, Italy',
                'city' => 'Sardinia',
                'country' => 'Italy',
                'phone' => '+39 070 92391',
                'email' => 'info@chialagunaresort.com',
                'website' => 'en.chialagunaresort.com/en/our-hotels/hotel-baia/hotel-baia/151-0.html',
                'chain' => 'italianhospitalitycollection.com'
            ),

            array(
                'id_albergo' => '19122',
                'dc' => '9629',
                'name' => 'Le Massif',
                'address' => 'Strada regionale, 38, 11013 Courmayeur AO, Italy',
                'city' => 'Courmayeur',
                'country' => 'Italy',
                'phone' => '+39 02 843 45 670',
                'email' => null,
                'website' => 'lemassifcourmayeur.com/en/',
                'chain' => 'italianhospitalitycollection.com'
            ),

            array(
                'id_albergo' => '31',
                'dc' => '466',
                'name' => 'Fonteverde',
                'address' => 'Località Terme, 1, 53040 San Casciano dei Bagni SI, Italy',
                'city' => 'Tuscany',
                'country' => 'Italy',
                'phone' => '+39 0578 57241',
                'email' => 'info@fonteverdespa.com',
                'website' => 'fonteverdespa.com',
                'chain' => 'italianhospitalitycollection.com'
            ),

            array(
                'id_albergo' => '487',
                'dc' => '33',
                'name' => 'Bagni di Pisa',
                'address' => 'Largo Shelley, 18, 56017 San Giuliano Terme PI, Italy',
                'city' => 'Tuscany',
                'country' => 'Italy',
                'phone' => '+39 050 88501',
                'email' => 'info@bagnidipisa.com',
                'website' => 'bagnidipisa.com',
                'chain' => 'italianhospitalitycollection.com'
            ),

            array(
                'id_albergo' => '32',
                'dc' => '471',
                'name' => 'Grotta Giusti',
                'address' => 'Via della Grotta Giusti, 1411, 51015 Monsummano Terme PT, Italy',
                'city' => 'Tuscany',
                'country' => 'Italy',
                'phone' => '+39 0572 90771',
                'email' => 'info@grottagiustispa.com',
                'website' => 'grottagiustispa.com',
                'chain' => 'italianhospitalitycollection.com'
            ),
//
            array(
                'id_albergo' => '1433',
                'dc' => '34560',
                'name' => 'Devero Hotel',
                'address' => 'Largo Kennedy, 1, 20873 Cavenago di Brianza MB, Italy',
                'city' => 'Cavenago di Brianza',
                'country' => 'Italy',
                'phone' => '+39 02 9533 5412',
                'email' => ' info@deverohotel.it',
                'website' => 'deverohotel.it'
            ),
//
            array(
                'id_albergo' => '2055',
                'dc' => '123',
                'name' => 'Hotel Veronesi La Torre',
                'address' => 'Via Monte Baldo, 22, 37062 Dossobuono di Villafranca VR, Italy',
                'city' => 'Verona',
                'country' => 'Italy',
                'phone' => '+39 045 860 4811',
                'email' => null,
                'website' => 'hotelveronesilatorre.it'
            ),
//
            array(
                'id_albergo' => '11987',
                'dc' => '6361',
                'name' => 'HOTEL QC Terme Monte Bianco',
                'address' => '11010 Palleusieux Aosta Valley, Italy',
                'city' => 'Aosta Valley',
                'country' => 'Italy',
                'phone' => '+39 0165 87004',
                'email' => 'info@qctermemontebianco.it',
                'website' => 'qctermemontebianco.it'
            ),
//
            array(
                'id_albergo' => '13435',
                'dc' => '8907',
                'name' => 'Camping Village Rocchette',
                'address' => 'Strada Provinciale delle Rocchette, snc, 58043 Castiglione della Pescaia GR, Italy',
                'city' => 'Castiglione della Pescaia',
                'country' => 'Italy',
                'phone' => '+39 0564 941123',
                'email' => 'info@rocchette.com',
                'website' => 'rocchette.com'
            ),
//
            array(
                'id_albergo' => '146',
                'dc' => '785',
                'name' => 'Cosmo Hotel Palace',
                'address' => 'Via F. de Sanctis, 5, 20092 Cinisello Balsamo MI, Italy',
                'city' => 'Cinisello Balsamo',
                'country' => 'Italy',
                'phone' => '+39 02 617771',
                'email' => 'palace@hotelcosmo.com',
                'website' => 'cosmohotelpalace.it'
            ),

//
            array(
                'id_albergo' => '17024',
                'dc' => '6427',
                'name' => 'Grand Hotel San Pietro',
                'address' => 'Via Luigi Pirandello, 50, 98039 Taormina ME, Italy',
                'city' => 'Taormina',
                'country' => 'Italy',
                'phone' => '+39 0942 620711',
                'email' => 'info.sanpietro@lindberghhotels.it',
                'website' => 'sanpietrotaormina.com'
            ),
//
            array(
                'id_albergo' => '175',
                'dc' => '251',
                'name' => 'Hotel Le Fontanelle',
                'address' => 'Km 14, Strada Provinciale 408 di Montevarchi, 53019 Castelnuovo Berardenga SI, Italy',
                'city' => 'Castelnuovo Berardenga',
                'country' => 'Italy',
                'phone' => '+39 0577 35751',
                'email' => 'info@hotelfontanelle.com',
                'website' => 'hotelfontanelle.com'
            ),
//
            array(
                'id_albergo' => '12231',
                'dc' => '8556',
                'name' => 'Almar Jesolo Resort & Spa',
                'address' => 'Via Dante Alighieri, 106, 30016 Lido di Jesolo VE, Italy',
                'city' => 'Lido di Jesolo',
                'country' => 'Italy',
                'phone' => '+39 0421 388111',
                'email' => 'info@almarjesolo.com',
                'website' => 'almarjesolo.com'
            ),
//
            array(
                'id_albergo' => '11059',
                'dc' => '2320',
                'name' => 'Hotel Zirmes',
                'address' => 'Str. de Pecé, 10, 38035 Moena TN, Italy',
                'city' => 'Moena',
                'country' => 'Italy',
                'phone' => '+39 0462 573160',
                'email' => 'info@hotelzirmesmoena.it',
                'website' => 'hotelzirmesmoena.it'
            ),
//
            array(
                'id_albergo' => '6346',
                'dc' => '216',
                'name' => 'Grand Hotel Savoia',
                'address' => 'Via Roma, 62, 32043 Cortina d\'Ampezzo BL, Italy',
                'city' => 'Cortina d\'Ampezzo',
                'country' => 'Italy',
                'phone' => '+39 0436 3201',
                'email' => null,
                'website' => 'grandhotelsavoiacortina.com'
            ),


// Escapade-Paris chain
            array(
                'id_albergo' => '10959',
                'dc' => '451',
                'name' => 'Hôtel Exelmans',
                'address' => '73 Rue Boileau, 75016 Paris, France',
                'city' => 'Paris',
                'country' => 'France',
                'phone' => '+33 1 42 24 94 66',
                'email' => 'exelmans@escapade-paris.com',
                'website' => 'en.escapade-paris.fr',
                'chain' => 'en.escapade-paris.fr'
            ),
            array(
                'id_albergo' => '10961',
                'dc' => '541',
                'name' => 'Hôtel France Eiffel',
                'address' => '8 Rue Saint-Charles, 75015 Paris, France',
                'city' => 'Paris',
                'country' => 'France',
                'phone' => '+33 1 45 79 33 35',
                'email' => 'france-eiffel@escapade-paris.com',
                'website' => 'en.escapade-paris.fr',
                'chain' => 'en.escapade-paris.fr'
            ),
            array(
                'id_albergo' => '10964',
                'dc' => '752',
                'name' => 'Hôtel Le Relais Saint Charles',
                'address' => '72 Boulevard de Grenelle, 75015 Paris, France',
                'city' => 'Paris',
                'country' => 'France',
                'phone' => '+33 1 40 58 00 57',
                'email' => 'relaissaintcharles@escapade-paris.com',
                'website' => 'en.escapade-paris.fr',
                'chain' => 'en.escapade-paris.fr'
            ),
            array(
                'id_albergo' => '10958',
                'dc' => '544',
                'name' => 'Hotel Orquídea',
                'address' => '65 Rue de l\'Ouest, 75014 Paris, France',
                'city' => 'Paris',
                'country' => 'France',
                'phone' => '+33 1 43 22 70 50',
                'email' => 'reservation@hotel-orchidee.fr',
                'website' => 'en.escapade-paris.fr',
                'chain' => 'en.escapade-paris.fr'
            ),
            array(
                'id_albergo' => null,
                'dc' => null,
                'name' => 'Hôtel Alixia Antony',
                'address' => '1 Avenue de la Providence, 92160 Antony, France',
                'city' => 'Paris',
                'country' => 'France',
                'phone' => '+33 1 46 74 92 92',
                'email' => 'accueil@hotelalixia.com',
                'website' => 'hotelalixia.com',
                'chain' => 'en.escapade-paris.fr'
            ),
            array(
                'id_albergo' => '11301',
                'dc' => '737',
                'name' => 'Hotel France Louvre',
                'address' => '40 Rue de Rivoli, 75004 Paris, France',
                'city' => 'Paris',
                'country' => 'France',
                'phone' => '+33 1 42 71 29 28',
                'email' => 'hotel@francelouvre.com',
                'website' => 'francelouvre.com',
                'chain' => 'en.escapade-paris.fr'
            ),
            array(
                'id_albergo' => null,
                'dc' => null,
                'name' => 'Hôtel de Berny',
                'address' => '129 Avenue Aristide Briand, 92160 Antony, France',
                'city' => 'Paris',
                'country' => 'France',
                'phone' => '+33 1 46 11 43 90',
                'email' => 'accueil@hotelberny.com',
                'website' => 'hotel-de-berny.com',
                'chain' => 'en.escapade-paris.fr'
            ),
            array(
                'id_albergo' => null,
                'dc' => null,
                'name' => 'Hôtel Le Relais Montmartre',
                'address' => '6 Rue Constance, 75018 Paris, France',
                'city' => 'Paris',
                'country' => 'France',
                'phone' => '+33 1 70 64 25 25',
                'email' => 'contact@relaismontmartre.fr',
                'website' => 'hotel-relais-montmartre.com',
                'chain' => 'en.escapade-paris.fr'
            ),
            array(
                'id_albergo' => '10962',
                'dc' => '541',
                'name' => 'Hôtel des Batignolles',
                'address' => '26–28 Rue des Batignolles, 75017 Paris, France',
                'city' => 'Paris',
                'country' => 'France',
                'phone' => '+33 1 43 87 70 40',
                'email' => 'hotel@batignolles.com',
                'website' => 'batignolles.com',
                'chain' => 'en.escapade-paris.fr'
            ),
            array(
                'id_albergo' => null,
                'dc' => null,
                'name' => 'Hôtel Splendor',
                'address' => '38 Rue Cardinet, 75017 Paris, France',
                'city' => 'Paris',
                'country' => 'France',
                'phone' => '+33 1 46 22 07 73',
                'email' => 'contact@hotel-splendor.com',
                'website' => 'hotel-splendor.com',
                'chain' => 'en.escapade-paris.fr'
            ),


// chain TicinoHotelsGroup.com/en/
            array(
                'id_albergo' => '91',
                'dc' => '123',
                'name' => 'Hotel Villa Principe Leopoldo & Spa',
                'address' => 'Via Montalbano 5, 6900 Lugano, Switzerland',
                'city' => 'Lugano',
                'country' => 'Switzerland',
                'phone' => '+41 91 985 88 55',
                'email' => 'info@leopoldohotel.com',
                'website' => 'leopoldohotel.com/en/',
                'chain' => 'ticinohotelsgroup.com/en/'
            ),
            array(
                'id_albergo' => '5209',
                'dc' => '230620096',
                'name' => 'Villa Sassa Hotel, Residence & Spa',
                'address' => 'Via Tesserete 10, 6900 Lugano, Switzerland',
                'city' => 'Lugano',
                'country' => 'Switzerland',
                'phone' => '+41 91 911 41 11',
                'email' => 'info@villasassa.ch',
                'website' => 'villasassa.ch',
                'chain' => 'ticinohotelsgroup.com/en/'
            ),
            array(
                'id_albergo' => '10146',
                'dc' => '842',
                'name' => 'Kurhaus Cademario Hotel & Spa',
                'address' => 'Lisone 12, 6936 Cademario, Switzerland',
                'city' => 'Cademario',
                'country' => 'Switzerland',
                'phone' => '+41 91 610 51 11',
                'email' => 'info@kurhauscademario.com',
                'website' => 'kurhauscademario.com/en/',
                'chain' => 'ticinohotelsgroup.com/en/'
            ),
            array(
                'id_albergo' => '13594',
                'dc' => '9987',
                'name' => 'Park Hotel Principe',
                'address' => 'Via Montalbano 19, 6925 Lugano, Switzerland',
                'city' => 'Lugano',
                'country' => 'Switzerland',
                'phone' => '+41 91 985 86 43',
                'email' => 'info@parkhotelprincipe.com',
                'website' => 'parkhotelprincipe.com/en/',
                'chain' => 'ticinohotelsgroup.com/en/'
            ),
            array(
                'id_albergo' => null,
                'dc' => null,
                'name' => 'E-Rooms Minusio',
                'address' => 'Via delle Vigne 149, 6648 Minusio, Switzerland',
                'city' => 'Minusio',
                'country' => 'Switzerland',
                'phone' => '+41 91 735 85 85',
                'email' => 'info@eroomsminusio.com',
                'website' => 'eroomsminusio.com',
                'chain' => 'ticinohotelsgroup.com/en/'
            ),
            array(
                'id_albergo' => null,
                'dc' => null,
                'name' => 'Esplanade Hotel Resort & Spa',
                'address' => 'Via delle Vigne 149, 6648 Minusio, Switzerland',
                'city' => 'Minusio',
                'country' => 'Switzerland',
                'phone' => '+41 91 911 47 23',
                'email' => null,
                'website' => null,
                'chain' => 'ticinohotelsgroup.com/en/'
            ),
*/

////              array(
//                'id_albergo' => 'null',
//                'dc' => 'null',
//                'name' => 'null',
//                'address' => 'null',
//                'city' => 'null',
//                'country' => 'null',
//                'phone' => 'null',
//                'email' => 'null',
//                'website' => 'null',
//                'chain' => 'null'
//            ),


        );
        if ($result1 = DB::table('hotels')->orderBy('s_no', 'desc')->first()) {
            global $j;
            $j = $result1->s_no;
        } else {
            $j = 0;
        }

        foreach ($hotelArray as $hotel) {

            DB::table('hotels')->insert([
                'uid' => uniqid(),
                's_no' => ++$j,
                'name' => $hotel['name'],
                'address' => $hotel['address'],
                'city' => $hotel['city'],
                'country' => $hotel['country'],
                'phone' => $hotel['phone'],
                'website' => $hotel['website'],
                'latitude' => null,
                'longitude' => null,
                'all_data' => serialize($hotel),

                'source' => 'reservations.verticalbooking.com',
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()')
            ]);

        }
    }
}
