<h1 class="text-2xl font-bold mb-4">Mensajes</h1>

<table class="w-full border">
    <tr class="bg-gray-200">
        <th>Nombre</th>
        <th>Email</th>
        <th>Mensaje</th>
    </tr>

    @foreach($contactos as $c)
    <tr class="border">
        <td>{{ $c->nombre }}</td>
        <td>{{ $c->email }}</td>
        <td>{{ $c->mensaje }}</td>
    </tr>
    @endforeach
</table>