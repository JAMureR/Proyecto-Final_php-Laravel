<x-app-layout>
    <x-slot name="header">
        @include('includes.message')
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-10">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Aquí ajustamos la estructura para que la imagen sea más grande -->
                    @if(!empty($image->image_path) && Storage::disk('images')->exists($image->image_path))
                    <div class="relative bg-gray-900 rounded-lg shadow-md overflow-hidden w-full md:w-3/4 mx-auto">
                        <!-- Imagen de fondo más grande y centrada -->
                        <img class="w-full h-auto object-cover" src="{{ route('image.file',['filename' => $image->image_path]) }}" alt="Imagen subida" />
                    </div>

                    <!-- Capa de superposición para el nick y el avatar (debajo de la imagen) -->
                    <div class="bg-gray-900 bg-opacity-80 p-4 rounded-b-lg w-full md:w-3/4 mx-auto mt-4">
                        <div class="flex items-center">
                            @if($image->user->image)
                            <img id="user-avatar" src="{{ route('user.avatar', ['filename' => $image->user->image]) }}" class="w-8 h-8 rounded-full" />
                            @endif
                            <p class="font-semibold ml-2 text-white">
                                {{ '@' . $image->user->nick }}
                            </p>
                        </div>

                        <!-- Descripción en su propia capa -->
                        <div class="description mt-2 text-white">
                            {{ $image->description }}
                            <p id="date-created">{{ $image->created_at }}</p>
                        </div>

                        <!-- Formulario de comentario: Alineado arriba, justo antes de los comentarios -->
                        <form method="POST" action="{{ route('comment.save') }}" class="w-full mt-4">
                            @csrf
                            <input value="{{ $image->id }}" type="hidden" name="image_id" />

                            <div class="flex items-center space-x-2">
                                <!-- Textarea más ancho y alineado a la izquierda -->
                                <textarea name="content" placeholder="Añade un comentario..." required class="w-3/4 p-2 border rounded-md"></textarea>

                                <!-- Botón de "Publicar" alineado a la izquierda -->
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Publicar</button>
                            </div>

                            <!-- Mensaje de error -->
                            @error('content')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </form>

                        <!-- Contenedor flex para organizar los comentarios -->
                        <div class="flex flex-col w-full mt-4 space-y-4">
                            <!-- Sección de comentarios -->
                            <div class="w-full">
                                <h2 class="text-white">Comentarios ({{ count($image->comments) }})</h2>
                                <br>

                                @foreach ($image->comments as $comment)
                                <div id="comment" class="w-15 h-15 text-white text-xs">
                                    <p>{{ '@' . $comment->user->nick . ' | ' . $comment->created_at }}</p>
                                    <p>{{ $comment->content }}</p>

                                    @if(Auth::check() && ($comment->user_id == Auth::user()->id || $comment->image->user_id == Auth::user()->id))
                                        <a href="{{ route('comment.delete',['id'=> $comment->id]) }}" class=" underline">
                                            Eliminar comentario
                                        </a>
                                    @endif
                                </div>
                                <br>
                                @endforeach
                            </div>
                        </div>

                        <!-- Icono de corazón alineado abajo -->
                        <div id="likes-detail">
                            <img src="{{ asset('img/heart-red.png') }}" alt="Corazón" class="w-6 h-6 ml-4" />
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
