<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Num;

class NumController extends Controller
{
    
    public function index()
    {
        $nums = Num::all(); // استرجاع جميع السجلات من الجدول
        return response()->json($nums);
    }

    
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $validated = $request->validate([
            'doctor' => 'required|string|max:255',
            'patient' => 'required|string|max:255',
            'clinic' => 'required|string|max:255',
            'lab' => 'required|string|max:255',
        ]);

        // إنشاء السجل الجديد
        $num = Num::create($validated);

        // إرجاع السجل الجديد
        return response()->json($num, 201);
    }

    /**
     * عرض تفاصيل سجل معين.
     */
    public function show($id)
    {
        $num = Num::findOrFail($id); // البحث عن السجل باستخدام المعرف
        return response()->json($num);
    }

    /**
     * تحديث سجل معين.
     */
    public function update(Request $request, $id)
    {
        $num = Num::findOrFail($id); // العثور على السجل

        // التحقق من صحة البيانات المدخلة
        $validated = $request->validate([
            'doctor' => 'nullable|string|max:255',
            'patient' => 'nullable|string|max:255',
            'clinic' => 'nullable|string|max:255',
            'lab' => 'nullable|string|max:255',
        ]);

        // تحديث السجل
        $num->update($validated);

        return response()->json($num);
    }

    /**
     * حذف سجل معين.
     */
    public function destroy($id)
    {
        $num = Num::findOrFail($id); // العثور على السجل
        $num->delete(); // حذف السجل

        return response()->json(null, 204); // إرجاع استجابة "بدون محتوى"
    }
}
