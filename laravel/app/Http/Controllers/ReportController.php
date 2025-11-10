<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\InspectionList;
use App\Models\ReportCheckItem;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
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
            ->when($request->filled('from'), fn($q) =>
            $q->whereDate('created_at', '>=', $request->from))
            ->when($request->filled('to'), fn($q) =>
            $q->whereDate('created_at', '<=', $request->to))
            ->with(['inspectionList:id,title']) // voor badge/naam in index
            ->orderByDesc('created_at')
            ->paginate((int) $request->integer('per_page', 12))
            ->withQueryString();

        return view('reports.index', [
            'reports' => $reports,
            'from'    => $request->get('from'),
            'to'      => $request->get('to'),
        ]);
    }

    public function beheer(Request $request)
    {
        $status  = $request->string('status', 'all')->toString();
        $q       = $request->string('q')->toString();
        $sort    = $request->string('sort', 'created_desc')->toString();
        $perPage = (int) $request->integer('per_page', 12);

        $counts = [
            'all'       => Report::count(),
            'draft'     => Report::where('status', 'draft')->count(),
            'submitted' => Report::where('status', 'submitted')->count(),
            'archived'  => Report::where('status', 'archived')->count(),
        ];

        $query = Report::query()->with(['inspectionList:id,title']);

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
                $query->orderBy('created_at', 'desc');
        }

        $reports = $query->paginate($perPage)->appends($request->query());

        return view('reports.beheer', compact('reports', 'counts', 'status', 'q', 'sort', 'perPage'));
    }

    public function create()
    {
        // Voor de dropdown om te koppelen
        $inspections = InspectionList::orderBy('created_at', 'desc')
            ->get(['id', 'title']);

        return view('reports.create', compact('inspections'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'schip_naam'        => ['required', 'string', 'max:120'],
            'schip_nummer'      => ['nullable', 'string', 'max:50'],
            'schip_bouwjaar'    => ['nullable', 'integer', 'min:1800', 'max:' . (date('Y') + 1)],
            'monteur'           => ['required', 'string', 'max:120'],
            'description'       => ['nullable', 'string', 'max:5000'],
            'status'            => ['required', 'in:draft,submitted,archived'],
            'inspection_list_id' => ['nullable', 'exists:inspection_lists,id'],
        ]);

        Report::create($data);

        return redirect()
            ->route('reports.beheer')
            ->with('success', 'Rapportage aangemaakt.');
    }

    public function show(Report $report)
    {
        // Sidebar/overzicht
        $reports = Report::with(['inspectionList.categories.checks'])
            ->orderByDesc('created_at')
            ->get();

        // Laad de gekoppelde inspectielijst (inclusief nested relaties)
        $report->load(['inspectionList.categories.checks', 'checkItems']);

        $checkItems = $report->checkItems->keyBy('inspection_check_id');

        // Als je (tijdelijk) nog een fallback wilt naar "laatste inspectielijst"
        // wanneer er geen koppeling is:
        $inspection = $report->inspectionList
            ?: InspectionList::with('categories.checks')->latest()->first();

        $submittedPdfs = collect(Storage::disk('public')->files('reports'))
            ->filter(fn ($path) => str_starts_with($path, "reports/report-{$report->id}-"))
            ->sortDesc()
            ->values();

        return view('reports.show', [
            'report'        => $report,
            'reports'       => $reports,
            'inspection'    => $inspection,
            'checkItems'    => $checkItems,
            'submittedPdfs' => $submittedPdfs,
        ]);
    }

    public function edit(Report $report)
    {
        $inspections = InspectionList::orderBy('created_at', 'desc')
            ->get(['id', 'title']);

        return view('reports.edit', compact('report', 'inspections'));
    }

    public function update(Request $request, Report $report)
    {
        $data = $request->validate([
            'schip_naam'        => ['required', 'string', 'max:120'],
            'schip_nummer'      => ['nullable', 'string', 'max:50'],
            'schip_bouwjaar'    => ['nullable', 'integer', 'min:1800', 'max:' . (date('Y') + 1)],
            'monteur'           => ['required', 'string', 'max:120'],
            'description'       => ['nullable', 'string', 'max:5000'],
            'status'            => ['required', 'in:draft,submitted,archived'],
            'inspection_list_id' => ['nullable', 'exists:inspection_lists,id'],
        ]);

        $report->update($data);

        return redirect()
            ->route('reports.beheer')
            ->with('success', 'Rapportage bijgewerkt.');
    }

    public function destroy(Report $report)
    {
        $report->delete();

        return redirect()
            ->route('reports.beheer')
            ->with('success', 'Rapportage verwijderd.');
    }

    public function saveProgress(Request $request, Report $report)
    {
        $validated = $request->validate([
            'checks' => ['required', 'array'],
            'checks.*.status' => ['required', 'in:pending,gecontroleerd,bijzonderheden'],
            'checks.*.notes' => ['nullable', 'string', 'max:2000'],
            'checks.*.photos' => ['sometimes', 'array'],
            'checks.*.photos.*' => ['nullable', 'image', 'max:5120'],
        ]);

        [$ok, $message] = $this->syncCheckItems($report, $validated['checks']);

        if (!$ok) {
            return back()
                ->withErrors(['progress' => $message])
                ->withInput();
        }

        return back()->with('success', 'Rapportage voortgang opgeslagen.');
    }

    public function submit(Request $request, Report $report)
    {
        $validated = $request->validate([
            'checks' => ['required', 'array'],
            'checks.*.status' => ['required', 'in:pending,gecontroleerd,bijzonderheden'],
            'checks.*.notes' => ['nullable', 'string', 'max:2000'],
            'checks.*.photos' => ['sometimes', 'array'],
            'checks.*.photos.*' => ['nullable', 'image', 'max:5120'],
            'signature' => ['required', 'string'],
        ]);

        [$ok, $message] = $this->syncCheckItems($report, $validated['checks']);

        if (!$ok) {
            return back()
                ->withErrors(['progress' => $message])
                ->withInput();
        }

        $oldSignature = $report->signature_path;
        $oldPdf = $report->submitted_pdf_path;

        $signaturePath = $this->storeSignature($validated['signature'], $report);

        if ($oldSignature && $oldSignature !== $signaturePath) {
            Storage::disk('public')->delete($oldSignature);
        }

        $report->load(['inspectionList.categories.checks', 'checkItems']);

        $groupedChecks = $this->groupChecksForExport($report);

        $signatureImage = null;
        if ($signaturePath && Storage::disk('public')->exists($signaturePath)) {
            $signatureImage = 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($signaturePath));
        }

        $pdf = Pdf::loadView('reports.pdf', [
            'report' => $report,
            'groupedChecks' => $groupedChecks,
            'signatureImage' => $signatureImage,
        ])->setPaper('a4');

        $pdfPath = "reports/report-{$report->id}-" . now()->format('YmdHis') . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        if ($oldPdf && $oldPdf !== $pdfPath) {
            Storage::disk('public')->delete($oldPdf);
        }

        $report->update([
            'signature_path' => $signaturePath,
            'submitted_pdf_path' => $pdfPath,
            'submitted_at' => now(),
            'status' => 'submitted',
        ]);

        return redirect()
            ->route('reports.show', $report)
            ->with('success', 'Rapportage verzonden en PDF opgeslagen.');
    }

    private function syncCheckItems(Report $report, array $checksPayload): array
    {
        $complete = collect($checksPayload)->every(
            fn($check) => in_array($check['status'], ['gecontroleerd', 'bijzonderheden'], true)
        );

        if (!$complete) {
            return [false, 'Niet alle controles zijn toegewezen aan Gecontroleerd of Bijzonderheden.'];
        }

        foreach ($checksPayload as $checkId => $payload) {
            $item = ReportCheckItem::firstOrNew([
                'report_id' => $report->id,
                'inspection_check_id' => $checkId,
            ]);

            $status = $payload['status'];
            $item->status = $status;

            if ($status === 'bijzonderheden') {
                $item->notes = $payload['notes'] ?? null;
                $existingPhotos = $item->photos ?? [];

                if (!empty($payload['photos'])) {
                    foreach ($payload['photos'] as $photo) {
                        if ($photo) {
                            $existingPhotos[] = $photo->store("report-checks/{$report->id}", 'public');
                        }
                    }
                }

                $item->photos = $existingPhotos ? array_values(array_filter($existingPhotos)) : null;
            } else {
                if (!empty($item->photos)) {
                    foreach ($item->photos as $storedPath) {
                        Storage::disk('public')->delete($storedPath);
                    }
                }

                $item->notes = null;
                $item->photos = null;
            }

            $item->save();
        }

        return [true, null];
    }

    private function storeSignature(string $rawSignature, Report $report): string
    {
        if (!Str::startsWith($rawSignature, 'data:image')) {
            throw ValidationException::withMessages([
                'signature' => 'Ongeldig handtekening-formaat.',
            ]);
        }

        if (!str_contains($rawSignature, ',')) {
            throw ValidationException::withMessages([
                'signature' => 'Ongeldig handtekening-formaat.',
            ]);
        }

        [, $data] = explode(',', $rawSignature, 2);
        $binary = base64_decode($data);

        if ($binary === false) {
            throw ValidationException::withMessages([
                'signature' => 'Het tekenen is niet gelukt, probeer opnieuw.',
            ]);
        }

        $path = "signatures/report-{$report->id}-" . now()->format('YmdHis') . '.png';
        Storage::disk('public')->put($path, $binary);

        return $path;
    }

    private function groupChecksForExport(Report $report): array
    {
        $report->loadMissing(['inspectionList.categories.checks', 'checkItems']);

        $items = $report->checkItems->keyBy('inspection_check_id');

        $result = [
            'gecontroleerd' => [],
            'bijzonderheden' => [],
        ];

        if (!$report->inspectionList) {
            return $result;
        }

        foreach ($report->inspectionList->categories as $category) {
            foreach ($category->checks as $check) {
                $item = $items->get($check->id);
                if (!$item) {
                    continue;
                }

                if (! isset($result[$item->status])) {
                    $result[$item->status] = [];
                }

                $result[$item->status][] = [
                    'category' => $category->name,
                    'label' => $check->label ?? 'Controle #' . $check->id,
                    'code' => $check->code,
                    'notes' => $item->notes,
                    'photos' => $item->photos ?? [],
                ];
            }
        }

        return $result;
    }
}
