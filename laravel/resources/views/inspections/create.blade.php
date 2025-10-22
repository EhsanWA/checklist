{{-- resources/views/inspections/create.blade.php --}}
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe Inspectielijst</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    {{-- Alpine.js (CDN) --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 text-gray-900 min-h-screen">

    {{-- Optioneel: jouw bestaande header --}}
    @includeIf('header')

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="inspectionBuilder()">
        <a href="{{ route('reports.beheer') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 mb-4">
            <span aria-hidden="true">&larr;</span>
            Terug naar beheer
        </a>
        <h1 class="text-2xl font-semibold mb-6">Nieuwe Inspectielijst</h1>

        {{-- direct boven <form> --}}
        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 text-red-700 px-4 py-3">
                <p class="font-semibold">Er ging iets mis:</p>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 text-green-800 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif


        <form method="POST" action="{{ route('inspections.store') }}">
            @csrf

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Titel <span class="text-red-500">*</span>
                    </label>
                    <input name="title" x-model="form.title" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-200 px-4 py-2"
                        placeholder="Bijv. Meetrapport MRP2920">
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Beschrijving</label>
                    <textarea name="description" x-model="form.description" rows="3"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-200 px-4 py-2"
                        placeholder="Optioneel: context of instructies"></textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Categorieën --}}
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold">Categorieën</h2>
                    <button type="button" @click="addCategory()"
                        class="px-3 py-2 rounded-xl bg-sky-600 text-white hover:bg-sky-700">
                        + Categorie
                    </button>
                </div>

                <template x-for="(cat, ci) in form.categories" :key="cat.key">
                    <section class="rounded-2xl border border-gray-200 bg-white shadow-sm p-4 space-y-4">
                        <div class="flex gap-3">
                            <input type="hidden" :name="`categories[${ci}][sort]`" :value="ci">
                            <input :name="`categories[${ci}][name]`" x-model="cat.name" required
                                class="flex-1 rounded-lg border-gray-300 px-3 py-2"
                                placeholder="Bijv. 1211 - Hoofdmachine installatie">
                            <button type="button" @click="removeCategory(ci)"
                                class="px-3 py-2 rounded-lg bg-red-50 text-red-700 hover:bg-red-100">
                                Verwijderen
                            </button>
                        </div>

                        {{-- Checks binnen categorie --}}
                        <div class="flex items-center justify-between">
                            <h3 class="font-medium">Checks</h3>
                            <button type="button" @click="addCheck(ci)"
                                class="px-3 py-1.5 rounded-lg bg-gray-900 text-white hover:bg-black">+ Check</button>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(chk, ji) in cat.checks" :key="chk.key">
                                <div class="grid grid-cols-12 gap-3 items-start">
                                    <input type="hidden" :name="`categories[${ci}][checks][${ji}][sort]`"
                                        :value="ji">

                                    <div class="col-span-7">
                                        <input :name="`categories[${ci}][checks][${ji}][label]`" x-model="chk.label"
                                            required class="w-full rounded-lg border-gray-300 px-3 py-2"
                                            placeholder="Bijv. Lees de motor uit met de Vodiatool.">
                                    </div>

                                    <div class="col-span-3">
                                        <input :name="`categories[${ci}][checks][${ji}][code]`" x-model="chk.code"
                                            class="w-full rounded-lg border-gray-300 px-3 py-2"
                                            placeholder="Optioneel code (M0001)">
                                    </div>

                                    <div class="col-span-1 flex items-center gap-2">
                                        {{-- stuur altijd een waarde mee --}}
                                        <input type="hidden" :name="`categories[${ci}][checks][${ji}][required]`"
                                            value="0">
                                        <input type="checkbox" :name="`categories[${ci}][checks][${ji}][required]`"
                                            value="1" x-model="chk.required" class="rounded">
                                        <span class="text-sm">Verplicht</span>
                                    </div>

                                    <div class="col-span-1">
                                        <select :name="`categories[${ci}][checks][${ji}][severity]`"
                                            x-model="chk.severity"
                                            class="w-full rounded-lg border-gray-300 px-2 py-2 text-sm">
                                            <option value="info">Info</option>
                                            <option value="low">Low</option>
                                            <option value="medium">Medium</option>
                                            <option value="high">High</option>
                                        </select>
                                    </div>

                                    <div class="col-span-12">
                                        <button type="button" @click="removeCheck(ci, ji)"
                                            class="text-red-700 text-sm hover:underline">Check verwijderen</button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </section>
                </template>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-sky-600 text-white hover:bg-sky-700">
                        Inspectielijst opslaan
                    </button>
                </div>
            </div>
        </form>
    </main>

    {{-- App JS (als je Alpine/Turbo/Vite gebruikt) --}}
    @vite('resources/js/app.js')

    <script>
        function inspectionBuilder() {
            // Fallback voor oude browsers zonder crypto.randomUUID()
            const uuid = () => (crypto?.randomUUID?.() ?? Math.random().toString(36).slice(2) + Date.now());

            return {
                form: {
                    title: '',
                    description: '',
                    categories: [{
                        key: uuid(),
                        name: '',
                        checks: [{
                            key: uuid(),
                            label: '',
                            code: '',
                            required: true,
                            severity: 'info'
                        }]
                    }]
                },
                addCategory() {
                    this.form.categories.push({
                        key: uuid(),
                        name: '',
                        checks: [{
                            key: uuid(),
                            label: '',
                            code: '',
                            required: true,
                            severity: 'info'
                        }]
                    });
                },
                removeCategory(index) {
                    this.form.categories.splice(index, 1);
                },
                addCheck(ci) {
                    this.form.categories[ci].checks.push({
                        key: uuid(),
                        label: '',
                        code: '',
                        required: true,
                        severity: 'info'
                    });
                },
                removeCheck(ci, ji) {
                    this.form.categories[ci].checks.splice(ji, 1);
                }
            }
        }
    </script>
</body>

</html>
