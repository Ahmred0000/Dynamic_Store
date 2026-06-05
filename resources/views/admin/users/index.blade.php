@extends('layouts.admin')

@section('title', 'المستخدمين')
@section('header', 'إدارة المستخدمين')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h3 class="text-gray-600">إجمالي المستخدمين: {{ $users->total() }}</h3>
    <button onclick="document.getElementById('modal-add').classList.remove('hidden')"
        class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
        + إضافة مستخدم
    </button>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr class="text-right text-gray-500 border-b">
                <th class="px-4 py-3">الاسم</th>
                <th class="px-4 py-3">البريد الإلكتروني</th>
                <th class="px-4 py-3">الدور</th>
                <th class="px-4 py-3">تاريخ الإضافة</th>
                <th class="px-4 py-3">إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $user->email }}</td>
                <td class="px-4 py-3">
                    @foreach($user->roles as $role)
                        @if($role->name == 'admin')
                            <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded-full text-xs">مدير</span>
                        @elseif($role->name == 'worker')
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs">عامل</span>
                        @elseif($role->name == 'customer')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">عميل</span>
                        @endif
                    @endforeach
                </td>
                <td class="px-4 py-3 text-gray-500">{{ $user->created_at->format('Y/m/d') }}</td>
                <td class="px-4 py-3">
                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                        onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline text-xs">حذف</button>
                    </form>
                    @else
                        <span class="text-gray-400 text-xs">أنت</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-8 text-gray-400">لا يوجد مستخدمون بعد</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $users->links() }}</div>
</div>

{{-- Modal إضافة مستخدم --}}
<div id="modal-add" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-6">إضافة مستخدم جديد</h3>
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">الاسم</label>
                    <input type="text" name="name" required
                        class="w-full border rounded-lg px-3 py-2 text-right">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">البريد الإلكتروني</label>
                    <input type="email" name="email" required
                        class="w-full border rounded-lg px-3 py-2 text-right">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">كلمة المرور</label>
                    <input type="password" name="password" required minlength="8"
                        class="w-full border rounded-lg px-3 py-2 text-right">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">الدور</label>
                    <select name="role" required
                        class="w-full border rounded-lg px-3 py-2 text-right">
                        <option value="worker">عامل</option>
                       <option value="admin">مدير</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3 mt-6 justify-end">
                <button type="button"
                    onclick="document.getElementById('modal-add').classList.add('hidden')"
                    class="px-5 py-2 border rounded-lg text-gray-600 hover:bg-gray-50">إلغاء</button>
                <button type="submit"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">حفظ</button>
            </div>
        </form>
    </div>
</div>

@endsection

