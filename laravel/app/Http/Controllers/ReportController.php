<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Publiek overzicht (zonder auth), met filters op jouw velden.
     */
    public function index(Request $request)
    {
        $reports = Report::query()
            ->when($request->filled('schip_naam'), fn($q) =>
            $q->where('schip_naam', 'like', '%' . $request->schip_naam . '%'))
            ->when($request->filled('schip_nummer'), fn($q) =>
            $q->where('schip_nummer', 'like', '%' . $request->schip_nummer . '%'))
            ->when($request->filled('schip_bouwjaar'), fn($q) =>
            $q->where('schip_bouwjaar', $request->schip_bouwjaar))
            ->when($request->filled('monteur'), fn($q) =>
            $q->where('monteur', 'like', '%' . $request->monteur . '%'))
            ->when($request->filled('description'), fn($q) =>
            $q->where('description', 'like', '%' . $request->description . '%'))
            ->when($request->filled('status'), fn($q) =>
            $q->where('status', $request->status))
            // optioneel: aangemaakt tussen
            ->when($request->filled('from'), fn($q) =>
            $q->whereDate('created_at', '>=', $request->from))
            ->when($request->filled('to'), fn($q) =>
            $q->whereDate('created_at', '<=', $request->to))
            ->orderByDesc('created_at')
            ->paginate((int) $request->integer('per_page', 12))
            ->withQueryString();

        return view('reports.index', [
            'reports' => $reports,
            'search'  => null, // niet meer in gebruik
            'from'    => $request->get('from'),
            'to'      => $request->get('to'),
        ]);
    }

    /**
     * Beheer-overzicht met tab-tellers en sorting.
     */
    public function beheer(Request $request)
    {
        $status  = $request->string('status', 'all')->toString();   // all|draft|submitted|archived
        $q       = $request->string('q')->toString();
        $sort    = $request->string('sort', 'created_desc')->toString(); // created_desc|created_asc|schip_naam_asc|...
        $perPage = (int) $request->integer('per_page', 12);

        // Tellers voor tabs
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
            $query->where(function ($qq) use ($q) {
                $qq->where('schip_naam', 'like', "%{$q}%")
                    ->orWhere('schip_nummer', 'like', "%{$q}%")
                    ->orWhere('monteur', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Sorteren (incl. aliasen voor oude 'title_*')
        switch ($sort) {
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'schip_naam_asc':
            case 'title_asc':
                $query->orderBy('schip_naam', 'asc');
                break;
            case 'schip_naam_desc':
            case 'title_desc':
                $query->orderBy('schip_naam', 'desc');
                break;
            case 'bouwjaar_asc':
                $query->orderBy('schip_bouwjaar', 'asc');
                break;
            case 'bouwjaar_desc':
                $query->orderBy('schip_bouwjaar', 'desc');
                break;
            case 'nummer_asc':
                $query->orderBy('schip_nummer', 'asc');
                break;
            case 'nummer_desc':
                $query->orderBy('schip_nummer', 'desc');
                break;
            case 'monteur_asc':
                $query->orderBy('monteur', 'asc');
                break;
            case 'monteur_desc':
                $query->orderBy('monteur', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc'); // created_desc
        }

        $reports = $query->paginate($perPage)->appends($request->query());

        return view('reports.beheer', compact('reports', 'counts', 'status', 'q', 'sort', 'perPage'));
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'schip_naam'     => ['required', 'string', 'max:120'],
            'schip_nummer'   => ['nullable', 'string', 'max:50'],
            'schip_bouwjaar' => ['nullable', 'integer', 'min:1800', 'max:' . (date('Y') + 1)],
            'monteur'        => ['required', 'string', 'max:120'],
            'description'    => ['nullable', 'string', 'max:5000'],
            'status'         => ['required', 'in:draft,submitted,archived'],
        ]);

        Report::create($data);

        return redirect()->route('reports.beheer')
            ->with('success', 'Rapportage aangemaakt.');
    }

    public function show(Report $report)
    {
        // (optioneel) lijstje voor zijbalk/overzicht
        $reports = Report::select('id', 'schip_naam', 'created_at', 'status')->latest()->get();

        return view('reports.show', compact('report', 'reports'));
    }

    public function edit(Report $report)
    {
        return view('reports.edit', compact('report'));
    }

    public function update(Request $request, Report $report)
    {
        $data = $request->validate([
            'schip_naam'     => ['required', 'string', 'max:120'],
            'schip_nummer'   => ['nullable', 'string', 'max:50'],
            'schip_bouwjaar' => ['nullable', 'integer', 'min:1800', 'max:' . (date('Y') + 1)],
            'monteur'        => ['required', 'string', 'max:120'],
            'description'    => ['nullable', 'string', 'max:5000'],
            'status'         => ['required', 'in:draft,submitted,archived'],
        ]);

        $report->update($data);

        return redirect()->route('reports.beheer')
            ->with('success', 'Rapportage bijgewerkt.');
    }

    public function destroy(Report $report)
    {
        $report->delete();

        return redirect()->route('reports.beheer')
            ->with('success', 'Rapportage verwijderd.');
    }
}
