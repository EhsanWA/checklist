<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meetrapport {{ $report->title ?? 'MRP2920' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/21e98e6012.js" crossorigin="anonymous"></script>
</head>

<body class="min-h-[100dvh] bg-gray-50 text-gray-800">
    @php use Illuminate\Support\Facades\Storage; @endphp

    {{-- Header --}}
    @include('header')

    {{-- Tabs --}}
    <div class="sticky top-0 z-30 bg-white/90 backdrop-blur border-b">
        @include('tabNav')
    </div>

    <form id="report-progress-form" method="POST" action="{{ route('reports.progress', $report) }}"
        enctype="multipart/form-data" class="max-w-5xl mx-auto p-4 space-y-6 pb-32">
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

        {{-- Tab 1: Gecontroleerd --}}
        <div id="tab1-content" class="tab-content hidden">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-2xl font-semibold text-slate-800">Gecontroleerd</h2>
                </div>
                <div class="checklist-dropzone min-h-[240px] rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/60 p-4 transition"
                    data-dropzone="gecontroleerd">
                    <p class="text-sm text-slate-400" data-empty-state>Er zijn nog geen controles afgerond.</p>
                </div>
            </div>
        </div>

        {{-- Tab 2: Opdrachten --}}
        <div id="tab2-content" class="tab-content relative z-1 space-y-6">
            {{-- Rapportinformatie --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm max-w-none">
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

            {{-- Opdrachten / Checklist --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm max-w-none">
                <div
                    class="flex flex-col gap-3 border-b border-slate-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-slate-800">Opdrachten</h2>
                        <p class="text-sm text-slate-500">
                            Tik om de status te wisselen.
                        </p>
                    </div>

                    @if ($report->inspectionList)
                        <div class="flex items-center gap-2" data-pdf-menu>
                            <a href="{{ route('inspections.show', $report->inspectionList) }}" target="_blank"
                                class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-sky-400 hover:text-sky-600">
                                Volledige inspectie
                                <i class="fa-solid fa-up-right-from-square text-xs"></i>
                            </a>

                            <div class="relative">
                                <button type="button"
                                    class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:border-sky-400 hover:text-sky-600"
                                    data-pdf-toggle>
                                    Verzonden PDF's
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </button>
                                <div class="pdf-dropdown hidden absolute right-0 top-full mt-2 w-64 rounded-2xl border border-slate-200 bg-white shadow-xl z-10"
                                    data-pdf-dropdown>
                                    <div class="border-b border-slate-100 px-4 py-2">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                            Verzonden PDF's</p>
                                    </div>
                                    @if (($submittedPdfs ?? collect())->isEmpty())
                                        <p class="px-4 py-3 text-sm text-slate-500">Nog geen verzonden bestanden.</p>
                                    @else
                                        <ul class="max-h-60 overflow-y-auto">
                                            @foreach ($submittedPdfs as $pdf)
                                                <li>
                                                    <a href="{{ Storage::url($pdf) }}" target="_blank"
                                                        class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                                        <i class="fa-solid fa-file-pdf text-sky-500"></i>
                                                        <span class="truncate">{{ basename($pdf) }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Filters en zoek --}}
                <div class="mt-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div class="flex flex-wrap gap-2" id="status-filters">
                        @php
                            $filters = [
                                ['key' => 'all', 'label' => 'Alle'],
                                ['key' => 'pending', 'label' => 'Open'],
                                ['key' => 'gecontroleerd', 'label' => 'Gereed'],
                                ['key' => 'bijzonderheden', 'label' => 'Bijzonder'],
                            ];
                        @endphp
                        @foreach ($filters as $f)
                            <button type="button"
                                class="filter-chip rounded-full border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 focus:outline-none focus:ring focus:ring-sky-200"
                                data-filter="{{ $f['key'] }}">
                                {{ $f['label'] }}
                            </button>
                        @endforeach
                    </div>

                    <div class="relative w-full md:w-64">
                        <input type="search" id="checks-search" placeholder="Zoek check of code..."
                            class="w-full rounded-full border border-slate-200 px-4 py-2.5 pr-9 text-sm text-slate-800 focus:border-sky-400 focus:ring focus:ring-sky-100" />
                        <i
                            class="fa-solid fa-magnifying-glass absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <p class="mt-2 text-sm text-slate-500 hidden sm:text-right" data-search-empty-state>
                    Geen checks gevonden voor deze zoekopdracht.
                </p>

                {{-- Checklist --}}
                @if ($report->inspectionList)
                    <div id="checks-container"
                        class="mt-4 checklist-dropzone min-h-[320px] rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/60 p-4 space-y-4 transition"
                        data-dropzone="pending">
                        <p class="text-sm text-slate-400" data-empty-state>Alle checks zijn verwerkt.</p>
                        @include('reportCard', [
                            'report' => $report,
                            'checkItems' => $checkItems ?? collect(),
                        ])
                    </div>
                @else
                    <div class="mt-4 rounded-2xl border border-yellow-200 bg-yellow-50 p-4 text-yellow-900">
                        Geen inspectielijst gekoppeld aan deze rapportage.
                    </div>
                @endif
            </div>
        </div>

        {{-- Tab 3: Bijzonderheden --}}
        <div id="tab3-content" class="tab-content hidden">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-2xl font-semibold text-slate-800">Bijzonderheden</h2>
                </div>
                <div class="checklist-dropzone min-h-[240px] rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/60 p-4 transition"
                    data-dropzone="bijzonderheden">
                    <p class="text-sm text-slate-400" data-empty-state>Geen bijzonderheden geregistreerd.</p>
                </div>
            </div>
        </div>

        {{-- Modal: afronden + handtekening (centered) --}}
        <div id="action-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/60 p-4">
            <div class="w-full max-w-2xl rounded-3xl bg-white p-5 shadow-2xl">
                <div class="flex items-start justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Rapportage afronden</h2>
                        <p class="text-sm text-slate-500">Teken en verstuur de rapportage.</p>
                        <p data-open-warning class="mt-2 text-sm font-medium text-amber-600">
                            Tik alle controles aan om ze te markeren als Gecontroleerd of Bijzonderheden voordat je verstuurt.
                        </p>
                    </div>
                    <button type="button" id="close-action-modal"
                        class="rounded-full border border-slate-200 px-3 py-1 text-sm font-semibold text-slate-500 hover:bg-slate-50">
                        Sluit
                    </button>
                </div>

                <div class="mt-4 space-y-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4">
                        <p class="text-sm font-semibold text-slate-800">Handtekening</p>
                        <p class="text-xs text-slate-500">Teken om de rapportage officieel te maken.</p>
                        <canvas id="signature-pad"
                            class="mt-3 w-full rounded-lg border border-slate-200 bg-white shadow-inner"
                            style="touch-action: none;" width="900" height="220"></canvas>
                        <input type="hidden" name="signature" id="signature-input">
                        <div class="mt-2 flex flex-wrap items-center gap-2">
                            <button type="button" id="signature-clear"
                                class="rounded-full border border-slate-300 px-4 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                                Wis handtekening
                            </button>
                            <span data-signature-warning class="hidden text-xs font-semibold text-rose-600">
                                Plaats eerst een handtekening om te versturen.
                            </span>
                        </div>
                    </div>

                    <button type="submit" id="send-report-btn" formaction="{{ route('reports.submit', $report) }}"
                        class="w-full rounded-2xl bg-emerald-600 px-6 py-3 text-base font-semibold text-white transition disabled:opacity-50"
                        disabled>
                        Verstuur rapportage
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Sticky actiebar onderin - OUTSIDE the form -->
    <div id="sticky-actions"
        class="fixed left-0 right-0 bottom-0 z-50 border-t border-slate-200 bg-white/95 backdrop-blur px-4 py-3 pb-[calc(env(safe-area-inset-bottom)+1rem)]">
        <div class="mx-auto flex max-w-5xl items-center gap-3">
            <div class="hidden sm:flex items-center text-sm text-slate-600" id="progress-counter">
                <i class="fa-solid fa-list-check mr-2"></i>
                <span><b data-count-done>0</b>/<b data-count-total>0</b> gereed</span>
            </div>

            <button type="button" id="open-action-modal"
                class="ml-auto hidden rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring focus:ring-slate-200">
                Handtekening & verzenden
            </button>

            <button type="submit" form="report-progress-form" id="save-report-btn"
                class="rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white disabled:opacity-50"
                disabled>
                Opslaan
            </button>
        </div>
    </div>

    <script>
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("translate-x-full");
    sidebar.classList.toggle("translate-x-0");
}

function switchTab(tabIndex) {
    document.querySelectorAll(".tab-content").forEach(el => el.classList.add("hidden"));
    document.querySelectorAll(".tab-btn").forEach(el => {
        el.classList.remove("bg-sky-500", "text-white");
        el.classList.add("bg-gray-200", "text-gray-800");
    });

    document.getElementById(`tab${tabIndex}-content`).classList.remove("hidden");
    const activeBtn = document.getElementById(`tab${tabIndex}-btn`);
    if (activeBtn) {
        activeBtn.classList.add("bg-sky-500", "text-white");
        activeBtn.classList.remove("bg-gray-200", "text-gray-800");
    }
}

// Default tab
switchTab(2);

// Initialize Checklist Board
initChecklistBoard();

function initChecklistBoard() {
    const form = document.getElementById("report-progress-form");
    if (!form) return;

    const dropzones = form.querySelectorAll("[data-dropzone]");
    const zoneMap = {};
    let pendingHasItems = true;

    // UI elements
    const saveBtn = document.getElementById("save-report-btn");
    const sendBtn = document.getElementById("send-report-btn");
    const openActionBtn = document.getElementById("open-action-modal");
    const closeActionBtn = document.getElementById("close-action-modal");
    const actionModal = document.getElementById("action-modal");
    const warning = document.querySelector("[data-open-warning]");
    const signatureCanvas = document.getElementById("signature-pad");
    const signatureInput = document.getElementById("signature-input");
    const signatureWarning = document.querySelector("[data-signature-warning]");
    const clearSignatureBtn = document.getElementById("signature-clear");
    const countDone = document.querySelector("[data-count-done]");
    const countTotal = document.querySelector("[data-count-total]");
    const pdfToggleBtn = document.querySelector("[data-pdf-toggle]");
    const pdfDropdown = document.querySelector("[data-pdf-dropdown]");
    const pdfMenu = document.querySelector("[data-pdf-menu]");
    const searchInput = document.getElementById("checks-search");
    const searchEmptyState = document.querySelector("[data-search-empty-state]");
    let signatureDirty = false;
    let currentSearchTerm = "";

    // Initialize dropzones (but no drag events anymore)
    dropzones.forEach(zone => {
        zoneMap[zone.dataset.dropzone] = zone;
    });

    const items = form.querySelectorAll("[data-check-item]");
    if (countTotal) countTotal.textContent = items.length;

    // Initialize items
    items.forEach(item => {
        // Ensure drag is disabled
        item.removeAttribute("draggable");
        item.draggable = false;

        // Status buttons
        item.querySelectorAll("[data-status-option]").forEach(btn => {
            btn.addEventListener("click", () => {
                const target = btn.dataset.statusOption;
                moveItem(item, target);
                zoneMap[target]?.scrollIntoView({ behavior: "smooth", block: "center" });
            });
        });

        // Cycle button (optional)
        const cycleBtn = item.querySelector("[data-cycle-status]");
        if (cycleBtn) {
            cycleBtn.addEventListener("click", () => {
                const order = ["pending", "gecontroleerd", "bijzonderheden"];
                const cur = item.dataset.status || "pending";
                const next = order[(order.indexOf(cur) + 1) % order.length];
                moveItem(item, next);
            });
        }

        // Move to initial zone
        moveItem(item, item.dataset.status || "pending");
    });

    initSearch();
    updateCounters();
    updateActionButtons();

    // SEARCH
    function initSearch() {
        if (!items.length || !searchInput) return;

        searchInput.addEventListener("input", () => {
            currentSearchTerm = searchInput.value.trim().toLowerCase();
            applySearchFilter();
        });

        applySearchFilter();
    }

    function applySearchFilter() {
        let visibleCount = 0;

        items.forEach(item => {
            const haystack = (item.dataset.searchIndex || item.textContent || "").toLowerCase();
            const match = !currentSearchTerm || haystack.includes(currentSearchTerm);
            item.toggleAttribute("hidden", !match);
            if (match) visibleCount++;
        });

        const showEmpty = currentSearchTerm.length > 0 && visibleCount === 0;
        searchEmptyState?.classList.toggle("hidden", !showEmpty);

        updateEmptyStates();
    }

    // MOVE ITEM
    function moveItem(item, targetKey) {
        const zone = zoneMap[targetKey] ?? zoneMap.pending;
        if (!zone) return;

        zone.appendChild(item);
        item.dataset.status = targetKey;

        const statusInput = item.querySelector("[data-status-field]");
        if (statusInput) statusInput.value = targetKey;

        toggleDetails(item);
        updateStatusButtons(item);
        updateEmptyStates();
        updateCounters();
    }

    function toggleDetails(item) {
        const wrap = item.querySelector("[data-note-wrapper]");
        const note = item.querySelector("[data-note-field]");
        const photo = item.querySelector("[data-photo-field]");
        const show = item.dataset.status === "bijzonderheden";

        if (wrap) wrap.classList.toggle("hidden", !show);
        if (note) note.disabled = !show;
        if (photo) photo.disabled = !show;
    }

    function updateStatusButtons(item) {
        const cur = item.dataset.status;
        item.querySelectorAll("[data-status-option]").forEach(btn => {
            const active = btn.dataset.statusOption === cur;
            btn.classList.toggle("border-sky-500", active);
            btn.classList.toggle("bg-sky-50", active);
            btn.classList.toggle("text-sky-900", active);
            btn.classList.toggle("shadow", active);
        });
    }

    function updateEmptyStates() {
        dropzones.forEach(zone => {
            const hasVisibleItems = zone.querySelector("[data-check-item]:not([hidden])");
            const isEmpty = !hasVisibleItems;
            const helper = zone.querySelector("[data-empty-state]");
            if (helper) helper.classList.toggle("hidden", !isEmpty);
            if (zone.dataset.dropzone === "pending") pendingHasItems = !isEmpty;
        });
        updateActionButtons();
    }

    function updateCounters() {
        const done = form.querySelectorAll(
            '[data-check-item][data-status="gecontroleerd"], [data-check-item][data-status="bijzonderheden"]'
        ).length;
        if (countDone) countDone.textContent = done;
    }

    function updateActionButtons() {
        // Opslaan moet altijd kunnen; verzenden alleen zonder openstaande items en met handtekening.
        const disabledSave = false;
        const disabledSend = pendingHasItems || !signatureDirty;

        if (saveBtn) saveBtn.disabled = disabledSave;
        if (sendBtn) sendBtn.disabled = disabledSend;

        warning?.classList.toggle("hidden", !pendingHasItems);
        openActionBtn?.classList.toggle("hidden", pendingHasItems);
    }

    // SIGNATURE PAD
    initSignaturePad();

    function initSignaturePad() {
        if (!signatureCanvas || !signatureInput) return;

        const ctx = signatureCanvas.getContext("2d");
        ctx.lineWidth = 2;
        ctx.lineCap = "round";
        ctx.strokeStyle = "#0f172a";
        ctx.fillStyle = "#fff";
        ctx.fillRect(0, 0, signatureCanvas.width, signatureCanvas.height);

        let drawing = false;

        const get = e => {
            const r = signatureCanvas.getBoundingClientRect();
            const p = e.touches ? e.touches[0] : e;
            return {
                x: (p.clientX - r.left) * (signatureCanvas.width / r.width),
                y: (p.clientY - r.top) * (signatureCanvas.height / r.height)
            };
        };

        const start = e => {
            drawing = true;
            const { x, y } = get(e);
            ctx.beginPath();
            ctx.moveTo(x, y);
            e.preventDefault();
        };

        const draw = e => {
            if (!drawing) return;
            const { x, y } = get(e);
            ctx.lineTo(x, y);
            ctx.stroke();
            signatureDirty = true;
            signatureWarning?.classList.add("hidden");
            updateActionButtons();
            e.preventDefault();
        };

        const stop = () => {
            drawing = false;
            ctx.beginPath();
        };

        signatureCanvas.addEventListener("mousedown", start);
        signatureCanvas.addEventListener("mousemove", draw);
        window.addEventListener("mouseup", stop);
        signatureCanvas.addEventListener("touchstart", start, { passive: false });
        signatureCanvas.addEventListener("touchmove", draw, { passive: false });
        window.addEventListener("touchend", stop);

        clearSignatureBtn?.addEventListener("click", () => {
            ctx.fillRect(0, 0, signatureCanvas.width, signatureCanvas.height);
            signatureDirty = false;
            updateActionButtons();
        });

        sendBtn?.addEventListener("click", e => {
            if (sendBtn.disabled) {
                e.preventDefault();
                if (!signatureDirty) signatureWarning?.classList.remove("hidden");
                return;
            }
            signatureInput.value = signatureCanvas.toDataURL("image/png");
        });
    }

    // Modal open/sluit
            if (openActionBtn && actionModal) {
                openActionBtn.addEventListener('click', () => {
                    actionModal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                });
                closeActionBtn?.addEventListener('click', () => {
                    actionModal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                });
                actionModal.addEventListener('click', e => {
                    if (e.target === actionModal) {
                        actionModal.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                    }
                });
            }

    // PDF MENU
    initPdfDropdown();

    function initPdfDropdown() {
        if (!pdfToggleBtn || !pdfDropdown || !pdfMenu) return;

        pdfToggleBtn.addEventListener("click", event => {
            event.stopPropagation();
            pdfDropdown.classList.toggle("hidden");
        });

        document.addEventListener("click", event => {
            if (!pdfMenu.contains(event.target)) {
                pdfDropdown.classList.add("hidden");
            }
        });
    }
}
</script>

</body>

</html>
