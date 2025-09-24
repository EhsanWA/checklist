<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // app/Http/Controllers/ReportController.php
    public function index(Request $request)
    {
        $status   = $request->string('status', 'all')->toString(); // all|draft|submitted|archived
        $q        = $request->string('q')->toString();
        $sort     = $request->string('sort', 'created_desc')->toString(); // created_desc|created_asc|title_asc|title_desc
        $perPage  = (int) $request->integer('per_page', 12);

        // Tab tellers
        $counts = [
            'all'       => Report::count(),
            'draft'     => Report::where('status', 'draft')->count(),
            'submitted' => Report::where('status', 'submitted')->count(),
            'archived'  => Report::where('status', 'archived')->count(),
        ];

        $query = Report::query();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($q) {
            $query->where(
                fn($qq) =>
                $qq->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
            );
        }

        // Sorteren
        match ($sort) {
            'created_asc' => $query->orderBy('created_at', 'asc'),
            'title_asc'   => $query->orderBy('title', 'asc'),
            'title_desc'  => $query->orderBy('title', 'desc'),
            default       => $query->orderBy('created_at', 'desc'), // created_desc
        };

        $reports = $query->paginate($perPage)->appends($request->query());

        return view('reports.index', compact('reports', 'counts', 'status', 'q', 'sort', 'perPage'));
    }


    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        $report = Report::create($data);
        return redirect()
            ->route('reports.index')
            ->with('success', 'Rapportage aangemaakt: ' . $report->title);
    }

    public function show(Report $report)
    {
        return view('reports.show', compact('report'));
    }

    // app/Http/Controllers/ReportController.php

    public function edit(Report $report)
    {
        return view('reports.edit', compact('report'));
    }

    public function update(Request $request, Report $report)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status'      => ['required', 'in:draft,submitted,archived'],
        ]);

        $report->update($data);

        return redirect()
            ->route('reports.index')
            ->with('success', 'Rapportage bijgewerkt.');
    }

    public function destroy(Report $report)
    {
        $report->delete();

        return redirect()
            ->route('reports.index')
            ->with('success', 'Rapportage verwijderd.');
    }
}
