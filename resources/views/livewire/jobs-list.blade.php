<!-- jobs.blade.php -->

<div class="grid grid-cols-1" wire:poll.{{ $pollingInterval }}s.keep-alive="fetchJobs()">

    @foreach ($jobs as $job)

        <div class="flex flex-row border bg-white rounded overflow-hidden">

            <div class="w-24 h-24 rounded bg-transparent object-cover p-6">
                @if ($job['company_logo'])
                    <img src="{{ $job['company_logo'] }}" alt="{{ $job['company'] }}" class="w-full h-full object-cover">
                @endif

            </div>

            <div class="p-5 pl-0 flex-grow">

                <p class="text-gray-600 text-left">
                    <span class="text-indigo-500 font-medium">{{ $job['company'] }}</span>
                </p>

                <h2 class="md:text-xl font-bold mb-3 text-left">{{ $job['title'] }}</h2>

                {{-- <p class="text-gray-600 mb-4 text-left">
                  <span class="text-indigo-500 font-medium">{{ $job['company'] }}</span> - {{ $job['location'] }}
                </p> --}}

                @empty(!$job['tags'])
                    <div class="text-xs font-medium mb-2 flex flex-wrap gap-2">
                        @foreach (explode(',',$job['tags']) as $tag)
                            <span class="bg-indigo-100 text-indigo-500 rounded-full px-3 py-1">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endempty


                @empty(!$job['pubDate'])
                    <p class="text-gray-400 font-medium text-left">
                        Posted {{ \Carbon\Carbon::parse($job['pubDate'])->diffForHumans() }}
                    </p>
                @endempty

            </div>

            <div class="text-gray-600 text-sm p-8 text-right w-1/2">

                @empty(!$job['salary']) <br>
                <h2 class="font-bold">{{ $job['salary'] }}</h2>
                <br>
                @endempty

                <p class="mt-4">
                    @empty(!$job['job_type'])
                        <span class="bg-indigo-100 text-indigo-500 rounded-full px-3 py-1">
                    <span class="font-medium">
                        {{ Str::of($job['job_type'])->replace('_',' ')->title() }}
                        @empty(!$job['location'])
                            <span class="">
                            / {{ $job['location'] }}
                        </span>
                        @endempty
                    </span>
                </span>
                    @endempty
                </p>

            </div>

        </div>

    @endforeach

</div>

<script>
    setTimeout(() => {

    }, 2000);
</script>