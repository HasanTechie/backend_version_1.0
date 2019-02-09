<?php

use Goutte\Client;

use Illuminate\Database\Seeder;

class GatheringHotels_hoteleasyreservationsdotcom_ScrapingDataSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
//        $client = new Client();
//
        $url = "https://www.gruppoloan.it/hdc/en/";
//        $crawler = $client->request('GET', $url);
//
//        $form = $crawler->filter('form[name="formFR"]')->form();
//        $crawler = $client->submit($form, array('datepicker' => date("Y-m-d")));
//
//        dd($crawler->text());

        $client = new Client();
//        $crawler = $client->request('GET', 'https://www.gruppoloan.it/hdc/en/');
//        // select the form and fill in some values
//        $form = $crawler->filter('div.col-sm-12.col-md-11.col-md-offset-1.content_fastR > form')->form();
//        $form['datepicker'] = date("Y-m-d", strtotime("+25 day", strtotime(date("Y-m-d"))));
//        // submit that form
//        $crawler = $client->submit($form);


        $crawler = $client->request('GET', $url);
        $form = $crawler->filter("div.col-sm-12.col-md-11.col-md-offset-1.content_fastR > form")->form();
        $form['datepicker'] = date("d/m/Y", strtotime("+25 day", strtotime(date("Y-m-d"))));
        dd($form);
        $form['NUMNOTTI'] = 1;
        $form['NUMCAMERE'] = 1;
        $form['NUMPERSONE'] = 1;
//
        $crawler = $client->submit($form);
//        $crawler = $client->submit($form, array('datepicker' => date("d/m/Y", strtotime("+25 day", strtotime(date("Y-m-d")))), 'NUMNOTTI' => 1, 'NUMCAMERE' => 1, 'NUMPERSONE' => 1));
//        $crawler->filter('.flash-error')->each(function ($node) {
//            print $node->text() . "\n";
//        });

//        dd($crawler->nodeName());



        $crawler = $client->request('GET', 'https://www.hoteleasyreservations.com/her5th/(S(5w2mzgy135fdvxlrwqaqi0i1))/her/pren1.aspx?_ga=&EASYCHATSESSION=');
//
//
        $crawler->filter(".cellflex")->each(function ($node) {
            print $node->text() . "\n";
        });

    }
}
