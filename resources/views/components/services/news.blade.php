<div class="w-full h-full text-gray-900 dark:text-white overflow-y-auto p-4 pb-40 sm:p-8">
    <div class="flex flex-col gap-4 sm:gap-8">

        <div class="flex justify-end">
            <button id="news-language-toggle" data-current-lang="{{ request('news_lang', 'en') }}" class="px-4 sm:px-8 py-2 sm:py-4 text-base sm:text-xl rounded-xl font-medium transition-colors duration-200 bg-gray-600 hover:bg-gray-700 text-white">
                {{ request('news_lang', 'en') == 'en' ? 'العربية' : 'English' }}
            </button>
        </div>

        @if($news && count($news) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-8" id="news-container" data-language="{{ request('news_lang', 'en') }}">
            @foreach($news as $article)
            <a href="{{ $article['url'] }}" target="_blank" class="animate-fadeInScaleUp delay-100 border border-gray-300 dark:border-white/10 rounded-2xl p-3 sm:p-4 hover:bg-gray-100 dark:hover:bg-white/10 transition-all duration-200 flex flex-col group">
                @if($article['image'])
                <div class="w-full h-64 sm:h-96 mb-2 sm:mb-3 overflow-hidden rounded-xl bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                    <img src="{{ $article['image'] }}" alt="News" class="w-full h-96 object-cover transition-transform duration-300" onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'flex flex-col items-center justify-center gap-2 p-8\'><svg class=\'w-16 h-16 opacity-30\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path fill-rule=\'evenodd\' d=\'M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z\' clip-rule=\'evenodd\'/></svg><p class=\'text-sm opacity-60 text-center px-4\'>The image failed to load from the source.</p></div>';">
                </div>
                @endif
                <div class="flex flex-col flex-1 justify-between">
                    <div>
                        <h3 class="text-lg sm:text-2xl font-semibold line-clamp-2 mb-1 sm:mb-2">{{ $article['title'] }}</h3>
                        @if($article['description'])
                        <p class="text-base sm:text-lg opacity-80 line-clamp-2 mb-1 sm:mb-2">{{ $article['description'] }}</p>
                        @endif
                    </div>
                    <div class="mt-1 sm:mt-2">
                        <p class="text-base sm:text-xl opacity-75 font-medium">Source: {{ $article['source'] }}</p>
                        @if($article['published_at'])
                        <p class="text-xs sm:text-sm opacity-60">{{ \Carbon\Carbon::parse($article['published_at'])->diffForHumans() }}</p>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- View More Button -->
        <div class="text-center pb-4">
            <button id="view-more-btn" class="w-full sm:w-auto px-4 sm:px-8 py-2 sm:py-4 text-base sm:text-xl rounded-xl font-medium transition-colors duration-200 bg-gray-600 hover:bg-gray-700 text-white">
                View More
            </button>
            <div id="loading-spinner" class="hidden text-base sm:text-xl opacity-75">
                Loading more news...
            </div>
        </div>
        @else
        <div class="flex-1 flex items-center justify-center">
            <div class="text-center opacity-75 text-lg sm:text-2xl">
                Sorry, the free API has reached it's limit. Try again tommorow.
            </div>
        </div>
        @endif
    </div>
</div>