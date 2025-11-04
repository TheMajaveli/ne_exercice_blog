<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tous les articles') }}
            </h2>
            @auth
                <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Nouvel article') }}
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if ($posts->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="space-y-6">
                            @foreach ($posts as $post)
                                <div class="border-b border-gray-200 pb-6 last:border-b-0">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-semibold mb-2">
                                                <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:text-blue-800">
                                                    {{ $post->title }}
                                                </a>
                                            </h3>
                                            <p class="text-gray-600 mb-2">{{ Str::limit($post->content, 200) }}</p>
                                            <div class="flex items-center text-sm text-gray-500 space-x-4">
                                                <span>{{ __('Par') }} {{ $post->user->name }}</span>
                                                @if ($post->published_at)
                                                    <span>{{ $post->published_at->format('d/m/Y H:i') }}</span>
                                                @else
                                                    <span class="text-orange-500">{{ __('Brouillon') }}</span>
                                                @endif
                                                <span>{{ $post->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        @auth
                                            @can('update', $post)
                                                <div class="ml-4 flex space-x-2">
                                                    <a href="{{ route('posts.edit', $post) }}" class="text-blue-600 hover:text-blue-800">
                                                        {{ __('Modifier') }}
                                                    </a>
                                                </div>
                                            @endcan
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $posts->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        <p class="text-gray-500">{{ __('Aucun article pour le moment.') }}</p>
                        @auth
                            <a href="{{ route('posts.create') }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Créer le premier article') }}
                            </a>
                        @endauth
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>


