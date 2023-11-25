<?php

namespace Peergum\GeoDB\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;
use Peergum\GeoDB\Models\Country;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use ZipArchive;

class GeoDBInstall extends Command
{
    const VERSION = "v1.0-beta";
    const GEONAME_DOWNLOAD_URL = "https://download.geonames.org/export/dump/";
    const COUNTRIES = 'AD AE AF AG AI AL AM AN AO AQ AR AS AT AU AW AX AZ BA BB BD BE BF BG BH BI BJ BL BM BN BO BQ BR BS BT BV BW BY BZ CA CC CD CF CG CH CI CK CL CM CN CO CR CS CU CV CW CX CY CZ DE DJ DK DM DO DZ EC EE EG EH ER ES ET FI FJ FK FM FO FR GA GB GD GE GF GG GH GI GL GM GN GP GQ GR GS GT GU GW GY HK HM HN HR HT HU ID IE IL IM IN IO IQ IR IS IT JE JM JO JP KE KG KH KI KM KN KP KR KW KY KZ LA LB LC LI LK LR LS LT LU LV LY MA MC MD ME MF MG MH MK ML MM MN MO MP MQ MR MS MT MU MV MW MX MY MZ NA NC NE NF NG NI NL NO NP NR NU NZ OM PA PE PF PG PH PK PL PM PN PR PS PT PW PY QA RE RO RS RU RW SA SB SC SD SE SG SH SI SJ SK SL SM SN SO SR SS ST SV SX SY SZ TC TD TF TG TH TJ TK TL TM TN TO TR TT TV TW TZ UA UG UM US UY UZ VA VC VE VG VI VN VU WF WS XK YE YT ZA ZM ZW';
    const COUNTRY_LIST_FILE = "countryInfo.txt";
    const MAX_LINES_PER_BATCH = 30000;
    const MAX_BYTES_PER_BATCH = 3000000;
    const MAX_BATCH_SIZE = 1000;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geodb:install
    {--U|update : force redownload of geonames files if they exist}
    {country?* : countries to add/update (2-letter names) [default is set in geodb config]}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download required geonames files and update geodb tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("------------------");
        $this->info(" GeoDB installing");
        $this->info(" v.{self::VERSION}");
        $this->info("------------------");

        // create data folders if necessary
        $geodbStorage = storage_path("geodb");
        $geodbZipStorage = storage_path("geodb/zip");
        if (!file_exists($geodbStorage)) {
            mkdir($geodbStorage);
        }
        if (!file_exists($geodbZipStorage)) {
            mkdir($geodbZipStorage);
        }

        $this->info("Updating GeoDB tables...");

        // retrieving country list
        $this->info("Updating country list...");
        $countryListFile = $geodbStorage . "/" . self::COUNTRY_LIST_FILE;
        try {
            if (!file_exists($countryListFile) || config('geodb.update', false)) {
                $countryData = file_get_contents(config('geodb.download_url', self::GEONAME_DOWNLOAD_URL)
                    . self::COUNTRY_LIST_FILE);
                if ($countryData) {
                    file_put_contents($countryListFile, $countryData);
                }
            } else {
                $countryData = file_get_contents($countryListFile);
            }
        } catch (Exception $err) {
            $this->error(" FAILED");
        }
        $lines = preg_split("/[\r\n]+/", $countryData);
        if ($lines === false) {
            $this->error("FAILED. Can't read file.");
        }
        DB::transaction(function () use ($lines) {
            $lineCount = count($lines);
            $numLine = 1;
            $recordCount = 0;
            $records = [];
            $this->info("Parsing data (${lineCount} lines)...");
            $prevPercentRead = -1;
            foreach ($lines as $line) {
                $percentRead = floor($numLine * 100 / $lineCount);
                if ($prevPercentRead != $percentRead) {
//                    echo sprintf("\rParsed % 3d%% (${numLine}/${lineCount})", $percentRead);
                    $prevPercentRead = $percentRead;
                }
                if (preg_match('/^(#.*)*$/', $line)) {
                    $numLine++;
                    continue;
                }
                $fields = preg_split("/\t/", $line);
                $records[$recordCount] = [
                    'name' => $fields[4],
                    'cc' => $fields[0],
                    'cc2' => $fields[1],
                    'sqkm' => $fields[6],
                    'population' => $fields[7],
                    'continent' => $fields[8],
                    'tld' => $fields[9],
                    'currency_code' => $fields[10],
                    'currency_name' => $fields[11],
                    'lang' => $fields[15],
                ];
                $numLine++;
                $recordCount++;
//                if ($recordCount > $batchSize) {
//                    // write to database
//                    DB::table('countries')->upsert($records, ['cc'], ['cc2', 'name', 'lang']);
//                    $recordCount = 0;
//                    $records = [];
//                }
            }
            if ($recordCount) {
                DB::table('countries')->upsert($records, ['cc'], ['name', 'lang','cc2','sqkm','population','continent','tld','currency_code','currency_name']);
            }
            DB::commit();
        });
        $this->info("Parsing DONE.");

        $countryIds = [];
        foreach (DB::table('countries')->select('id', 'cc')->get() as $country) {
            $countryIds[$country->cc] = $country->id;
        }

        $countries = implode(' ',$this->argument('country')) ?: config('geodb.countries', 'all');
        $forceUpdate = $this->option('update');

        if (!$countries) {
            die("No country listed in geodb config file");
        }
        // in case we want all, use separate files to require less memory overall
        if ($countries == 'all') {
            $countries = self::COUNTRIES;
        }

        $uCountries = strtoupper($countries);
        $this->line("you're about to update data for the following countries: {$countries}");
        if (!$this->confirm("Do you confirm?",true)) {
            $this->info("Operation aborted. Please check use -h for details or configure geodb");
            die();
        }

        $countryArray = preg_split("/[ ,]+/", $countries);
        $countryNumber = count($countryArray);
        $loadedCountries = [];
        $countryCount = 1;
        $totalCities = 0;
        $totalStates = 0;
        $section1 = $this->output->getOutput()->section();
        $section2 = $this->output->getOutput()->section();
        $progressCountry = new ProgressBar($section1);
        $progressCountry->start(count($countryArray));
        $progressData = new ProgressBar($section2);
        $progressCountry->clear();
        foreach ($countryArray as $country) {
            $numCities = 0;
            $numStates = 0;
            if ($country == 'ALL') {
                // if 'ALL' in upper case, use the big file...
                $countryFile = "allCountries";
            } else {
                $countryFile = strtoupper($country);
            }
//            echo "-> ${country} [{$countryCount}/{$countryNumber}] ...";
            $zipFile = config('geodb.download_url', self::GEONAME_DOWNLOAD_URL) . $countryFile . ".zip";
            $storedZipFile = $geodbStorage . '/' . $countryFile . ".zip";
            $countryDataFile = $geodbStorage . "/" . $countryFile . ".txt";
            if (!file_exists($countryDataFile) || config('geodb.update', false) || $forceUpdate) {
                $zipped = file_get_contents($zipFile);
                if ($zipped !== false) {
                    if (file_put_contents($storedZipFile, $zipped) === false) {
                        die("FAILED saving");
                    };
                } else {
                    die(" FAILED");
                }
//                echo " DONE.\n";
//                echo "   Unzipping...";
                $zip = new ZipArchive;
                $res = $zip->open($storedZipFile);
                if ($res === TRUE) {
                    $zip->extractTo($geodbStorage);
                    $zip->close();
                } else {
                    die(' FAILED.');
                }
                unset($zip);
                rename($storedZipFile, $geodbZipStorage . "/" . $countryFile . ".zip");
//                echo " DONE.\n";
            }
            $fsize = filesize($countryDataFile);
            if ($fsize < 1024) {
                $ffsize = $fsize . 'B';
            } else if ($fsize < 1024 * 1024) {
                $ffsize = sprintf("%.1fKB", $fsize / 1024);
            } else if ($fsize < 1024 * 1024 * 1024) {
                $ffsize = sprintf("%.1fMB", $fsize / (1024 * 1024));
            } else {
                $ffsize = sprintf("%.3fGB", $fsize / (1024 * 1024 * 1024));
            }
//            $this->info("File size: {$fsize}");
            $fCountry = fopen($countryDataFile, "r");
            if (!$fCountry) {
                die("FAILED to open data file");
            }
            $batchSize = config('geodb.batch_size', self::MAX_BATCH_SIZE);
            if ($batchSize > self::MAX_BATCH_SIZE) {
                echo "NOTE: `batch_size` in geodb config file isset to {$batchSize}\n      -> adjusted to {self::MAX_BATCH_SIZE}\n";
            }

            $batchNum = 1;
            $data = "";
            $sizeRatio = ceil($fsize / 100);
            $progressData->start(ceil($fsize / $sizeRatio));
            $numLine = 1;
            $cityCount = 0;
            $stateCount = 0;
            $cities = [];
            $states = [];
            $progressCountry->setFormat('[%bar%] %current:2d%/%max:2d% %percent:3s%% | %elapsed:7s% / %remaining:7s% left | totals: %cities% cities, %states% states');
            $progressCountry->setMessage($totalCities, 'cities');
            $progressCountry->setMessage($totalStates, 'states');
            $progressCountry->minSecondsBetweenRedraws(1);
            $progressCountry->maxSecondsBetweenRedraws(1);
            $progressData->setFormat('[%bar%] %filesize% %percent:3s%% | %elapsed:7s% / %remaining:7s% | %memory:6s% | current file: %country%, %cities% cities, %states% states');
            $progressData->setMessage($country, 'country');
            $progressData->setMessage($ffsize, 'filesize');
            $progressData->minSecondsBetweenRedraws(1);
            $progressData->maxSecondsBetweenRedraws(1);
            $sizeRead = 0;
            $progressCountry->display();
            $progressData->display();
            do {
                $progressData->setMessage($numCities, 'cities');
                $progressData->setMessage($numStates, 'states');
//                $packet = fread($fCountry, self::MAX_BYTES_PER_BATCH);
//                if ($packet === false) {
//                    die(". FAILED. Can't read file.");
//                }
//                // concatenate new packet to remainder of data
//                $newData = $data . $packet;
//                $data = $newData;
//                // we limit the number of elements in the array, to avoid mem overuse / errors
////                $lines = preg_split("/[\r\n]+/", $data, self::MAX_LINES_PER_BATCH);
//                $lines = preg_split("/[\r\n]+/", $data);
//                if ($lines === false) {
//                    die(". FAILED. Can't read file.");
//                }
//                $lineCount = count($lines);
////                echo ", ${lineCount} lines...\n";
//                $prevPercentRead = -1;
                $line = fgets($fCountry);
                $sizeRead += strlen($line);
//                $this->info($line);
//                foreach ($lines as $line) {
//                    if ($numLine == self::MAX_LINES_PER_BATCH) {
//                        // last line might be the rest of file -> leave loop
//                        break;
//                    }
//                    $percentRead = floor($numLine * 100 / $lineCount);
//                    if ($prevPercentRead != $percentRead) {
////                        echo sprintf("\rParsed % 3d%%", $percentRead);
//                        $prevPercentRead = $percentRead;
//                    }
                $fields = preg_split("/\t/", $line);
                //                    echo implode("|", $fields) . "\n";
                if (count($fields) < 17) {
                    continue;
                }
                $class = $fields[6];
                $code = $fields[7];
                if ($class == 'P' && ($code == 'PPLC' || $code == 'PPL' || $code == 'PPLA' || $code == 'PPLA2' || $code == 'PPLA3')) {
                    $cities[$cityCount] = [
                        'id' => $fields[0],
                        'name' => $fields[1],
                        'ascii_name' => preg_replace('/[^a-z]+/',' ',strtolower($fields[2])),
                        'latitude' => $fields[4],
                        'longitude' => $fields[5],
                        'feature_class' => $fields[6],
                        'feature_code' => $fields[7],
                        'country_id' => $countryIds[$fields[8]],
                        'admin1' => $fields[10],
                        'admin2' => $fields[11],
                        'admin3' => $fields[12],
                        'admin4' => $fields[13],
                        'population' => $fields[14],
                        'elevation' => $fields[15] ?: null,
                        'timezone' => $fields[17],
                    ];
                    $cityCount++;
                    $numCities++;
                    $totalCities++;
                } else if ($class == 'A' && ($code == 'RGN' || $code == 'ADM1' || $code == 'ADM2')) {
                    $states[$stateCount] = [
                        'id' => $fields[0],
                        'name' => $fields[1],
                        'ascii_name' => preg_replace('/[^a-z]/',' ',strtolower($fields[2])),
                        'latitude' => $fields[4],
                        'longitude' => $fields[5],
                        'feature_class' => $fields[6],
                        'feature_code' => $fields[7],
                        'country_id' => $countryIds[$fields[8]],
                        'admin1' => $fields[10],
                        'admin2' => $fields[11],
                        'admin3' => $fields[12],
                        'admin4' => $fields[13],
                        'population' => $fields[14],
                        'elevation' => $fields[15] ?: null,
                        'timezone' => $fields[17],
                    ];
                    $stateCount++;
                    $numStates++;
                    $totalStates++;
                }
                $numLine++;
                if ($cityCount >= $batchSize) {
                    // write to database
//                        echo " +{$numCities} cities";
                    DB::table('cities')->upsert($cities, ['id'], ['name', 'ascii_name']);
                    $cityCount = 0;
                    $cities = [];
                }
                if ($stateCount >= $batchSize) {
                    // write to database
//                        echo " +{$numStates} states";
                    DB::table('states')->upsert($states, ['id'], ['name', 'ascii_name']);
                    $stateCount = 0;
                    $states = [];
                }
//                }
//                $data = $lines[$lineCount - 1]; // shift data to only keep last "line"
//                $batchNum++;
                $progressData->setProgress(ceil($sizeRead / $sizeRatio));
            } while (!feof($fCountry));
            if ($cityCount) {
//                    echo " +{$numCities} cities";
                DB::table('cities')->upsert($cities, ['id'], ['name', 'ascii_name']);
            }
            if ($stateCount) {
//                    echo " +{$numStates} states";
                DB::table('states')->upsert($states, ['id'], ['name', 'ascii_name']);
            }
//            echo ". DONE.\n";
            fclose($fCountry);
            $progressData->finish();
//            echo "{$numCities} cities and {$numStates} states added/updated to country {$country}";
            $progressCountry->advance();
        }
        $progressCountry->finish();
    }
}
