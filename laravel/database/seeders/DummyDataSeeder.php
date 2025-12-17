<?php

namespace Database\Seeders;

use App\Models\InspectionCategory;
use App\Models\InspectionCheck;
use App\Models\InspectionList;
use App\Models\Report;
use App\Models\ReportCheckItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    /**
     * Seed dummy data for local development.
     */
    public function run(): void
    {
        DB::transaction(function () {
            User::firstOrCreate(
                ['email' => 'test@example.com'],
                ['name' => 'Test User', 'password' => bcrypt('password')]
            );

            $listA = InspectionList::firstOrCreate(
                ['title' => 'Meetrapport MRP2920'],
                ['description' => 'Voorbeeld inspectielijst voor motorruimte.']
            );

            $listB = InspectionList::firstOrCreate(
                ['title' => 'Jaarlijkse veiligheid controle'],
                ['description' => 'Standaard jaarlijkse controle op veiligheid.']
            );

            $lists = [
                [
                    'list' => $listA,
                    'categories' => [
                        [
                            'name' => '1211 - Hoofdmachine installatie',
                            'sort' => 1,
                            'checks' => [
                                ['label' => 'Lees de motor uit met de Vodia tool.', 'code' => 'Op.Rgl.M0001', 'severity' => 'medium', 'sort' => 1],
                                ['label' => 'Controleer oliedruk en koelvloeistof.', 'code' => 'Op.Rgl.M0005', 'severity' => 'high', 'sort' => 2],
                                ['label' => 'Controleer lekkages rond de koppeling.', 'code' => 'Op.Rgl.M0010', 'severity' => 'low', 'sort' => 3],
                            ],
                        ],
                        [
                            'name' => '1310 - Brandstof systeem',
                            'sort' => 2,
                            'checks' => [
                                ['label' => 'Inspecteer brandstofleidingen op slijtage.', 'code' => 'Fuel.F001', 'severity' => 'medium', 'sort' => 1],
                                ['label' => 'Controleer brandstoffilters.', 'code' => 'Fuel.F002', 'severity' => 'low', 'sort' => 2],
                            ],
                        ],
                    ],
                ],
                [
                    'list' => $listB,
                    'categories' => [
                        [
                            'name' => '2001 - Veiligheidsmiddelen',
                            'sort' => 1,
                            'checks' => [
                                ['label' => 'Controleer brandblussers op geldige keuring.', 'code' => 'Safe.S001', 'severity' => 'high', 'sort' => 1],
                                ['label' => 'Controleer noodverlichting in gangpaden.', 'code' => 'Safe.S002', 'severity' => 'medium', 'sort' => 2],
                            ],
                        ],
                        [
                            'name' => '2002 - Dek en tuigage',
                            'sort' => 2,
                            'checks' => [
                                ['label' => 'Controleer de staat van de reling.', 'code' => 'Deck.D001', 'severity' => 'low', 'sort' => 1],
                                ['label' => 'Controleer bevestiging van reddingsboeien.', 'code' => 'Deck.D002', 'severity' => 'medium', 'sort' => 2],
                            ],
                        ],
                    ],
                ],
            ];

            foreach ($lists as $listData) {
                $list = $listData['list'];
                $categories = $listData['categories'];
                foreach ($categories as $categoryData) {
                    $category = InspectionCategory::firstOrCreate(
                        [
                            'inspection_list_id' => $list->id,
                            'name' => $categoryData['name'],
                        ],
                        ['sort' => $categoryData['sort']]
                    );

                    foreach ($categoryData['checks'] as $checkData) {
                        InspectionCheck::firstOrCreate(
                            [
                                'inspection_category_id' => $category->id,
                                'label' => $checkData['label'],
                            ],
                            [
                                'code' => $checkData['code'],
                                'required' => true,
                                'severity' => $checkData['severity'],
                                'sort' => $checkData['sort'],
                            ]
                        );
                    }
                }
            }

            $reportA = Report::firstOrCreate(
                ['schip_naam' => 'MS Horizon', 'inspection_list_id' => $listA->id],
                [
                    'schip_nummer' => 'MRP-2920',
                    'schip_bouwjaar' => 2012,
                    'monteur' => 'J. Vermeer',
                    'description' => 'Initiele opname en inspectie.',
                    'status' => 'submitted',
                    'submitted_at' => now()->subDays(3),
                ]
            );

            $reportB = Report::firstOrCreate(
                ['schip_naam' => 'MS Northwind', 'inspection_list_id' => $listB->id],
                [
                    'schip_nummer' => 'SV-8841',
                    'schip_bouwjaar' => 2008,
                    'monteur' => 'S. Bakker',
                    'description' => 'Jaarlijkse veiligheidsronde.',
                    'status' => 'draft',
                ]
            );

            $this->seedReportItems($reportA);
            $this->seedReportItems($reportB);
        });
    }

    private function seedReportItems(Report $report): void
    {
        $checks = InspectionCheck::query()
            ->whereHas('category', function ($query) use ($report) {
                $query->where('inspection_list_id', $report->inspection_list_id);
            })
            ->get();

        $statuses = ['pending', 'gecontroleerd', 'bijzonderheden'];

        foreach ($checks as $index => $check) {
            ReportCheckItem::firstOrCreate(
                [
                    'report_id' => $report->id,
                    'inspection_check_id' => $check->id,
                ],
                [
                    'status' => $statuses[$index % count($statuses)],
                    'notes' => $index % 3 === 2 ? 'Let op: kleine afwijking gevonden.' : null,
                    'photos' => null,
                ]
            );
        }
    }
}
