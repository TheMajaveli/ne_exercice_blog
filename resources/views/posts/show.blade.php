<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $post->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('posts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Retour') }}
                </a>
                @auth
                    @can('update', $post)
                        <a href="{{ route('posts.edit', $post) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Modifier') }}
                        </a>
                    @endcan
                    @can('delete', $post)
                        <form method="POST" action="{{ route('posts.destroy', $post) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                {{ __('Supprimer') }}
                            </button>
                        </form>
                    @endcan
                @endauth
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 pb-4 border-b border-gray-200">
                        <div class="flex items-center text-sm text-gray-500 space-x-4 mb-2">
                            <span>{{ __('Par') }} <strong>{{ $post->user->name }}</strong></span>
                            @if ($post->published_at)
                                <span>{{ __('Publié le') }} {{ $post->published_at->format('d/m/Y à H:i') }}</span>
                            @else
                                <span class="text-orange-500">{{ __('Brouillon') }}</span>
                            @endif
                            <span>{{ __('Créé') }} {{ $post->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="prose max-w-none">
                        <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>
                        <div class="whitespace-pre-wrap">{{ $post->content }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


