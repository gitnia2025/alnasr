<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Doc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DocController extends Controller
{
    
public function store(Request $request)
 {
    // التحقق من البيانات المدخلة
    $validated = $request->validate([
        'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // التحقق من الصورة
        'cv' => 'nullable|mimes:pdf,doc,docx|max:2048', // التحقق من السيرة الذاتية
        'name_ar' => 'required|string|max:255',
        'name_en' => 'required|string|max:255',
        'specialist_id' => 'required|exists:specialists,id',
        'phone' => 'required|string',
        'whats' => 'required|string',
        'floor' => 'required|string',
        'age' => 'required|integer',
        'sex' => 'required|in:male,female',
        'day_en' => 'nullable|string|max:255',
        'day_ar' => 'nullable|string|max:255',
        'specialist_ar' => 'nullable|string|max:255',
        'specialist_en' => 'nullable|string|max:255',
        'certificate_ar' => 'nullable|string|max:255',
        'certificate_en' => 'nullable|string|max:255',
        'exp_ar' => 'nullable|string|max:255',
        'exp_en' => 'nullable|string|max:255',
        'desc_ar'=> 'nullable|string|max:255',
        'desc_en'=> 'nullable|string|max:255',
    ]);

    // حفظ الصورة في التخزين العام مع إرجاع اسم الملف فقط
    $imageName = $request->file('img')->hashName();
    $request->file('img')->storeAs('docs', $imageName, 'public'); // حفظ الصورة داخل docs

    // حفظ السيرة الذاتية إذا كانت موجودة بنفس الطريقة
    $cvName = null;
    if ($request->hasFile('cv')) {
        $cvName = $request->file('cv')->hashName();
        $request->file('cv')->storeAs('docs', $cvName, 'public');
    }

    // إنشاء السجل في قاعدة البيانات مع حفظ اسم الملف فقط
    $doc = Doc::create([
        'img' => $imageName, // تخزين اسم الصورة فقط
        'cv' => $cvName, // تخزين اسم ملف الـ CV فقط (إن وجد)
        'name_ar' => $request->name_ar,
        'name_en' => $request->name_en,
        'specialist_id' => $request->specialist_id,
        'phone' => $request->phone,
        'whats' => $request->whats,
        'age' => $request->age,
        'sex' => $request->sex,
        'floor' => $request->floor ?? 'no determine',
        'day_en' => $request->day_en ?? 'not specified',
        'day_ar' => $request->day_ar ?? 'غير محدد',
        'specialist_ar' => $request->specialist_ar ?? 'غير محدد',
        'specialist_en' => $request->specialist_en ?? 'Not specified',
        'time_from' => $request->time_from,
        'time_to' => $request->time_to,
        'certificate_ar' => $request->certificate_ar ?? 'غير متوفر',
        'certificate_en' => $request->certificate_en ?? 'Not available',
        'exp_ar' => $request->exp_ar ?? 'لا يوجد خبرة',
        'exp_en' => $request->exp_en ?? 'No experience',
        'active' => $request->active ?? true,
        'show' => $request->show ?? true,
        'desc_ar'=>  $request->desc_ar ?? 'غير محدد',
        'desc_en'=>  $request->desc_en ?? 'Not specified',
    ]);

    return response()->json($doc, 201);
}

public function index()
{
    $docs = Doc::with('specialist')->get();

    // تعديل البيانات لإضافة مسار الصورة والسيرة الذاتية الصحيح
    $docs->transform(function ($doc) {
        $doc->img = $doc->img ? 'docs/' . $doc->img : null;
        $doc->cv = $doc->cv ? 'docs/' . $doc->cv : null;
        return $doc;
    });

    return response()->json($docs);
}

public function show($id)
{
    // Fetch the doc with its associated specialist
    $doc = Doc::with('specialist')->findOrFail($id);

    $doc->img = $doc->img ? 'docs/' . $doc->img : null;
    $doc->cv = $doc->cv ? 'docs/' . $doc->cv : null;

    // Return the doc as JSON response
    return response()->json($doc);
}

public function showtop()
{
    try {
        // تسجيل بداية العملية
        Log::info('Fetching docs with show = 1');

        // استرجاع جميع الدكاترة الذين لديهم show = 1 مع تحميل التخصص
        $docs = Doc::with('specialist')->where('show', 1)->get();

        // إذا كانت الدكاترة موجودة
        Log::info('Fetched ' . $docs->count() . ' doctors with show = 1');

        // إرجاع البيانات بشكل JSON
        return response()->json($docs);

    } catch (\Exception $e) {
        // تسجيل الخطأ في حالة حدوث استثناء
        Log::error('Error fetching docs with show = 1: ' . $e->getMessage());

        // إرجاع رسالة خطأ
        return response()->json(['error' => 'Something went wrong. Please try again later.'], 500);
    }
}



   
public function update(Request $request, $id)
{
    $doc = Doc::findOrFail($id);

    // التحقق من البيانات المدخلة
    $validated = $request->validate([
        'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // التحقق من الصورة
        'cv' => 'nullable|mimes:pdf,doc,docx|max:2048', // التحقق من السيرة الذاتية
        'name_ar' => 'nullable|string|max:255',
        'name_en' => 'nullable|string|max:255',
        'specialist_id' => 'nullable|exists:specialists,id',
        'phone' => 'nullable|string',
        'whats' => 'nullable|string',
        'floor' => 'nullable|string',
        'age' => 'nullable|integer',
        'sex' => 'nullable|in:male,female',
        'day_en' => 'nullable|string|max:255',
        'day_ar' => 'nullable|string|max:255',
        'specialist_ar' => 'nullable|string|max:255',
        'specialist_en' => 'nullable|string|max:255',
        'certificate_ar' => 'nullable|string|max:255',
        'certificate_en' => 'nullable|string|max:255',
        'exp_ar' => 'nullable|string|max:255',
        'exp_en' => 'nullable|string|max:255',
        'desc_ar'=> 'nullable|string|max:255',
        'desc_en'=> 'nullable|string|max:255',
    ]);

    // تحديث الصورة إذا تم رفع صورة جديدة
    if ($request->hasFile('img')) {
        $imageName = $request->file('img')->hashName();
        $request->file('img')->storeAs('docs', $imageName, 'public');
        $doc->img = $imageName; // تحديث اسم الصورة الجديدة
    }

    // تحديث السيرة الذاتية إذا تم رفع ملف جديد
    if ($request->hasFile('cv')) {
        $cvName = $request->file('cv')->hashName();
        $request->file('cv')->storeAs('docs', $cvName, 'public');
        $doc->cv = $cvName; // تحديث اسم ملف السيرة الذاتية الجديد
    }

    // تحديث باقي الحقول بدون التأثير على الحقول غير المرسلة
    $doc->update($request->except(['img', 'cv']));

    return response()->json($doc);
}
public function getDoctorsBySpecialist($specialist_id)
{
    // جلب جميع الأطباء الذين ينتمون إلى القسم المحدد مع جلب معلومات القسم أيضًا
    $docs = Doc::with('specialist')->where('specialist_id', $specialist_id)->get();

    // تعديل البيانات لإضافة مسار الصورة والسيرة الذاتية الصحيح
    $docs->transform(function ($doc) {
        $doc->img = $doc->img ? 'docs/' . $doc->img : null;
        $doc->cv = $doc->cv ? 'docs/' . $doc->cv : null;
        return $doc;
    });

    return response()->json($docs);
}

    // Delete a Doc by ID
public function destroy($id)
    {
        $doc = Doc::findOrFail($id);
        $doc->delete();
        return response()->json(['message' => 'Doc deleted successfully']);
    }
}
