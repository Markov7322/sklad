<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Новая складчина</h1>
    <form method="POST" action="{{ route('skladchinas.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="text" name="name" placeholder="Название" class="border p-2 block mb-2" />
        <textarea name="description" class="border p-2 block mb-2" placeholder="Описание"></textarea>
        <input type="number" step="0.01" name="full_price" placeholder="Полная цена" class="border p-2 block mb-2" />
        <input type="number" step="0.01" name="member_price" placeholder="Цена взноса" class="border p-2 block mb-2" />
        <select name="category_id" class="border p-2 block mb-2">
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status" class="border p-2 block mb-2">
            @foreach(\App\Models\Skladchina::statuses() as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        <input type="file" name="image" class="border p-2 block mb-2" />
        <x-primary-button>Создать</x-primary-button>
    </form>
</x-app-layout>
