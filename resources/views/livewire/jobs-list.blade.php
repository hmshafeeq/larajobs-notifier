<!-- jobs.blade.php -->

<div class="grid grid-cols-1 text-sm" {{ 'wire:poll.'.$pollingInterval.'s.keep-alive' }}="fetchJobs()">
@if(!empty($jobs))
    @foreach($jobs as $job)
        <div class="flex flex-row border bg-gray-50 rounded overflow-hidden hover:bg-gray-100">
            <div class="w-24 h-24 rounded bg-transparent object-cover p-6">
                @if ($job['company_logo'])
                    <img src="{{ $job['company_logo'] }}" alt="{{ $job['company'] }}" class="object-cover">
                @endif
            </div>
            <div class="flex flex-row w-full">
                <div class="p-5 pl-0 w-2/3">
                    <p class="text-left">
                        <span class="text-sky-500 text-xs">{{ $job['company'] }}</span>
                    </p>
                    <h2 class="font-medium text-left text-gray-700">{{ $job['title'] }}</h2>


                    @if($job['salary'])
                        <small class="text-zinc-600">{{ $job['salary'] }}</small>
                    @endif


                    @if($job['job_type'])
                        <span class="text-zinc-600 text-xs">
                            @if($job['salary'])
                            <br>
                            @endif
                            {{ Str::of($job['job_type'])->replace('_',' ')->title() }}
                            @if($job['location'])
                                / {{ $job['location'] }}
                            @endif
                            </span>
                    @endif



                    @if($job['tags'])
                        <div class="text-xs mb-2 flex flex-wrap">
                            @foreach (explode(',', $job['tags']) as $tag)
                                <span class="bg-gray-100 text-gray-500 rounded-full px-2 py-1 mr-1 mt-1">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                    @if($job['pub_date'])
                        <p class="text-gray-400 text-xs">
                            -- Posted {{ \Carbon\Carbon::parse($job['pub_date'])->timezone('AMERICA/LOS_ANGELES')->diffForHumans() }}
                        </p>
                    @endif
                </div>
                <div class="flex flex-col text-gray-600 text-sm text-right w-1/3">
                    <div class="py-8 px-6">
                        <a href="{{ $job['link'] }}" target="_blank" style="width: 60px;"
                           class="bg-sky-500 text-white px-2.5 py-1.5 rounded hover:cursor-pointer hover:bg-sky-600">
                            Apply
                        </a>

                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="flex flex-row border bg-gray-50 p-12 text-center">
        <div class=" mx-auto">
            Please hold on while we retrieve the latest jobs from larajobs.com...
        </div>
    </div>
    @endif
    </div>