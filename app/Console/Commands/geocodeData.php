<?php

namespace App\Console\Commands;

use App\Models\City;
use Illuminate\Console\Command;

class geocodeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:geocode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get lat and lon from address';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $towns = City::all();
        foreach ($towns as $town) {
            $str = $town->address;
            $str = explode(',', $str)[1];
            $str = substr($str, 1);
            $arr = explode(' ', $str);
            $text = '';
            $i = 0;
            foreach ($arr as $item) {
                if ($i >= 2) {
                    if ($i == 2) {
                        $text .= $item;
                    } else {
                        $text .= '%' . $item;
                    }
                }
                $i += 1;
            }
            $str = $text;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $str . '&key=AIzaSyD3IdOaoOc8tVpnakDzh1BLImcS-iJxoVY');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);

            $json = json_decode($response);
            if ($json->results) {
                $latitude = $json->results[0]->geometry->location->lat;
                $longitude = $json->results[0]->geometry->location->lng;

                $town->latitude = $latitude;
                $town->longitude = $longitude;
                $town->save();
            }
        }
    }
}
