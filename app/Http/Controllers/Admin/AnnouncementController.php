<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest('published_at')
            ->latest('id')
            ->paginate(10);

        return view('back.Announcements.index', compact('announcements'));
    }

    public function create()
    {
        $announcement = new Announcement([
            'published_at' => now(),
        ]);

        return view('back.Announcements.create', compact('announcement'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        Announcement::create($data);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function edit(Announcement $announcement)
    {
        return view('back.Announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $this->validateData($request);

        $announcement->update($data);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement deleted.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'published_at' => ['nullable', 'date'],
        ]);
    }
}
