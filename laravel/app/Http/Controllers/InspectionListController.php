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
        $data = $request->validate([
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

        return redirect()->route('inspections.create')->with('success', 'Inspectielijst aangemaakt.');
    }

    public function show(InspectionList $inspectionList)
    {
        $inspectionList->load('categories.checks');
        return view('inspections.show', compact('inspectionList'));
    }
}
