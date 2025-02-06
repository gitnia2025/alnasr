<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * عرض جميع التعليقات.
     */
    public function index()
    {
        $feedbacks = Feedback::all(); // استرجاع جميع التعليقات
        return response()->json($feedbacks);
    }

    /**
     * إضافة تعليق جديد.
     */
    public function store(Request $request)
    {
        // التحقق من البيانات المدخلة
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'Doc_name' => 'required|string|max:255',
            'desc' => 'required|string',
        ]);

        // إنشاء تعليق جديد
        $feedback = Feedback::create($validated);

        // إرجاع التعليق الذي تم إضافته مع حالة 201
        return response()->json($feedback, 201);
    }

    /**
     * عرض تفاصيل تعليق معين.
     */
    public function show($id)
    {
        // العثور على التعليق باستخدام المعرف
        $feedback = Feedback::findOrFail($id);
        return response()->json($feedback);
    }

    /**
     * تحديث تعليق معين.
     */
    public function update(Request $request, $id)
    {
        // العثور على التعليق باستخدام المعرف
        $feedback = Feedback::findOrFail($id);

        // التحقق من البيانات المدخلة
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'Doc_name' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
        ]);

        // تحديث التعليق
        $feedback->update($validated);

        return response()->json($feedback);
    }

    /**
     * حذف تعليق معين.
     */
    public function destroy($id)
    {
        // العثور على التعليق باستخدام المعرف
        $feedback = Feedback::findOrFail($id);
        $feedback->delete(); // حذف التعليق

        return response()->json(null, 204); // إرجاع استجابة "بدون محتوى"
    }
}
