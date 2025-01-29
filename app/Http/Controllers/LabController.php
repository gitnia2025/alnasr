<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lab;

class LabController extends Controller
{
    
    /**
     * عرض جميع التعليقات.
     */
    public function index()
    {
        $lab = Lab::all(); // استرجاع جميع التعليقات
        return response()->json($lab);
    }

    /**
     * إضافة تعليق جديد.
     */
    // public function store(Request $request)
    // {
    //     // التحقق من البيانات المدخلة
    //     $validated = $request->validate([
    //         'name_ar' => 'required|string|max:255',
    //         'name_en' => 'required|string|max:255',
    //         'phone' => 'required|string|max:20',
    //         'whats' => 'required|string|max:20',
    //         'day_en' => 'required|string|max:50',
    //         'day_ar' => 'required|string|max:50',
    //         'time_from' => 'required|date_format:H:i',
    //         'time_to' => 'required|date_format:H:i',
    //     ]);

    //     // إنشاء التعليق الجديد
    //     $lab = Lab::create($validated);

    //     // إرجاع التعليق الذي تم إضافته مع حالة 201
    //     return response()->json($lab, 201);
    // }
    public function store(Request $request)
    {
        // التحقق من البيانات المدخلة
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'whats' => 'required|string|max:20',
            'day_en' => 'required|string|max:50',
            'day_ar' => 'required|string|max:50',
            'time_from' => 'required|date_format:H:i',
            'time_to' => 'required|date_format:H:i',
        ]);
    
        try {
            // إنشاء معمل جديد
            $lab = Lab::create($validated);
    
            // تأكيد العملية عبر log أو رسالة مباشرة
            \Log::info("Lab Created: ", $lab->toArray());
    
            // إرجاع المعمل الذي تم إضافته مع حالة 201
            return response()->json($lab, 201);
        } catch (\Exception $e) {
            // في حال حدوث خطأ في قاعدة البيانات أو أي مشكلة أخرى
            \Log::error("Error Creating Lab: " . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ في إضافة المعمل', 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * عرض تفاصيل تعليق معين.
     */
    public function show($id)
    {
        $lab = Lab::findOrFail($id); // العثور على التعليق باستخدام المعرف
        return response()->json($lab);
    }

    /**
     * تحديث تعليق معين.
     */
    public function update(Request $request, $id)
    {
        $lab = Lab::findOrFail($id); // العثور على التعليق

        // التحقق من البيانات المدخلة
        $validated = $request->validate([
            'name_ar' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whats' => 'nullable|string|max:20',
            'day_en' => 'nullable|string|max:50',
            'day_ar' => 'nullable|string|max:50',
            'time_from' => 'nullable|date_format:H:i',
            'time_to' => 'nullable|date_format:H:i',
        ]);

        // تحديث التعليق
        $lab->update($validated);

        return response()->json($lab);
    }

    /**
     * حذف تعليق معين.
     */
    public function destroy($id)
    {
        $lab = Lab::findOrFail($id); // العثور على التعليق
        $lab->delete(); // حذف التعليق

        return response()->json(null, 204); // إرجاع استجابة "بدون محتوى"
    }
}
