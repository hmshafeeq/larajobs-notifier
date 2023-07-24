<?php

namespace App\Livewire;

use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Native\Laravel\Facades\Notification;

class JobsList extends Component
{
    public $jobs;

    public $pollingInterval;

    public function mount()
    {
        $this->fetchJobs();

        $this->pollingInterval = config('nativephp.polling_interval', 5);
    }

    public function render()
    {
        return view('livewire.jobs-list');
    }

    public function fetchJobs()
    {

        $newJobs = config('nativephp.debug_mode', false)
            ? $this->getFakeData()
            : $this->getFeedsData();

        $this->sendNewJobNotifications($newJobs);

        $this->jobs = $newJobs;
    }

    private function getFeedsData()
    {
        $feedData = Http::get('http://larajobs.com/feed')->body();

        // Assuming you have already fetched the XML data into the variable $feedData

        // Create a new instance of DOMDocument
        $dom = new DOMDocument();

        // Load the XML data into the DOMDocument
        $dom->loadXML($feedData);

        // Create a new instance of DOMXPath
        $xpath = new DOMXPath($dom);

        // Register the namespace prefixes and URIs used in the XML
        $namespaces = [
            'content' => 'http://purl.org/rss/1.0/modules/content/',
            'job' => 'https://larajobs.com',
        ];

        foreach ($namespaces as $prefix => $uri) {
            $xpath->registerNamespace($prefix, $uri);
        }

        // Now, you can use XPath to query nodes with the registered namespaces.
        // For example, to extract the 'job_type', 'location', and 'tags':
        $items = $xpath->query('//item');

        $result = [];
        foreach ($items as $item) {

            try {

                $title = $xpath->query('title', $item)->item(0)->nodeValue;
                $link = $xpath->query('link', $item)->item(0)->nodeValue;
                $pubDate = $xpath->query('pubDate', $item)->item(0)->nodeValue;
                $creator = $xpath->query('dc:creator', $item)->item(0)->nodeValue;
                $location = $xpath->query('job:location', $item)->item(0)->nodeValue;
                $jobType = $xpath->query('job:job_type', $item)->item(0)->nodeValue;
                $salary = $xpath->query('job:salary', $item)->item(0)->nodeValue;
                $company = $xpath->query('job:company', $item)->item(0)->nodeValue;
                $companyLogo = $xpath->query('job:company_logo', $item)->item(0)->nodeValue;
                $tags = $xpath->query('job:tags', $item)->item(0)->nodeValue;

                // temporary fix for getting default logo
                if ($companyLogo == "https://larajobs.com/logos/") {
                    $companyLogo = "https://larajobs.com/img/nologo.png";
                }


                $result[] = [
                    'title' => $title,
                    'link' => $link,
                    'pubDate' => $pubDate,
                    'creator' => $creator,
                    'location' => $location,
                    'job_type' => $jobType,
                    'salary' => $salary,
                    'company' => $company,
                    'company_logo' => $companyLogo,
                    'tags' => $tags,
                ];

            } catch (\Exception $e) {
            }
        }


        return $result;
    }

    private function getFakeData()
    {
        $results = [];

        for ($i = 0; $i < 10; $i++) {

            $results[] = [
                'title' => fake()->jobTitle,
                'link' => fake()->url,
                'pubDate' => fake()->dateTimeThisMonth->format('Y-m-d H:i:s'),
                'creator' => fake()->name,
                'location' => fake()->address,
                'job_type' => fake()->randomElement(['FULL_TIME', 'PART_TIME', 'CONTRACT']),
                'salary' => fake()->randomNumber(5, true) . '-' . fake()->randomNumber(5, true),
                'company' => fake()->company,
                'company_logo' => fake()->imageUrl(200, 200, 'business', true),
                'tags' => implode(',', fake()->randomElements(['Laravel', 'PHP', 'JavaScript', 'Vue.js', 'React', 'Node.js'], fake()->numberBetween(1, 3))),
            ];

        }

        return $results;
    }

    private function sendNewJobNotifications(array $newJobs)
    {
        if (! empty($this->jobs)) {

            $newItems = collect($newJobs)->whereNotIn('link', collect($this->jobs)->pluck('link')->toArray());

            if ($newItems->count() > 0) {

                Notification::title('Hello from Larajobs')
                    ->message('This is a detail message coming from your Laravel app.')
                    ->show();
            }
        }
    }

}
