<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseMaterialController extends Controller
{
    public function index(Course $course)
    {
        // Check if user owns this course
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $materials = $course->materials()->latest()->paginate(10);

        // Get user's plan limits
        $user = Auth::user();
        $plan = Plan::where('name', $user->plan)->first();
        $contentLimit = $plan->content_upload_limit ?? 0;

        // Count total materials across all user's courses
        $currentMaterialCount = CourseMaterial::whereHas('course', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        return view('course-materials.index', compact('course', 'materials', 'contentLimit', 'currentMaterialCount'));
    }

    public function create(Course $course)
    {
        // Check if user owns this course
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check material limit
        $user = Auth::user();
        $plan = Plan::where('name', $user->plan)->first();
        $contentLimit = $plan->content_upload_limit ?? 0;

        $currentMaterialCount = CourseMaterial::whereHas('course', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        if ($currentMaterialCount >= $contentLimit) {
            return redirect()->route('course-materials.index', $course)
                ->with('error', 'You have reached your content upload limit. Please upgrade your plan.');
        }

        return view('course-materials.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        // Check if user owns this course
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check material limit
        $user = Auth::user();
        $plan = Plan::where('name', $user->plan)->first();
        $contentLimit = $plan->content_upload_limit ?? 0;

        $currentMaterialCount = CourseMaterial::whereHas('course', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        if ($currentMaterialCount >= $contentLimit) {
            return redirect()->route('course-materials.index', $course)
                ->with('error', 'You have reached your content upload limit. Please upgrade your plan.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,document',
            'file' => 'required|file|max:102400', // Max 100MB
            'visibility' => 'required|in:private,public',
        ]);

        // Validate file types based on type
        if ($request->type === 'video') {
            $request->validate([
                'file' => 'mimes:mp4,mov,avi,wmv,flv,mkv'
            ]);
        } else {
            $request->validate([
                'file' => 'mimes:pdf,ppt,pptx,doc,docx,xls,xlsx,zip,rar'
            ]);
        }

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->store('course-materials/' . $course->id, 'public');

        CourseMaterial::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'visibility' => $request->visibility
        ]);

        return redirect()->route('course-materials.index', $course)
            ->with('success', 'Material uploaded successfully!');
    }

    public function destroy(Course $course, CourseMaterial $material)
    {
        // Check if user owns this course
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if material belongs to this course
        if ($material->course_id !== $course->id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete file from storage
        Storage::disk('public')->delete($material->file_path);

        // Delete material record
        $material->delete();

        return redirect()->route('course-materials.index', $course)
            ->with('success', 'Material deleted successfully!');
    }

    public function show(Course $course, CourseMaterial $material)
    {
        // Check if user owns this course
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if material belongs to this course
        if ($material->course_id !== $course->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('course-materials.show', compact('course', 'material'));
    }

    public function edit(Course $course, CourseMaterial $material)
    {
        // Check if user owns this course
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if material belongs to this course
        if ($material->course_id !== $course->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('course-materials.edit', compact('course', 'material'));
    }

    public function update(Request $request, Course $course, CourseMaterial $material)
    {
        // Check if user owns this course
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if material belongs to this course
        if ($material->course_id !== $course->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'visibility' => 'required|in:private,public',
        ]);

        $material->update([
            'title' => $request->title,
            'visibility' => $request->visibility,
        ]);

        return redirect()->route('app.course-materials.index', $course)
            ->with('success', 'Material updated successfully!');
    }

    public function download(Course $course, CourseMaterial $material)
    {
        // Check if user owns this course
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if material belongs to this course
        if ($material->course_id !== $course->id) {
            abort(403, 'Unauthorized action.');
        }

        return Storage::disk('public')->download($material->file_path, $material->file_name);
    }

    public function stream(Course $course, CourseMaterial $material)
    {
        // Check if user owns this course
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if material belongs to this course
        if ($material->course_id !== $course->id) {
            abort(403, 'Unauthorized action.');
        }

        $path = Storage::disk('public')->path($material->file_path);

        if (!file_exists($path)) {
            abort(404, 'File not found.');
        }

        return response()->file($path, [
            'Content-Type' => $material->mime_type,
            'Content-Disposition' => 'inline; filename="' . $material->file_name . '"',
        ]);
    }
}
