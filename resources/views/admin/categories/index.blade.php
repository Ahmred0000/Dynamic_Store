@extends('layouts.admin')

@section('title', 'الفئات')
@section('header', 'إدارة الفئات')

@section('content')

{{-- زرار إضافة فئة --}}
<div class="flex justify-between items-center mb-6">
    <h3 class="text-gray-600">إجمالي الفئات: {{ $categories->count() }}</h3>
    <button onclick="document.getElementById('modal-category-add').classList.remove('hidden')"
        class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 shadow">
        + إضافة فئة جديدة
    </button>
</div>

{{-- جدول الفئات --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr class="text-right text-gray-500 border-b">
                <th class="px-4 py-3">رقم الفئة</th>
                <th class="px-4 py-3">اسم الفئة</th>
                <th class="px-4 py-3">نوع الصلاحية (الوجهة)</th>
                <th class="px-4 py-3">إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-3 font-semibold text-gray-400">#{{ $category->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $category->name }}</td>
                <td class="px-4 py-3">
                    @if($category->is_for_sale)
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">🛒 متاح للبيع والعملاء</span>
                    @else
                        <span class="bg-amber-100 text-amber-700 px-2 py-1 rounded-full text-xs font-semibold">🏭 خاص بالمصنع فقط</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-3">
                        <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}', {{ $category->is_for_sale }})"
                            class="text-blue-600 hover:text-blue-800 text-xs font-semibold">تعديل</button>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}"
                            onsubmit="return confirm('هل أنت متأكد من حذف هذه الفئة؟ سيتم فك ارتباط منتجاتها.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-semibold">حذف</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-8 text-gray-400">لا توجد فئات مضافة بعد</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal إضافة فئة --}}
<div id="modal-category-add" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity">
    <div class="bg-white rounded-xl p-8 w-full max-w-lg shadow-2xl">
        <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2">📂 إضافة فئة جديدة</h3>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1 font-semibold">اسم الفئة</label>
                    <input type="text" name="name" required placeholder="مثال: مواد خام بلاستيكية"
                        class="w-full border rounded-lg px-3 py-2 text-right focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1 font-semibold">وجهة وصلاحية الفئة</label>
                    <select name="is_for_sale" required class="w-full border rounded-lg px-3 py-2 text-right focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="1">🛒 متاح للبيع للعملاء (تظهر بالمتجر)</option>
                        <option value="0">🏭 استخدام داخلي بالمصنع (للعمال فقط)</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3 mt-6 justify-end">
                <button type="button" onclick="document.getElementById('modal-category-add').classList.add('hidden')"
                    class="px-5 py-2 border rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">إلغاء</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">حفظ الفئة</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal تعديل فئة --}}
<div id="modal-category-edit" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity">
    <div class="bg-white rounded-xl p-8 w-full max-w-lg shadow-2xl">
        <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2">✏️ تعديل بيانات الفئة</h3>
        <form method="POST" id="edit-category-form" action="">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1 font-semibold">اسم الفئة</label>
                    <input type="text" name="name" id="edit-category-name" required
                        class="w-full border rounded-lg px-3 py-2 text-right focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1 font-semibold">وجهة وصلاحية الفئة</label>
                    <select name="is_for_sale" id="edit-category-is-for-sale" required class="w-full border rounded-lg px-3 py-2 text-right focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="1">🛒 متاح للبيع للعملاء (تظهر بالمتجر)</option>
                        <option value="0">🏭 استخدام داخلي بالمصنع (للعمال فقط)</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3 mt-6 justify-end">
                <button type="button" onclick="document.getElementById('modal-category-edit').classList.add('hidden')"
                    class="px-5 py-2 border rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">إلغاء</button>
                <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow">حفظ التعديلات</button>
            </div>
        </form>
    </div>
</div>

<script>
function editCategory(id, name, isForSale) {
    document.getElementById('edit-category-name').value = name;
    document.getElementById('edit-category-is-for-sale').value = isForSale;
    document.getElementById('edit-category-form').action = '/admin/categories/' + id;
    document.getElementById('modal-category-edit').classList.remove('hidden');
}
</script>

@endsection
