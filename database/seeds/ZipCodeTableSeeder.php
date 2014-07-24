<?php namespace Ninjaparade\Shipping\Database\Seeds;

use DB;
use Log;
// use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use \Ninjaparade\Shipping\Models\Zipcode as Model;

class ZipCodeTableSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

		// DB::table('zipcodes')->truncate();

		$csvFile = __DIR__.'/zips.csv';
			
		$areas = $this->csv_to_array($csvFile);

	
		foreach($areas as $row)
        {
        	
        	$zip = Model::where('zip' , '=', $row['zip'])->first();
        	
        	if(!count($zip))
        	{
        		Log::info("Inserting new recored");

                $city  = Str::title($row['city'] );

        		Zipcode::create(
        			array(
            			'city' => $city,
            			'zip' => Str::upper(str_replace(' ', '', $row['zip'] ) ),
            			'state' => 'BC',
            			'country' => 'Canada',
            			'updated_at' => new DateTime,
            			'created_at' => new DateTime,
            		)
            	);

        		Log::info("created " . $row['city']. " " .$row['zip']);
			}
        		
        }

        Log::info("Done inserting ". count($areas). ' rows');
		

	}

	public function csv_to_array($filename='', $delimiter=',')
    {
        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;
     
        $header = NULL;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {   
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
            {
                if(!$header){
                    $header = $row;
                }
                else{
                    if(count($row) > 1)
                        $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    }
}
