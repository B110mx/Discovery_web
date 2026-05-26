@extends('layouts.app')

@section('content')

<section class="max-w-7xl mx-auto space-y-10">
    <div class="bg-blue-700 text-white rounded-xl shadow-lg p-8 md:p-12">
        <p class="font-semibold uppercase tracking-wide text-sm">Tienda escolar</p>
        <h1 class="text-4xl md:text-5xl font-bold mt-3">Productos de listas de utiles</h1>
        <p class="text-blue-50 text-lg mt-5 max-w-3xl">
            Selecciona los materiales que necesitas y completa los datos del alumno y padre o tutor para registrar el pedido.
        </p>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded">
            Revisa los datos del pedido antes de enviarlo.
        </div>
    @endif

    <form action="{{ route('tienda.store') }}" method="POST" class="grid lg:grid-cols-3 gap-8">
        @csrf

        <section class="lg:col-span-2 bg-white rounded-xl shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-blue-700 mb-6">Selecciona productos</h2>

            @error('productos')
                <p class="text-red-600 font-semibold mb-4">{{ $message }}</p>
            @enderror

            <div class="grid md:grid-cols-2 gap-4">
                @foreach ($productos as $slug => $producto)
                    <label class="block border border-gray-200 rounded-lg p-4 hover:border-blue-400 hover:bg-blue-50">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" name="productos[{{ $slug }}][seleccionado]" value="1" class="mt-1">
                            <div class="flex-1">
                                <p class="font-bold text-black">{{ $producto['nombre'] }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $producto['nivel'] }}</p>
                                <p class="text-blue-700 font-bold mt-3">${{ number_format($producto['precio'], 2) }}</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <span class="block text-sm font-semibold text-gray-700 mb-1">Cantidad</span>
                            <input type="number" name="productos[{{ $slug }}][cantidad]" min="1" value="1" class="w-24 border border-gray-300 rounded p-2">
                        </div>
                    </label>
                @endforeach
            </div>
        </section>

        <aside class="bg-white rounded-xl shadow-md p-6 md:p-8 h-fit">
            <h2 class="text-2xl font-bold text-blue-700 mb-6">Datos del pedido</h2>

            <div class="space-y-4">
                <div>
                    <label for="alumno_nombre" class="block text-sm font-semibold text-gray-700 mb-1">Nombre del alumno</label>
                    <input id="alumno_nombre" name="alumno_nombre" value="{{ old('alumno_nombre') }}" class="w-full border border-gray-300 p-3 rounded" required>
                    @error('alumno_nombre') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="alumno_nivel" class="block text-sm font-semibold text-gray-700 mb-1">Nivel</label>
                    <select id="alumno_nivel" name="alumno_nivel" class="w-full border border-gray-300 p-3 rounded" required>
                        <option value="">Selecciona un nivel</option>
                        @foreach ($niveles as $valor => $etiqueta)
                            <option value="{{ $valor }}" @selected(old('alumno_nivel') === $valor)>{{ $etiqueta }}</option>
                        @endforeach
                    </select>
                    @error('alumno_nivel') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="alumno_grado" class="block text-sm font-semibold text-gray-700 mb-1">Grado o grupo</label>
                    <input id="alumno_grado" name="alumno_grado" value="{{ old('alumno_grado') }}" class="w-full border border-gray-300 p-3 rounded">
                </div>

                <div>
                    <label for="padre_nombre" class="block text-sm font-semibold text-gray-700 mb-1">Nombre del padre o tutor</label>
                    <input id="padre_nombre" name="padre_nombre" value="{{ old('padre_nombre') }}" class="w-full border border-gray-300 p-3 rounded" required>
                    @error('padre_nombre') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="padre_telefono" class="block text-sm font-semibold text-gray-700 mb-1">Telefono</label>
                    <input id="padre_telefono" name="padre_telefono" value="{{ old('padre_telefono') }}" class="w-full border border-gray-300 p-3 rounded" required>
                    @error('padre_telefono') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="padre_email" class="block text-sm font-semibold text-gray-700 mb-1">Correo electronico</label>
                    <input id="padre_email" type="email" name="padre_email" value="{{ old('padre_email') }}" class="w-full border border-gray-300 p-3 rounded">
                    @error('padre_email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="notas" class="block text-sm font-semibold text-gray-700 mb-1">Notas</label>
                    <textarea id="notas" name="notas" class="w-full border border-gray-300 p-3 rounded min-h-28">{{ old('notas') }}</textarea>
                </div>

                <button class="w-full bg-blue-700 text-white px-6 py-3 rounded font-semibold hover:bg-blue-800">
                    Realizar pedido
                </button>
            </div>
        </aside>
    </form>
</section>

@endsection
