<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    /**
     * عرض جميع العروض.
     */
    public function index()
    {
        // استرجاع جميع العروض من قاعدة البيانات
        $offers = Offer::all();
        
        // إرجاع العروض كـ JSON
        return response()->json($offers);
    }

    /**
     * إنشاء عرض جديد.
     */
    // public function store(Request $request)
    // {
    //     // التحقق من البيانات المدخلة
    //     $validated = $request->validate([
    //         'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // التحقق من الصورة
    //         'description' => 'required|string|max:255', // التحقق من الوصف
    //         // أضف باقي الحقول المطلوبة هنا
    //     ]);

    //     // حفظ الصورة في التخزين (أو استخدام طريقة تخزين أخرى مثل السحابة)
    //     $imagePath = $request->file('img')->store('offers', 'public');
        
    //     // إنشاء العرض الجديد
    //     $offer = Offer::create([
    //         'img' => $imagePath, // حفظ مسار الصورة في قاعدة البيانات
    //         'description' => $validated['description'],
    //         // أضف الحقول الأخرى المطلوبة هنا
    //     ]);

    //     // إرجاع العرض الذي تم إنشاؤه مع حالة 201
    //     return response()->json($offer, 201);
    // }
    public function store(Request $request)
    {
        // التحقق من البيانات المدخلة
        $validated = $request->validate([
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // التحقق من الصورة
            'description' => 'required|string|max:255', // التحقق من الوصف
            // أضف باقي الحقول المطلوبة هنا
        ]);
    
        // حفظ الصورة في التخزين (مجلد offers داخل التخزين العام)
        $imagePath = $request->file('img')->store('offers', 'public'); // تخزين الصورة داخل مجلد offers
    
        // الحصول على الرابط العام للصورة بعد تخزينها
        $imageUrl = asset('storage/' . $imagePath); // الرابط العام للصورة
    
        // إنشاء العرض الجديد وتخزين مسار الرابط في قاعدة البيانات
        $offer = Offer::create([
            'img' => $imageUrl, // حفظ رابط الصورة في قاعدة البيانات
            'description' => $validated['description'],
            // أضف الحقول الأخرى المطلوبة هنا
        ]);
    
        // إرجاع العرض الذي تم إنشاؤه مع حالة 201
        return response()->json($offer, 201);
    }
    
    /**
     * عرض تفاصيل عرض معين.
     */
    public function show($id)
    {
        // البحث عن العرض باستخدام المعرف
        $offer = Offer::findOrFail($id);
        
        // إرجاع العرض كـ JSON
        return response()->json($offer);
    }

    /**
     * تحديث عرض معين.
     */
    public function update(Request $request, $id)
    {
        // البحث عن العرض باستخدام المعرف
        $offer = Offer::findOrFail($id);

        // التحقق من البيانات المدخلة
        $validated = $request->validate([
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:255',
            // أضف باقي الحقول المطلوبة هنا
        ]);

        // إذا كانت صورة جديدة، قم بتخزينها
        if ($request->hasFile('img')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($offer->img) {
                Storage::disk('public')->delete($offer->img);
            }

            // حفظ الصورة الجديدة
            $imagePath = $request->file('img')->store('offers', 'public');
            $offer->img = $imagePath;
        }

        // تحديث العرض
        $offer->update([
            'description' => $validated['description'] ?? $offer->description,
            // أضف باقي الحقول التي تحتاج إلى تحديث هنا
        ]);

        // إرجاع العرض المحدث
        return response()->json($offer);
    }

    /**
     * حذف عرض معين.
     */
    public function destroy($id)
    {
        // البحث عن العرض باستخدام المعرف
        $offer = Offer::findOrFail($id);
        
        // حذف الصورة إذا كانت موجودة
        if ($offer->img) {
            Storage::disk('public')->delete($offer->img);
        }

        // حذف العرض من قاعدة البيانات
        $offer->delete();

        // إرجاع استجابة بدون محتوى (204 No Content)
        return response()->json(null, 204);
    }
}
