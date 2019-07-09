<?php

use Goutte\Client;
use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Seeder;

class Gathering_globaldotmomondodotcom_ScrapingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
/*
        $url = "https://global.momondo.com/hotel-search/Berlin,Germany-c9109/2019-02-22/2019-02-23/2adults?sort=rank_a&fs=price-options=-dealonly";
        $url = "https://www.booking.com/searchresults.html?city=-1746443&checkin=2019-02-21&checkout=2019-02-22&selected_currency=USD&lang=en&utm_term=city-M1746443&do_availability_check=1&utm_campaign=us&utm_medium=dsk-hcompareto&label=metakayak-linkdsk-hcomparetous-city-M1746443_xqdz-adbb743e935337299d64c6206318e981_los-01_bw-003_curr-USD_nrm-01_gst-02_lang-en_clkid-SaO3ViqA5ynZihpWk1TfR595_Asaw8Rj-yZrY00iBDAq25rvckm_xlg%3D%3D&cpchash=adbb743e935337299d64c6206318e981&utm_source=metakayak&utm_content=los-1_nrm-1_gst-2&aid=344269";
        $url = "view-source:https://www.kayak.com/hotels/Berlin,Germany-c9109/2019-02-21/2019-02-22/2adults?sort=rank_a&attempt=2&lastms=1550482242857&force=true&fs=price-options=-dealonly";
        $url = "https://www.hrs.com/en/hotel/berlin/d-55133";
        $url = "https://www.eurobookings.com/search.html?q=cur:USD;dsti:536;dstt:1;dsts:Berlin;start:2019-02-19;end:2019-02-20;fac:0;stars:;rad:0;wa:0;offset:1;rmcnf:1[2,0];sf:1;";
        $url = "https://www.eurobookings.com/berlin-hotels-de/park-inn-by-radisson-berlin-alexanderplatz.html?q=start:2019-03-19;end:2019-03-20;rmcnf:1[2,0];dsti:536;dstt:1;dsts:Berlin;cur:USD;sort:0_desc;frm:1;";
        $url = "https://www.hotels.com/search.do?resolved-location=CITY%3A332483%3AUNKNOWN%3AUNKNOWN&destination-id=332483&q-destination=Berlin,%20Germany&q-check-in=2019-03-13&q-check-out=2019-03-14&q-rooms=1&q-room-0-adults=2&q-room-0-children=0";
        $url = "http://hotel.ebdestinations.com/searchresults.html?aid=808942;checkin=2019-02-18;checkout=2019-02-19;ctoken=i2uhx1zckg3JKw4ntGJREM2jd3MjhP66g8IjmLS9UaTpr_woqsYusMJoM1c2CXoWK3g8RC88Zp6vOuIlLEPFGDiR3t3Rgn-HkVF_cU3Uev4ysGxOJb-xN_e14Hg1-vXa7B92jBVnDLScpIhJArzFbW1fLXO7NdE6dxX3BaA4Z4AUP5ZS77EZ_v32k0gT7pSan-3PNJ0aLSlcvbbcTymU03fZ8z37Cii3Kq_jSN8X7MtLdeKTf-s1D7cGV7HRNzqNPo7M0hMDCoUXgc8Eazysus91KN4vUuzx9Ap8m882p8CargpDTHFlwLGnvEqmhpbGblQYWX5umay9l0ms0bxD2rbfBavcCeIcJ05LnJgGsScWGGFJxwUuriDeef6z_PNcfrxREwZg60UNc8S0jiozWbsJlbuUGfR-OZZLqw;dest_id=-1746443;dest_type=city;fp_referrer_aid=1181742;label=europeanbd-hotelspage-sb-click-topbox;lang=en-gb;si=ai%2Cco%2Cci%2Cre%2Cdi;sp_plprd=UmFuZG9tSVYkc2RlIyh9YVXcKaaJl1ClMyi_Ca8_9pvLiJ8EjFEKC5FQs4DRqWX3-v6xg9XbpcI08jlKi2YwMaqeRBuBZgGIFfl0sFYu_qrxDr6ZFwwPa1gJ-0vOZuaPKtelNspadWVluYYDT_veMw;ss=Berlin%2C%20Berlin%20Federal%20State%2C%20Germany;submit=Search&utm_campaign=sb1&utm_medium=sp&utm_source=AW_Searchbox&";


        echo $url;

        $client = new Client();

        $crawler = $client->request('GET', $url);

        $data = $crawler->filter('.room_link')->each(function ($node) {

            return $node->text();
        });
        dd($data);
        Storage::put('myfile.html', $crawler->html());
//        dd($crawler->html());*/
    }
}
