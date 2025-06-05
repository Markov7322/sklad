<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Редактировать складчину</h1>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
            <form method="POST" action="{{ route('skladchinas.update', $skladchina) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Название --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $skladchina->name) }}" class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Описание --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание</label>
                    <textarea name="description" id="description" rows="4" class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('description', $skladchina->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Полная цена --}}
                <div>
                    <label for="full_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Полная цена (₽)</label>
                    <input type="number" name="full_price" id="full_price" value="{{ old('full_price', $skladchina->full_price) }}" class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('full_price')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Цена взноса --}}
                <div>
                    <label for="member_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Цена взноса (₽)</label>
                    <input type="number" name="member_price" id="member_price" value="{{ old('member_price', $skladchina->member_price) }}" class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('member_price')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Категория --}}
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Категория</label>
                    <select name="category_id" id="category_id" class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $skladchina->category_id) == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Статус --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Статус складчины</label>
                    <select name="status" id="status" class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(\App\Models\Skladchina::statuses() as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $skladchina->status) == $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ссылка на облако --}}
                <div>
                    <label for="attachment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ссылка на облако</label>
                    <input type="url" name="attachment" id="attachment" value="{{ old('attachment', $skladchina->attachment) }}" class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('attachment')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Обложка --}}
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Обложка (изображение)</label>
                    @if($skladchina->image_path)
                        <img src="{{ asset('storage/'.$skladchina->image_path) }}" alt="{{ $skladchina->name }}" class="mb-2 w-full h-40 object-cover rounded">
                    @endif
                    <input type="file" name="image" id="image" accept="image/*" class="block w-full text-gray-700 dark:text-gray-300 file:bg-gray-100 dark:file:bg-gray-700 file:border file:border-gray-300 dark:file:border-gray-600 file:rounded-md file:px-4 file:py-2 file:text-sm file:font-medium file:text-gray-900 dark:file:text-gray-100 file:cursor-pointer hover:file:bg-gray-200 dark:hover:file:bg-gray-600" />
                    @error('image')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Кнопка --}}
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="w-full inline-flex justify-center items-center bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-400 text-white dark:text-gray-100 font-semibold px-6 py-3 rounded-lg shadow-md transition duration-200">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
