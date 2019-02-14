<?php

use Illuminate\Database\Seeder;

class GettingKeywordsDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $columns = $this->getTableColumns('keywords_from_csv_new');

        $j = 0;

        foreach ($columns as $column) {

            $keywords = DB::table('keywords_from_csv_new')->select($column)->get();

            foreach ($keywords as $keyword) {
                DB::table('keywords')->insert([
                    'uid' => uniqid(),
                    's_no' => ++$j,
                    'keyword' => $keyword->$column,
                    'language' => $column,
                    'created_at' => DB::raw('now()'),
                    'updated_at' => DB::raw('now()')
                ]);
            }
        }

    }

    public function getTableColumns($table)
    {
        return DB::getSchemaBuilder()->getColumnListing($table);

        // Or

        // return Schema::getColumnListing($table);

    }
}
