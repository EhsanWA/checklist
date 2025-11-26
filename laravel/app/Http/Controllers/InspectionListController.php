<?php
// app/Http/Controllers/InspectionListController.php
namespace App\Http\Controllers;

use App\Models\InspectionList;
use App\Models\InspectionCategory;
use App\Models\InspectionCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InspectionListController extends Controller
{
    public function create()
    {
        return view('inspections.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatePayload($request);

        DB::transaction(function () use ($data) {
            $list = InspectionList::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
            ]);

            foreach ($data['categories'] as $i => $cat) {
                $category = InspectionCategory::create([
                    'inspection_list_id' => $list->id,
                    'name' => $cat['name'],
                    'sort' => $cat['sort'] ?? $i,
                ]);

                foreach ($cat['checks'] as $j => $chk) {
                    InspectionCheck::create([
                        'inspection_category_id' => $category->id,
                        'label' => $chk['label'],
                        'code' => $chk['code'] ?? null,
                        'required' => isset($chk['required']) ? (bool)$chk['required'] : true,
                        'severity' => $chk['severity'] ?? 'info',
                        'sort' => $chk['sort'] ?? $j,
                    ]);
                }
            }
        });

        return redirect()->route('inspections.beheer')->with('success', 'Inspectielijst aangemaakt.');
    }

    public function show(InspectionList $inspectionList)
    {
        $inspectionList->load('categories.checks');
        // return view('inspections.show', compact('inspectionList'));
    }

    public function beheer(Request $request)
    {
        $status = $request->string('status', 'all')->toString();
        $q = $request->string('q')->toString();
        $sort = $request->string('sort', 'created_desc')->toString();
        $perPage = (int) $request->integer('per_page', 12);

        $counts = [
            'all' => InspectionList::count(),
            'with_categories' => InspectionList::has('categories')->count(),
            'without_categories' => InspectionList::doesntHave('categories')->count(),
        ];

        $query = InspectionList::query()->withCount(['categories', 'checks']);

        if ($status === 'with_categories') {
            $query->has('categories');
        } elseif ($status === 'without_categories') {
            $query->doesntHave('categories');
        }

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        switch ($sort) {
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'categories_desc':
                $query->orderBy('categories_count', 'desc');
                break;
            case 'categories_asc':
                $query->orderBy('categories_count', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $inspectionLists = $query->paginate($perPage)->appends($request->query());

        return view('reports.inspectieBeheer', compact(
            'inspectionLists',
            'counts',
            'status',
            'q',
            'sort',
            'perPage'
        ));
    }

    public function edit(InspectionList $inspectionList)
    {
        $inspectionList->load('categories.checks');

        return view('inspections.create', [
            'inspectionList' => $inspectionList,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, InspectionList $inspectionList)
    {
        $data = $this->validatePayload($request);

        DB::transaction(function () use ($inspectionList, $data) {
            $inspectionList->update([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
            ]);

            $inspectionList->categories()->delete();

            foreach ($data['categories'] as $i => $cat) {
                $category = InspectionCategory::create([
                    'inspection_list_id' => $inspectionList->id,
                    'name' => $cat['name'],
                    'sort' => $cat['sort'] ?? $i,
                ]);

                foreach ($cat['checks'] as $j => $chk) {
                    InspectionCheck::create([
                        'inspection_category_id' => $category->id,
                        'label' => $chk['label'],
                        'code' => $chk['code'] ?? null,
                        'required' => isset($chk['required']) ? (bool) $chk['required'] : true,
                        'severity' => $chk['severity'] ?? 'info',
                        'sort' => $chk['sort'] ?? $j,
                    ]);
                }
            }
        });

        return redirect()->route('inspections.beheer')->with('success', 'Inspectielijst bijgewerkt.');
    }

    public function destroy(InspectionList $inspectionList)
    {
        $inspectionList->delete();

        return redirect()->route('inspections.beheer')->with('success', 'Inspectielijst verwijderd.');
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*.name' => ['required', 'string', 'max:255'],
            'categories.*.sort' => ['nullable', 'integer', 'min:0'],
            'categories.*.checks' => ['required', 'array', 'min:1'],
            'categories.*.checks.*.label' => ['required', 'string', 'max:1000'],
            'categories.*.checks.*.code' => ['nullable', 'string', 'max:255'],
            'categories.*.checks.*.required' => ['sometimes', 'boolean'],
            'categories.*.checks.*.severity' => ['nullable', Rule::in(['info', 'low', 'medium', 'high'])],
            'categories.*.checks.*.sort' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
