<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::latest()->paginate(12);
        return view('welcome', compact('reports'));
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
}
