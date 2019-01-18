<?php

use Illuminate\Database\Seeder;

class MergeHotelPlacesDataSeeder extends Seeder
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
        ini_set('memory_limit', '8192M');
        $places = DB::table('places')->get();
        foreach ($places as $place){
            if((strcasecmp($place->subCategory, 'hotel') == 0) || (stripos(strtolower($place->name),'hotel') !== false)) // case insensitive comparisons
                DB::table('hotels')->insert([
                    'name' => (isset($place->name) ? $place->name : 'Not Available'),
                    'tourpedia_id' => (isset($place->place_id) ? $place->place_id : null),
                    'total_rooms' => (isset($place->totalrooms) ? $place->totalrooms : null),
                    'country' => (isset($place->country) ? $place->country : 'Not Available'),
                    'city' => (isset($place->location) ? $place->location : 'Not Available'),
                    'address' => $place->address,
                    'international_phone' => (isset($place->international_phone_number) ? $place->international_phone_number : 'Not Available'),
                    'latitude' => $place->lat,
                    'longitude' => $place->lng,
                    'tourpedia_numReviews' => $place->numReviews,
                    'tourpedia_reviews' => $place->reviews,
                    'tourpedia_polarity' => $place->polarity,
                    'tourpedia_details' => $place->details,
                    'tourpedia_originalId' => $place->originalId,
                    'source' => $place->source,
                    'phone' => $place->phone_number,
                    'website' => $place->website,
                    'description' => $place->description,
                    'tourpedia_external_urls' => $place->external_urls,
                    'tourpedia_statistics' => $place->statistics,
                    'tourpedia_reviews_ids' => $place->reviews_ids,
                    'tourpedia_detailed_reviews' => $place->detailed_reviews,
                    'all_data' => serialize($place),
                    'created_at' => DB::raw('now()'),
                    'updated_at' => DB::raw('now()')
                ]);
        }
        */

            /*
             * !tourpedia_id = place_id
             * name = name
             * address = address
             * !!category
             * city = location
             * latitude = lat
             * longitude = lng
             * !tourpedia_numReviews = numReviews
             * !tourpedia_reviews = reviews
             * !tourpedia_polarity = polarity
             * !tourpedia_details = details
             * !tourpedia_originalId = originalId
             * !source = source
             * phone = phone_number
             * international_phone = international_phone_number
             * website = website
             * !description = description
             * !tourpedia_external_urls = external_urls
             * !tourpedia_statistics = statistics
             * !tourpedia_reviews_ids = reviews_ids
             * !tourpedia_detailed_reviews = detailed_reviews
             * all_data = all_data
             */
    }
}
