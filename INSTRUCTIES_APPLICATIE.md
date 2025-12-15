# Report Manager – Handleiding voor Ontwikkelaars

Dit document biedt richtlijnen voor toekomstige ontwikkelaars om het applicatie-codebase aan te passen en uit te breiden.

## Inhoudsopgave

1. [Projectoverzicht](#projectoverzicht)
2. [Technologie Stack](#technologie-stack)
3. [Directory Structuur](#directory-structuur)
4. [Kernfunctionaliteiten](#kernfunctionaliteiten)
5. [Sleutelbestanden & Componenten](#sleutelbestanden--componenten)
6. [Veelvoorkomende Aanpassingen](#veelvoorkomende-aanpassingen)
7. [Setup & Installatie](#setup--installatie)

---

## Projectoverzicht

**Report Manager** is een Laravel-gebaseerde webapplicatie voor het digitaal invullen en beheren van inspectierapportages. Het is geoptimaliseerd voor tablet-gebruik en voorzien van:

- 📋 **Interactieve checklist** met drie statussen: Open → Gereed → Bijzonderheden
- 📸 **Foto-upload** met client-side schaalvergroting (max 2MB, 1200×900px)
- ✍️ **Handtekeningpad** voor officiële rapportafrondingen
- 📄 **PDF-export** van voltooide rapportages
- 🔐 **Beheermodule** met PIN-authenticatie
- 🎨 **Responsive design** (Tailwind CSS v4)

---

## Technologie Stack

| Component        | Versie | Doel                                                     |
| ---------------- | ------ | -------------------------------------------------------- |
| **Laravel**      | 12     | Backend framework, routing, database                     |
| **Blade**        | -      | Server-side templating                                   |
| **Tailwind CSS** | 4      | Utility-first CSS framework                              |
| **Vite**         | -      | JavaScript/CSS bundler                                   |
| **Font Awesome** | CDN    | Icoonbibliotheek                                         |
| **DomPDF**       | 3.1    | Server-side PDF-generatie                                |
| **SQLite**       | -      | Database (standaard Laravel)                             |
| **Vanilla JS**   | -      | Interactiviteit (drag buttons, zoeken, signature canvas) |

---

## Directory Structuur

```
laravel/
├── app/
│   ├── Http/Controllers/
│   │   └── ReportController.php        # ⭐ Kernlogica (CRUD, PDF, validatie)
│   ├── Models/
│   │   ├── Report.php
│   │   ├── ReportCheckItem.php
│   │   ├── InspectionList.php
│   │   └── Check.php
│   └── Providers/
├── bootstrap/
├── config/
│   ├── app.php                          # App-configuratie
│   ├── admin.php                        # ⭐ Beheercode PIN-instelling
│   └── database.php                     # Database verbinding
├── database/
│   ├── migrations/                      # Schema wijzigingen
│   ├── factories/                       # Test data generatie
│   └── seeders/                         # Initial data
├── public/
│   ├── index.php                        # Entry point
│   └── images/                          # Statische assets
├── resources/
│   ├── css/app.css                      # Tailwind imports
│   ├── js/
│   │   ├── app.js                       # ⭐ Foto upload, foto preview
│   │   └── bootstrap.js                 # Axios config
│   └── views/
│       ├── home.blade.php               # ⭐ Landingspagina (routing beslissing)
│       ├── header.blade.php             # Sticky header + navigatie
│       ├── tabblad.blade.php            # ⭐ Checklist interface (core)
│       ├── reportCard.blade.php         # Check item component
│       ├── tabNav.blade.php             # Tab navigatie
│       └── reports/
│           ├── index.blade.php          # Rapportlijst
│           ├── create.blade.php         # Rapportage aanmaken
│           ├── edit.blade.php           # ⭐ Metadata bewerken (delete form)
│           └── beheer.blade.php         # ⭐ Admin dashboard (tabel, filters)
├── routes/
│   ├── web.php                          # ⭐ Alle routes (auth, resources, admin)
│   └── console.php
├── storage/
│   ├── app/public/                      # PDF's, foto's, handtekeningen
│   └── logs/
├── tests/
├── artisan                              # Laravel CLI
├── composer.json                        # PHP dependencies
├── package.json                         # Node dependencies
├── vite.config.js                       # Build configuratie
└── phpunit.xml                          # Test configuratie
```

---

## Kernfunctionaliteiten

### 1. **Rapportage Workflow**

```
Landingspagina (home.blade.php)
    ↓
Rapportlijst (reports/index.blade.php)
    ↓
Checklist invullen (tabblad.blade.php) → Opslaan via /reports/progress
    ↓
Afronden + Handtekening → POST /reports/submit
    ↓
PDF gegenereerd → Opgeslagen in storage/app/public/reports/
    ↓
Status = "submitted" in database
```

### 2. **Check Item Statussen**

Elk check item heeft **drie statussen**:

| Status           | Beschrijving              | Details panel zichtbaar?        |
| ---------------- | ------------------------- | ------------------------------- |
| `pending`        | Open (niet gecontroleerd) | Nee                             |
| `gecontroleerd`  | Gereed (afgevinkt)        | Nee                             |
| `bijzonderheden` | Bijzonder (notitie/foto)  | **Ja** – textarea + foto inputs |

**Hoe verplaatsen items zich?**

- User klikt op een **status pill** (Open/Gereed/Bijzonder)
- JS functie `moveItem(item, targetStatus)` wordt aangeroepen
- Item wordt naar juiste zone verplaatst
- Form input `checks[id][status]` wordt geupdate
- Bij volgende save/submit worden wijzigingen opgeslagen

### 3. **Foto-upload Flow**

```javascript
User selecteert foto (FileReader)
    ↓
2MB limiet check (alert als te groot)
    ↓
Canvas-resize: max 1200×900px, JPEG 85%
    ↓
Preview weergegeven (max-width 200px)
    ↓
Bij submit/save: foto geupload naar storage/app/public/checks/
```

### 4. **Handtekening & PDF**

- User tekent op HTML5 Canvas
- Ondertekening wordt naar Base64 PNG geconverteerd
- Bij submit: PNG opgeslagen + PDF gegenereerd vanuit Blade template
- PDF en signature beide opgeslagen in `storage/app/public/reports/`

---

## Sleutelbestanden & Componenten

### ⭐ **ReportController.php** – Kernlogica

**Belangrijkste functies:**

```php
// Haal alle rapportages op (met filters)
public function index()

// Admin dashboard (tabel, status tabs, zoeken)
public function beheer()

// Toon rapportage detail
public function show(Report $report)

// Sla voortgang op (ZONDER af te ronden)
public function saveProgress(Report $report)

// Rond af + genereer PDF + handtekening
public function submit(Report $report)

// Hulpfunctie: synchroniseer check data
private function syncCheckItems(Report $report, array $data)

// Hulpfunctie: opslag handtekening PNG
private function storeSignature(Report $report, string $signature)

// Hulpfunctie: organiseer checks voor PDF export
private function groupChecksForExport(Report $report)
```

**Aanpassing voorbeeld:** Wil je een extra veld aan rapportages toevoegen?

1. Voeg kolom toe via migration: `Schema::table('reports', fn($table) => $table->string('new_field'));`
2. Update `$report->fillable` in [Report.php](app/Models/Report.php)
3. Voeg input veld toe in [resources/views/reports/edit.blade.php](resources/views/reports/edit.blade.php)
4. Update `store()` of `update()` in ReportController

---

### ⭐ **tabblad.blade.php** – Checklist Interface

Dit is het **kernscherm** waar monteurs de inspectie uitvoeren.

**Structuur:**

```
Header (sticky)
   ↓
Tabs (Gecontroleerd / Opdrachten / Bijzonderheden)
   ↓
Form #report-progress-form
   ├─ Tab 1: Dropzone (gecontroleerd items)
   ├─ Tab 2: Rapportinfo + Checklist (pending items)
   ├─ Tab 3: Dropzone (bijzonderheden items)
   └─ Hidden Modal (handtekening + submit)
   ↓
Sticky actiebar (Opslaan / Afronden)
```

**Embedded JavaScript:**

```javascript
// Schakelt tabs
switchTab(tabIndex);

// KERNFUNCTIE: Verplaatst item naar status + update zone
moveItem(item, targetStatus);

// Toont/verbergt details panel (textarea, foto inputs)
toggleDetails(item);

// Initialiseert handtekeningpad (Canvas, touch + mouse)
initSignaturePad();

// Zoeken in checks
applySearchFilter();
```

**Aanpassing voorbeeld:** Wil je een extra status toevoegen (bijv. "Uitgesteld")?

1. Voeg status option toe in [reportCard.blade.php](resources/views/reportCard.blade.php):
   ```php
   'uitgesteld' => ['label' => 'Uitgesteld', 'hint' => 'Voor later'],
   ```
2. Update `statusOptions` array in tabblad.blade.php script
3. Zorg dat ReportController dit status herkent in `syncCheckItems()`

---

### ⭐ **home.blade.php** – Landingspagina & Routing

Dit bestand bepaalt de **toegangspunten** naar de app.

```php
// Kaart 1: Rapportage invullen (publiek)
route('reports.index')

// Kaart 2: Beheer (voorwaardelijk)
$beheerUrl = session('is_admin') === true
    ? route('reports.beheer')      // Admin dashboard
    : route('admin.login')          // PIN-login
```

**Aanpassing voorbeeld:** Wil je een extra kaart toevoegen?

1. Dupliceer kaart HTML
2. Voeg icoon (Font Awesome) toe
3. Voeg route toe in [routes/web.php](routes/web.php)
4. Zorg dat actiepagina beschikbaar is

---

### ⭐ **beheer.blade.php** – Admin Dashboard

Admin-overzicht met **tabel, filters, zoeken, sortering**.

**Structuur:**

```
Status tabs (All / Draft / Submitted / Archived)
   ↓
Filter bar (Zoeken, Status select, Sorteren)
   ↓
Tabel (Schip / Nummer / Bouwjaar / Monteur / Status / Datum)
   └─ Per rij: "Bewerken" link
```

**Aanpassing voorbeeld:** Wil je kolommen toevoegen aan de tabel?

1. Update `<table>` header (add `<th>Kolom</th>`)
2. Update `<tbody>` row (add `<td>{{ $report->veld }}</td>`)
3. Zorg dat veld bestaat in Report model
4. Optioneel: voeg sortering toe via query params

---

### ⭐ **edit.blade.php** – Rapportage Metadata + Delete

Bewerk rapportage-informatie (schip naam, monteur, status) en **verwijder rapportage**.

**Key elementen:**

```php
Form fields:
  - schip_naam (required)
  - schip_nummer
  - schip_bouwjaar
  - monteur (required)
  - description
  - status (select)
  - inspection_list_id

Sticky footer:
  - "Annuleren" link
  - "Verwijderen" form (DELETE, confirmation)
  - "Wijzigingen opslaan" submit
```

**Aanpassing voorbeeld:** Wil je het delete form verplaatsen?
Locatie: `<div class="fixed inset-x-0 bottom-0 ...">` → aanpassen CSS classes voor positie/layout

---

### **reportCard.blade.php** – Check Item Component

Render individuele check item met:

- Categorie + label + code
- Drie status pills
- Details panel (textarea + dual foto inputs: bestand + camera)

**Data-attributes (gebruikt door JavaScript):**

```html
data-check-item="check-ID"
<!-- Unieke ID voor lookup -->
data-status="pending|gecontroleerd|bijzonderheden"
<!-- Huidige status -->
data-search-index="..."
<!-- Zoekmatch string -->
data-status-field
<!-- Hidden input voor form -->
data-status-option="..."
<!-- Pill status keuze -->
data-note-wrapper
<!-- Details container -->
```

---

### **app.js** – Foto-upload & Preview

**Kernfunctie: Canvas resize + compression**

```javascript
// 1. FileReader leest bestand
// 2. Canvas schaalt naar max 1200×900
// 3. JPEG compression 85% kwaliteit
// 4. Blob opgeslagen op fileInput._resizedBlob
// 5. Preview weergegeven (max-width 200px)
```

**Aanpassing voorbeeld:** Wil je JPEG kwaliteit aanpassen?

```javascript
// Regel ~85: canvas.toBlob((blob) => {...}, "image/jpeg", 0.85);
// Verander 0.85 naar bijvoorbeeld 0.75 (meer compressie) of 0.95 (beter kwaliteit)
```

---

## Veelvoorkomende Aanpassingen

### 1. **PIN-code Beheerder Wijzigen**

📁 `config/admin.php`:

```php
return [
    'pin' => '1234',  // ← Wijzig deze waarde
];
```

### 2. **Rapport Statussen Toevoegen/Wijzigen**

📁 `app/Models/Report.php`:

```php
// Voeg casting toe
protected $casts = [
    'status' => 'string',  // of use enum in Laravel 8.1+
];
```

📁 `resources/views/reports/edit.blade.php`:

```php
<select name="status">
    <option value="concept">Concept</option>
    <option value="submitted">Ingediend</option>
    <option value="archived">Gearchiveerd</option>
    <!-- Voeg nieuwe status toe -->
</select>
```

### 3. **Nieuwe Check Status Toevoegen**

📁 `resources/views/reportCard.blade.php`:

```php
$statusOptions = [
    'pending' => ['label' => 'Open', 'hint' => 'Nog uitvoeren'],
    'gecontroleerd' => ['label' => 'Gereed', 'hint' => 'Afgevinkt'],
    'bijzonderheden' => ['label' => 'Bijzonder', 'hint' => 'Notitie/foto'],
    'NIEUW' => ['label' => 'Label', 'hint' => 'Hint'],  // ← Voeg toe
];
```

📁 `resources/views/tabblad.blade.php` (in initChecklistBoard script):

```javascript
const order = ["pending", "gecontroleerd", "bijzonderheden", "NIEUW"]; // Update hier
```

### 4. **Foto's Niet Comprimeren (Full Resolution)**

📁 `resources/js/app.js` (regel ~85):

```javascript
// Verwijder/comment out de Canvas resize logica
// En gebruik direct: fileInput._resizedBlob = file;
```

### 5. **PDF Lay-out Aanpassen**

📁 `app/Http/Controllers/ReportController.php` - `submit()` functie:

- PDF wordt gegenereerd vanuit Blade view (niet standaard ingesteld)
- Voeg view aan (`pdf.report` of soortgelijk)
- Update CSS/HTML in die view

### 6. **Kolommen Toevoegen aan Admin Tabel**

📁 `resources/views/reports/beheer.blade.php`:

Header:

```html
<th>Nieuwe Kolom</th>
```

Row:

```html
<td>{{ $report->nieuwe_kolom }}</td>
```

Zorg dat `nieuwe_kolom` bestaat in Report model/database.

### 7. **Handtekeningpad Canvas Grootte Wijzigen**

📁 `resources/views/tabblad.blade.php`:

```html
<canvas id="signature-pad" width="900" height="220"></canvas>
<!--                               ↑ Wijzig deze waarden ↑ -->
```

### 8. **Tailwind Kleuren Aanpassen**

📁 `tailwind.config.js` (als aanwezig):

```javascript
module.exports = {
  theme: {
    extend: {
      colors: {
        primary: "#...", // Custom kleuren
      },
    },
  },
};
```

Alternatief: Wijzig klasses in templates (bijv. `bg-sky-500` → `bg-blue-600`)

---

## Setup & Installatie

### Vereisten

- PHP 8.2+
- Composer
- Node.js 16+
- SQLite (standaard, of MySQL configureren)

### Stappen

```bash
# 1. Repository clonen
git clone <repo-url>
cd checklist/laravel

# 2. Dependencies installeren
composer install
npm install

# 3. Environment configureren
cp .env.example .env
php artisan key:generate

# 4. Database migreren
php artisan migrate --seed

# 5. Storage symlink creëren (voor PDF's/foto's)
php artisan storage:link

# 6. Development starten
php artisan serve          # Backend (localhost:8000)
npm run dev               # Frontend (Vite dev server)

# 7. Build voor productie
npm run build
```

### Troubleshooting

| Probleem              | Oorzaak               | Oplossing                  |
| --------------------- | --------------------- | -------------------------- |
| 403 Forbidden bij PDF | Symlink ontbreekt     | `php artisan storage:link` |
| Foto's niet zichtbaar | Storage symlink       | Zie boven                  |
| CSS niet compileren   | Vite niet draaiend    | `npm run dev`              |
| Database fout         | Migration niet gerund | `php artisan migrate`      |

---

## Tips voor Onderhoud

1. **Database wijzigingen?** → Maak migration: `php artisan make:migration add_field_to_reports`
2. **Model wijzigen?** → Update `$fillable` array
3. **Route wijzigen?** → Update `routes/web.php` + bijbehorende controller
4. **Styling wijzigen?** → Wijzig Tailwind classes direct in Blade views
5. **JavaScript toevoegen?** → Voeg toe in `resources/js/` of inline in `<script>` tag
6. **PDF lay-out?** → Werk aan een aparte Blade view, style met CSS (DomPDF ondersteunt basis CSS)

---

**Vragen? Controleer:**

- `config/` – App-instellingen
- `routes/web.php` – Route definitions
- `app/Http/Controllers/` – Business logic
- `resources/views/` – Visuele componenten
- `resources/js/` – Interactiviteit
