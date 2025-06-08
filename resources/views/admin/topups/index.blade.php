@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6 border-b pb-2">Пополнения баланса</h1>
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b">
                <th class="py-2 text-left">ID</th>
                <th class="py-2 text-left">Пользователь</th>
                <th class="py-2 text-left">Сумма</th>
                <th class="py-2 text-left">Статус</th>
                <th class="py-2 text-left">Дата</th>
                <th class="py-2 text-left"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($topups as $topup)
                <tr class="border-b">
                    <td class="py-1">{{ $topup->id }}</td>
                    <td class="py-1">{{ $topup->user->name }}</td>
                    <td class="py-1">{{ number_format($topup->amount, 2) }} ₽</td>
                    <td class="py-1">{{ $topup->status }}</td>
                    <td class="py-1">{{ $topup->created_at->format('d.m.Y H:i') }}</td>
                    <td class="py-1">
                        <form method="POST" action="{{ route('admin.topups.update', $topup) }}" class="flex items-center space-x-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="border rounded p-1">
                                <option value="pending" @selected($topup->status === 'pending')>Не оплачено</option>
                                <option value="paid" @selected($topup->status === 'paid')>Оплачено</option>
                                <option value="cancelled" @selected($topup->status === 'cancelled')>Отменено</option>
                                <option value="refunded" @selected($topup->status === 'refunded')>Возвращено</option>
                            </select>
                            <x-primary-button>Сохранить</x-primary-button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $topups->links() }}</div>
@endsection
