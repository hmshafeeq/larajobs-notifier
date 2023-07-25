<?php

namespace App\Livewire;

use App\Jobs\FetchLarajobsFeed;
use App\Models\Feed;
use App\Services\JobFeedService;
use Livewire\Component;
use Native\Laravel\Facades\Notification;

class JobsList extends Component
{
    public $jobs;

    public $pollingInterval;

    public function mount()
    {
        if (!Feed::exists()){
            FetchLarajobsFeed::dispatchSync();
        }

         $this->fetchJobs();

        $this->pollingInterval = config('nativephp.polling_interval');

    }

    public function render()
    {
         return view('livewire.jobs-list');
    }

    public function fetchJobs()
    {
        $newJobs = Feed::all()->toArray();

        $this->sendNewJobNotifications($newJobs);

        $this->jobs = $newJobs;
    }

    private function sendNewJobNotifications(array $newJobs)
    {
        if (! empty($this->jobs)) {

            $newItems = collect($newJobs)->whereNotIn('link', collect($this->jobs)->pluck('link')->toArray());

            if ($newItems->count() > 0) {

                Notification::title('New Jobs Alert!')
                    ->message('Discover '.$newItems->count().' new job openings on Larajobs.')
                    ->show();
            }
        }
    }
}
