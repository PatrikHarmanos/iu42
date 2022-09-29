<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use KubAT\PhpSimple\HtmlDomParser;
use App\Models\Town;

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

//        echo $dom->find('b[plaintext^=OKRES:]', 0)->next_sibling()->href;

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

                    $name = substr(strstr($town_dom->find('h1')[0]->plaintext," "), 1);
                    // $mayor_text = $town_dom->find('td[plaintext^=Starosta:]', 0);
                    // $mayor =  $mayor_text->next_sibling()->plaintext;
                    // $number = $town_dom->find('td[plaintext^=03]', 0)->plaintext;
                    // $fax = $town_dom->find('td[plaintext^=03]', 1)->plaintext;
                    // echo $name, PHP_EOL;

                    $product = Town::create([
                        'name' => $name,
                        'mayor_name' => 'kokot'
                    ]);
                }
            }

            // dalsi okres
            $point = $point->next_sibling();
        }
    }
}
