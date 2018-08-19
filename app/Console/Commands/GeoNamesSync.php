<?php

namespace App\Console\Commands;

use App\CitiesRU;
use App\UpdateConfig;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GeoNamesSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get_or_update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get or update cities data from geonames.org';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = 'temp';
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        $config = UpdateConfig::first();

        if (!$config) {
            UpdateConfig::firstOrCreate(['started'=>0])->first();

            }elseif($config['started'] == 1){
            return false;
        }
        $status = false;
        $dateString = date("Y-m-d", time());

        $last_config = UpdateConfig::first();
        $last_config->update(['started' => 1]);

        if(!$last_config['last_modified']) {

//            first time when table is empty

            file_put_contents("$path/RU.zip", fopen("http://download.geonames.org/export/dump/RU.zip", 'r'));
            exec("unzip $path/RU.zip -d $path");
            $status = $this->csvToArray("$path/RU.txt",true);

        }else if(strtotime($config['last_modified']) < strtotime($dateString)){

            file_put_contents("$path/modifications-$dateString.txt", fopen("http://download.geonames.org/export/dump/modifications-$dateString.txt", 'r'));
            $status = $this->csvToArray("$path/modifications-$dateString.txt");
        }
        $last_config->update(['last_modified' => $dateString,'started'=>0]);
        File::deleteDirectory($path);
        if ($status) {
            Log::info('Synchronization with geonames.org has been successfully');
        } else {
            Log::info('We have some issue with synchronization with geonames.org');
        }

    }

    private function csvToArray( $filePath, $first =false )
    {
        ini_set('memory_limit', '1024M');

        $fields = [
            'geonameid',
            'name',
            'asciiname',
            'alternatenames',
            'latitude',
            'longitude',
            'feature_class',
            'feature_code',
            'country_code',
            'cc2',
            'admin1_code',
            'admin2_code',
            'admin3_code',
            'admin4_code',
            'population',
            'elevation',
            'dem',
            'timezone',
            'modification_date',
        ];
        $filename = public_path($filePath);
        $delimiter = "\t";
        if (!file_exists($filename) || !is_readable($filename))
            return FALSE;
        $header = NULL;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 11000, $delimiter)) !== FALSE) {
                $row_key = [];
                foreach ($row as $key => $item) {
                    $row_key[$fields[$key]] = $item ? $item : null;
                }
                if ($first) {
                    CitiesRU::create($row_key);
                } elseif($row_key['country_code'] == 'RU'){
                   CitiesRU::firstOrCreate(['geonameid' => $row_key['geonameid']], $row_key);
                }

            }
            fclose($handle);
        }
        return true;
    }
}
