<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $courses = Course::where('user_id', $user->id)
            ->with('category')
            ->latest()
            ->paginate(10);

        // Get user's plan limits
        $plan = Plan::where('name', $user->plan)->first();
        $courseLimit = $plan->course_limit ?? 0;
        $currentCourseCount = Course::where('user_id', $user->id)->count();

        return view('courses.index', compact('courses', 'courseLimit', 'currentCourseCount'));
    }

    public function create()
    {
        $user = Auth::user();

        // Check course limit
        $plan = Plan::where('name', $user->plan)->first();
        $courseLimit = $plan->course_limit ?? 0;
        $currentCourseCount = Course::where('user_id', $user->id)->count();

        if ($currentCourseCount >= $courseLimit) {
            return redirect()->route('app.courses.index')
                ->with('error', 'You have reached your course limit. Please upgrade your plan.');
        }

        $categories = Category::all();

        return view('courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Check course limit
        $plan = Plan::where('name', $user->plan)->first();
        $courseLimit = $plan->course_limit ?? 0;
        $currentCourseCount = Course::where('user_id', $user->id)->count();

        if ($currentCourseCount >= $courseLimit) {
            return redirect()->route('app.courses.index')
                ->with('error', 'You have reached your course limit. Please upgrade your plan.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:open,closed'
        ]);

        Course::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'status' => $request->status
        ]);

        return redirect()->route('app.courses.index')
            ->with('success', 'Course created successfully!');
    }

    public function show(Course $course)
    {
        // Check if user owns this course
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        // Check if user owns this course
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::all();

        return view('courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        // Check if user owns this course
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:open,closed'
        ]);

        $course->update($request->only([
            'title',
            'description',
            'category_id',
            'price',
            'status'
        ]));

        return redirect()->route('app.courses.index')
            ->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course)
    {
        // Check if user owns this course
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $course->delete();

        return redirect()->route('app.courses.index')
            ->with('success', 'Course deleted successfully!');
    }
}
