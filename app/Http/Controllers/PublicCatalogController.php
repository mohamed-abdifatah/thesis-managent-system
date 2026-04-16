<?php

namespace App\Http\Controllers;

use App\Models\Thesis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicCatalogController extends Controller
{
    public function index()
    {
        $search = trim((string) request('q', ''));
        $sort = trim((string) request('sort', 'newest'));

        $booksQuery = Thesis::query()
            ->with(['student.user', 'group.students.user', 'supervisor.user', 'finalThesisVersion'])
            ->where('status', 'completed')
            ->where('is_library_approved', true)
            ->where('is_public', true)
            ->whereHas('finalThesisVersion', fn ($query) => $query->where('status', 'approved'));

        if ($search !== '') {
            $booksQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhereHas('student.user', fn ($studentQuery) => $studentQuery->where('name', 'like', '%' . $search . '%'))
                    ->orWhereHas('group', fn ($groupQuery) => $groupQuery->where('name', 'like', '%' . $search . '%'));
            });
        }

        if ($sort === 'popular') {
            $booksQuery
                ->orderByDesc('public_downloads')
                ->orderByDesc('published_at');
        } else {
            $booksQuery->orderByDesc('published_at');
        }

        $books = $booksQuery
            ->paginate(12)
            ->withQueryString();

        return view('public.books.index', compact('books', 'search', 'sort'));
    }

    public function show(Thesis $thesis)
    {
        if (!$this->isPubliclyAccessible($thesis)) {
            abort(404);
        }

        $thesis->load([
            'student.user',
            'group.students.user',
            'supervisor.user',
            'finalThesisVersion',
            'publisher',
            'proposals',
            'catalogEvents.user',
        ]);

        $publicFinalVersion = $thesis->finalThesisVersion;

        if (!$publicFinalVersion || $publicFinalVersion->status !== 'approved') {
            abort(404);
        }

        $recentEvents = $thesis->catalogEvents->take(8);

        return view('public.books.show', compact('thesis', 'recentEvents', 'publicFinalVersion'));
    }

    public function download(Thesis $thesis)
    {
        if (!$this->isPubliclyAccessible($thesis)) {
            abort(404);
        }

        $version = $thesis->finalThesisVersion()
            ->where('status', 'approved')
            ->first();

        if (!$version || !$version->file_path || !Storage::disk('public')->exists($version->file_path)) {
            abort(404);
        }

        $thesis->increment('public_downloads');

        $extension = pathinfo($version->file_path, PATHINFO_EXTENSION) ?: 'pdf';
        $filename = Str::slug($thesis->title) . '-v' . $version->version_number . '-final.' . $extension;

        return Storage::disk('public')->download($version->file_path, $filename);
    }

    private function isPubliclyAccessible(Thesis $thesis): bool
    {
        return (bool) ($thesis->is_public && $thesis->is_library_approved && $thesis->status === 'completed');
    }
}
