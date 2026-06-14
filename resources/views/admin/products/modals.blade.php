{{-- مودال الإضافة المحدث --}}
<div id="modal-add" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl">
        <h2 class="text-lg font-bold mb-4 border-b pb-2 text-slate-800">إضافة منتج جديد</h2>
        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="name" placeholder="اسم المنتج" required class="col-span-2 w-full border border-slate-300 rounded-lg p-2 text-slate-800 bg-white placeholder-slate-400">
                <input type="text" name="sku" placeholder="الكود (SKU)" required class="w-full border border-slate-300 rounded-lg p-2 text-slate-800 bg-white placeholder-slate-400">
                <select name="category_id" class="w-full border border-slate-300 rounded-lg p-2 text-slate-800 bg-white">
                    @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                </select>
                <input type="number" step="0.01" name="price" placeholder="السعر" required class="w-full border border-slate-300 rounded-lg p-2 text-slate-800 bg-white placeholder-slate-400">
                <input type="number" name="quantity" placeholder="الكمية" required class="w-full border border-slate-300 rounded-lg p-2 text-slate-800 bg-white placeholder-slate-400">
                <input type="number" name="min_quantity" placeholder="الحد الأدنى" required class="w-full border border-slate-300 rounded-lg p-2 text-slate-800 bg-white placeholder-slate-400">
                <select name="unit" class="w-full border border-slate-300 rounded-lg p-2 text-slate-800 bg-white">
                    <option value="قطعة">قطعة</option><option value="عود">عود</option><option value="كرتونة">كرتونة</option><option value="لوح">لوح</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-slate-500 font-bold">إلغاء</button>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold">حفظ</button>
            </div>
        </form>
    </div>
</div>

{{-- مودال التعديل المحدث --}}
<div id="modal-edit" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl">
        <h2 class="text-lg font-bold mb-4 border-b pb-2 text-slate-800">تعديل المنتج</h2>
        <form id="edit-form" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="name" id="edit-name" class="col-span-2 w-full border border-slate-300 rounded-lg p-2 text-slate-800 bg-white">
                <input type="text" name="sku" id="edit-sku" readonly class="w-full border border-slate-300 rounded-lg p-2 text-slate-500 bg-slate-100">
                <select name="category_id" id="edit-category" class="w-full border border-slate-300 rounded-lg p-2 text-slate-800 bg-white">
                    @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                </select>
                <input type="number" step="0.01" name="price" id="edit-price" class="w-full border border-slate-300 rounded-lg p-2 text-slate-800 bg-white">
                <input type="number" name="quantity" id="edit-quantity" class="w-full border border-slate-300 rounded-lg p-2 text-slate-800 bg-white">
                <input type="number" name="min_quantity" id="edit-min" class="w-full border border-slate-300 rounded-lg p-2 text-slate-800 bg-white">
                <select name="unit" id="edit-unit" class="w-full border border-slate-300 rounded-lg p-2 text-slate-800 bg-white">
                    <option value="قطعة">قطعة</option><option value="عود">عود</option><option value="كرتونة">كرتونة</option><option value="لوح">لوح</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-slate-500 font-bold">إلغاء</button>
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-xl font-bold">تحديث</button>
            </div>
        </form>
    </div>
</div>
