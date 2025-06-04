<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Редактировать складчину</h1>
    <form method="POST" action="{{ route('skladchinas.update', $skladchina) }}">
        @csrf
        @method('PUT')
        <input type="text" name="name" value="{{ $skladchina->name }}" class="border p-2 block mb-2" />
        <textarea name="description" class="border p-2 block mb-2">{{ $skladchina->description }}</textarea>
        <input type="number" step="0.01" name="full_price" value="{{ $skladchina->full_price }}" class="border p-2 block mb-2" />
        <input type="number" step="0.01" name="member_price" value="{{ $skladchina->member_price }}" class="border p-2 block mb-2" />
        <select name="category_id" class="border p-2 block mb-2">
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected($skladchina->category_id == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
        <x-primary-button>Сохранить</x-primary-button>
    </form>
</x-app-layout>
