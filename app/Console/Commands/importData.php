<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use KubAT\PhpSimple\HtmlDomParser;
use App\Models\City;

class importData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to import data about cities';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.e-obce.sk/kraj/NR.html');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        $dom = HtmlDomParser::str_get_html($response);

        $point = $dom->find('b[plaintext^=OKRES:]', 0);
        while ($point->next_sibling()) {
            $url = $point->next_sibling()->href;

            // zavolat GET pre kazdy okres
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $res = curl_exec($ch);
            curl_close($ch);

            $html = HtmlDomParser::str_get_html($res);

            foreach ($html->find('a[href^="https://www.e-obce.sk/obec/"]') as $town) {
                // skipnut linky na fotky
                if (!str_contains($town->href, 'fotky')) {
                    $town_url = $town->href;

                    // zavolat get pre kazdu obec
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $town_url);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $res = curl_exec($ch);
                    curl_close($ch);

                    $town_dom = HtmlDomParser::str_get_html($res);

                    $image = $town_dom->find('img[alt^=Erb]', 0);
                    $imageSrc = $image->src;
                    $imageLocalPath = 'public/resources/assets/towns/'.preg_replace('/[^a-z0-9-.]/i', '-', $imageSrc);
                    $dbPath = 'resources/assets/towns/'.preg_replace('/[^a-z0-9-.]/i', '-', $imageSrc);
                    $imageData = file_get_contents($imageSrc);
                    file_put_contents($imageLocalPath, $imageData);

                    $name = substr(strstr($town_dom->find('h1')[0]->plaintext," "), 1);

                    if ($town_dom->find('td[plaintext^=Starosta:]', 0) !== null) {
                        $mayor_text = $town_dom->find('td[plaintext^=Starosta:]', 0);
                    } else {
                        $mayor_text = $town_dom->find('td[plaintext^=Primátor:]', 0);
                    }
                    $mayor =  $mayor_text->next_sibling()->plaintext;
                    if ($town_dom->find('td[plaintext^=03]', 0) !== null) {
                        $number = $town_dom->find('td[plaintext^=03]', 0)->plaintext;
                    } else {
                        $number = null;
                    }
                    if ($town_dom->find('td[plaintext^=03]', 1) !== null) {
                        $fax = $town_dom->find('td[plaintext^=03]', 1)->plaintext;
                    } else {
                        $fax = null;
                    }
                    $address = $town_dom->find('td[valign^=top]', 15)->plaintext . ', ' . $town_dom->find('td[valign^=top]', 16)->plaintext;
                    $web = $town_dom->find('a[href^=https://www.]', 50)->plaintext;
                    $email = $town_dom->find('a[href^=mailto:]', 0)->plaintext;

                    $town = City::create([
                        'name' => $name,
                        'mayor_name' => $mayor,
                        'img_path' => $dbPath,
                        'number' => $number,
                        'fax' => $fax,
                        'address' => $address,
                        'web' => $web,
                        'email' => $email
                    ]);
                }
            }

            // dalsi okres
            $point = $point->next_sibling();
        }
    }
}
