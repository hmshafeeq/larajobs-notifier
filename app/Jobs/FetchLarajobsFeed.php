<?php

namespace App\Jobs;

use App\Models\Feed;
use App\Services\JobFeedService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels; 

class FetchLarajobsFeed
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = (new JobFeedService())->fetchData();

        foreach ($data as $datum) {
            Feed::updateOrCreate(
                ['link' => $datum['link']],
                $datum
            );

        }
    }
}
