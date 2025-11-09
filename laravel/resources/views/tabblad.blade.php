<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meetrapport MRP2920</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/window.js'])
    <script src="https://kit.fontawesome.com/21e98e6012.js" crossorigin="anonymous"></script>
</head>

<body class="bg-white text-gray-800">
    <!-- Header -->
    @include('header')

    <!-- Tabs -->
    @include('tabNav')

    <form id="report-progress-form" method="POST" action="{{ route('reports.progress', $report) }}"
        enctype="multipart/form-data" class="p-4 space-y-6">
        @csrf

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->has('progress'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700">
                {{ $errors->first('progress') }}
            </div>
        @endif

        <div id="tab1-content" class="tab-content hidden">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-2xl font-semibold text-slate-800">Gecontroleerd</h2>
                    <p class="text-sm text-slate-500">Sleep checks hierheen of gebruik de V-knop.</p>
                </div>
                <div class="checklist-dropzone min-h-[240px] rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/60 p-4 transition"
                    data-dropzone="gecontroleerd">
                    <p class="text-sm text-slate-400" data-empty-state>Er zijn nog geen controles afgerond.</p>
                </div>
            </div>
        </div>

        <div id="tab2-content" class="tab-content relative z-1 space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="grid gap-6 text-sm md:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Schip</p>
                        <p class="text-lg font-semibold text-slate-900">{{ $report->schip_naam ?? 'Onbekend' }}</p>
                        <p class="text-xs text-slate-500">Nummer: {{ $report->schip_nummer ?? 'n.v.t.' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Monteur</p>
                        <p class="text-lg font-semibold text-slate-900">{{ $report->monteur ?? 'Onbekend' }}</p>
                        <p class="text-xs text-slate-500">{{ $report->created_at->format('d-m-Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Status</p>
                        <p class="text-lg font-semibold text-slate-900">{{ ucfirst($report->status ?? 'concept') }}</p>
                        <p class="text-xs text-slate-500">Bouwjaar: {{ $report->schip_bouwjaar ?? 'onbekend' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Omschrijving</p>
                        <p class="max-h-24 overflow-hidden text-sm text-slate-700">
                            {{ $report->description ?: 'Geen beschrijving toegevoegd.' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 border-b border-slate-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-slate-800">Opdrachten</h2>
                        <p class="text-sm text-slate-500">
                            Gebruik de V- of X-knop of sleep een item naar een tabblad om de status bij te werken.
                        </p>
                    </div>
                    @if ($report->inspectionList)
                        <a href="{{ route('inspections.show', $report->inspectionList) }}"
                            class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-sky-400 hover:text-sky-600">
                            Volledige inspectie
                            <i class="fa-solid fa-up-right-from-square text-xs"></i>
                        </a>
                    @endif
                </div>

                @if ($report->inspectionList)
                    <div class="mt-4 checklist-dropzone min-h-[320px] rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/60 p-4 transition"
                        data-dropzone="pending">
                        <p class="text-sm text-slate-400" data-empty-state>Alle checks zijn verwerkt.</p>
                        @include('reportCard', ['report' => $report, 'checkItems' => $checkItems ?? collect()])
                    </div>
                @else
                    <div class="mt-4 rounded-2xl border border-yellow-200 bg-yellow-50 p-4 text-yellow-900">
                        Geen inspectielijst gekoppeld aan deze rapportage.
                    </div>
                @endif
            </div>
        </div>

        <div id="tab3-content" class="tab-content hidden">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-2xl font-semibold text-slate-800">Bijzonderheden</h2>
                    <p class="text-sm text-slate-500">Sleep checks hierheen of gebruik de X-knop.</p>
                </div>
                <div class="checklist-dropzone min-h-[240px] rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/60 p-4 transition"
                    data-dropzone="bijzonderheden">
                    <p class="text-sm text-slate-400" data-empty-state>Geen bijzonderheden geregistreerd.</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 text-sm text-slate-600 sm:flex-row sm:items-center sm:justify-between">
            <p data-open-warning class="text-slate-500">
                Sleep alle controles naar Gecontroleerd of Bijzonderheden om op te kunnen slaan.
            </p>
            <button type="submit" id="save-report-btn"
                class="rounded-full bg-sky-600 px-6 py-2 text-sm font-semibold text-white transition disabled:cursor-not-allowed disabled:opacity-50"
                disabled>
                Rapportage opslaan
            </button>
        </div>
    </form>

    <!-- Sidebar -->
    @include('sidebar')

</body>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("translate-x-full");
        sidebar.classList.toggle("translate-x-0");
    }

    function switchTab(tabIndex) {
        // verberg alle tab contents
        document
            .querySelectorAll(".tab-content")
            .forEach((el) => el.classList.add("hidden"));

        // reset all tab buttons
        document.querySelectorAll(".tab-btn").forEach((el) => {
            el.classList.remove("bg-sky-500", "text-white");
            el.classList.add("bg-gray-200", "text-gray-800");
        });

        // laat de geselecteerde tab zien
        document
            .getElementById(`tab${tabIndex}-content`)
            .classList.remove("hidden");
        const activeBtn = document.getElementById(`tab${tabIndex}-btn`);
        activeBtn.classList.add("bg-sky-500", "text-white");
        activeBtn.classList.remove("bg-gray-200", "text-gray-800");
    }

    // standaard tab
    switchTab(2);
    initChecklistBoard();

    function initChecklistBoard() {
        const form = document.getElementById("report-progress-form");
        if (!form) {
            return;
        }

        const dropzones = form.querySelectorAll('[data-dropzone]');
        if (!dropzones.length) {
            return;
        }

        const saveBtn = document.getElementById("save-report-btn");
        const warning = document.querySelector('[data-open-warning]');
        const zoneMap = {};

        dropzones.forEach((zone) => {
            const key = zone.dataset.dropzone;
            zoneMap[key] = zone;
            zone.addEventListener('dragover', (event) => {
                event.preventDefault();
                zone.classList.add('border-sky-300', 'bg-white');
            });
            zone.addEventListener('dragleave', (event) => {
                if (event.currentTarget.contains(event.relatedTarget)) {
                    return;
                }
                zone.classList.remove('border-sky-300', 'bg-white');
            });
            zone.addEventListener('drop', (event) => {
                event.preventDefault();
                zone.classList.remove('border-sky-300', 'bg-white');
                const itemId = event.dataTransfer.getData('text/plain');
                const item = form.querySelector(`[data-check-item="${itemId}"]`);
                if (item) {
                    moveItem(item, key);
                }
            });
        });

        const items = form.querySelectorAll('[data-check-item]');
        items.forEach((item) => {
            item.setAttribute('draggable', 'true');
            item.addEventListener('dragstart', (event) => {
                event.dataTransfer.setData('text/plain', item.dataset.checkItem);
                event.dataTransfer.effectAllowed = 'move';
                item.classList.add('opacity-60');
            });
            item.addEventListener('dragend', () => {
                item.classList.remove('opacity-60');
            });

            item.querySelectorAll('[data-move-to]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const target = btn.dataset.moveTo;
                    moveItem(item, target);
                    zoneMap[target]?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            });

            const initialStatus = item.dataset.status || 'pending';
            moveItem(item, initialStatus);
        });

        updateEmptyStates();

        function moveItem(item, targetKey) {
            const zone = zoneMap[targetKey] ?? zoneMap.pending;
            if (!zone) {
                return;
            }

            zone.appendChild(item);
            item.dataset.status = targetKey;

            const statusInput = item.querySelector('[data-status-field]');
            if (statusInput) {
                statusInput.value = targetKey;
            }

            toggleDetails(item);
            updateEmptyStates();
        }

        function toggleDetails(item) {
            const wrapper = item.querySelector('[data-note-wrapper]');
            const noteField = item.querySelector('[data-note-field]');
            const photoField = item.querySelector('[data-photo-field]');
            const isSpecial = item.dataset.status === 'bijzonderheden';

            if (wrapper) {
                wrapper.classList.toggle('hidden', !isSpecial);
            }
            if (noteField) {
                noteField.disabled = !isSpecial;
            }
            if (photoField) {
                photoField.disabled = !isSpecial;
            }
        }

        function updateEmptyStates() {
            dropzones.forEach((zone) => {
                const isEmpty = !zone.querySelector('[data-check-item]');
                const helper = zone.querySelector('[data-empty-state]');
                if (helper) {
                    helper.classList.toggle('hidden', !isEmpty);
                }
            });

            const pendingHasItems = zoneMap.pending && zoneMap.pending.querySelector('[data-check-item]');
            if (saveBtn) {
                saveBtn.disabled = !!pendingHasItems;
                saveBtn.classList.toggle('opacity-50', !!pendingHasItems);
            }
            if (warning) {
                warning.classList.toggle('hidden', !pendingHasItems);
            }
        }
    }
</script>

</html>
