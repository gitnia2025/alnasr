<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Doc;
use Illuminate\Http\Request;

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
            'day_en' => 'nullable|string|max:255', // حقل يوم الأسبوع بالإنجليزية اختياري
            'day_ar' => 'nullable|string|max:255', // حقل يوم الأسبوع بالعربية اختياري
            'specialist_ar' => 'nullable|string|max:255', // إضافة حقل specialist_ar اختياري
            'specialist_en' => 'nullable|string|max:255', // إضافة حقل specialist_en اختياري
            'certificate_ar' => 'nullable|string|max:255',
            'certificate_en' => 'nullable|string|max:255',
            'exp_ar' => 'nullable|string|max:255',
            'exp_en' => 'nullable|string|max:255',
            'desc_ar'=> 'nullable|string|max:255',
            'desc_en'=> 'nullable|string|max:255',
        ]);
        
        // حفظ الصورة في التخزين (مجلد docs داخل التخزين العام)
        $imagePath = $request->file('img')->store('docs', 'public'); // تخزين الصورة في مجلد docs داخل public storage
        
        // حفظ السيرة الذاتية إذا كانت موجودة
        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('docs', 'public'); // تخزين السيرة الذاتية في مجلد docs
        }
        
        // إنشاء السجل في قاعدة البيانات مع حفظ الروابط
        $doc = Doc::create([
            'img' => asset('/storage/app/public/' . $imagePath), // حفظ الرابط العام للصورة
            'cv' => $cvPath ? asset('/storage/app/public/' . $cvPath) : null, // حفظ الرابط العام للسيرة الذاتية (إن كانت موجودة)
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'specialist_id' => $request->specialist_id,
            'phone' => $request->phone,
            'whats' => $request->whats,
            'age' => $request->age,
            'sex' => $request->sex,
            'floor' => $request->floor ?? 'no determine',
            'day_en' => $request->day_en ?? 'not specified', // تعيين قيمة افتراضية إذا لم يتم تحديدها
            'day_ar' => $request->day_ar ?? 'غير محدد', // تعيين قيمة افتراضية إذا لم يتم تحديدها
            'specialist_ar' => $request->specialist_ar ?? 'غير محدد', // تعيين قيمة افتراضية لحقل specialist_ar
            'specialist_en' => $request->specialist_en ?? 'Not specified', // تعيين قيمة افتراضية لحقل specialist_en
            'time_from' => $request->time_from,
            'time_to' => $request->time_to,
            'certificate_ar' => $request->certificate_ar ?? 'غير متوفر', // تعيين قيمة افتراضية إذا لم يتم تحديدها
            'certificate_en' => $request->certificate_en ?? 'Not available', // تعيين قيمة افتراضية إذا لم يتم تحديدها
            'exp_ar' => $request->exp_ar ?? 'لا يوجد خبرة', // تعيين قيمة افتراضية إذا لم يتم تحديدها
            'exp_en' => $request->exp_en ?? 'No experience', // تعيين قيمة افتراضية إذا لم يتم تحديدها
            'active' => $request->active ?? true, // تعيين قيمة افتراضية
            'show' => $request->show ?? true, // تعيين قيمة افتراضية
            'desc_ar'=>  $request->desc_ar ?? 'غير محدد',
            'desc_en'=>  $request->desc_ar ?? ' Not specified',

        ]);
        
        // إرجاع السجل الذي تم إنشاؤه مع حالة 201
        return response()->json($doc, 201);
    }
    
    
    
    // Get all Docs
    public function index()
    {
        $docs = Doc::with('specialist')->get();
        return response()->json($docs);
    }

    // Get a single Doc by ID
    public function show($id)
    {
        $doc = Doc::with('specialist')->findOrFail($id);
        return response()->json($doc);
    }

    // Update a Doc by ID
    public function update(Request $request, $id)
    {
        $doc = Doc::findOrFail($id);

        $doc->update($request->all());

        return response()->json($doc);
    }

    // Delete a Doc by ID
    public function destroy($id)
    {
        $doc = Doc::findOrFail($id);
        $doc->delete();
        return response()->json(['message' => 'Doc deleted successfully']);
    }
}
