import "./bootstrap";

document.addEventListener("DOMContentLoaded", function () {
    function getTabContent(index) {
        return document.getElementById(`tab${index}-content`);
    }

    // Maak rapporten versleepbaar
    document.querySelectorAll(".draggable-report").forEach(function (el, idx) {
        if (!el.id) {
            el.id = `report-${idx}-${Math.random().toString(36).slice(2)}`;
        }
        el.addEventListener("dragstart", function (ev) {
            const id = ev.currentTarget.id;
            ev.dataTransfer.setData("text/plain", id);
            ev.dataTransfer.effectAllowed = "move";
            ev.currentTarget.classList.add("dragging");
        });

        el.addEventListener("dragend", function (ev) {
            ev.currentTarget.classList.remove("dragging");
        });
    });

    // Allow dropping into tab contents
    document.querySelectorAll(".tab-content").forEach(function (tab) {
        tab.addEventListener("dragover", function (ev) {
            ev.preventDefault();
            ev.dataTransfer.dropEffect = "move";
            tab.classList.add("drag-over");
        });

        tab.addEventListener("dragleave", function () {
            tab.classList.remove("drag-over");
        });

        tab.addEventListener("drop", function (ev) {
            ev.preventDefault();
            tab.classList.remove("drag-over");
            const reportId = ev.dataTransfer.getData("text/plain");
            const draggedEl = document.getElementById(reportId);
            if (draggedEl) tab.appendChild(draggedEl);
        });
    });

    // Enable dropping via tab buttons
    document.querySelectorAll(".tab-btn").forEach(function (btn) {
        btn.addEventListener("dragover", function (ev) {
            ev.preventDefault();
            this.classList.add("drag-over-tab-btn");
            const idx = this.id.match(/\d+/)?.[0];
            if (idx) switchTab(Number(idx));
        });

        btn.addEventListener("dragleave", function () {
            this.classList.remove("drag-over-tab-btn");
        });

        btn.addEventListener("drop", function (ev) {
            ev.preventDefault();
            this.classList.remove("drag-over-tab-btn");
            const reportId = ev.dataTransfer.getData("text/plain");
            const draggedEl = document.getElementById(reportId);
            const idx = this.id.match(/\d+/)?.[0];
            if (idx && draggedEl) {
                switchTab(Number(idx));
                const target = getTabContent(idx);
                target.appendChild(draggedEl);
            }
        });
    });
});

// dropdown voor opdrachten onder "bijzonderheden" tab
document.addEventListener("DOMContentLoaded", function () {
    function getTabContent(index) {
        return document.getElementById(`tab${index}-content`);
    }

    // Ensure element has an id
    function ensureId(el) {
        if (!el.id)
            el.id = `report-${Date.now()}-${Math.random()
                .toString(36)
                .slice(2)}`;
    }

    // Bind drag handlers to an element (id + dragstart/dragend)
    function bindDraggable(el) {
        ensureId(el);
        el.setAttribute("draggable", "true");
        // avoid double-binding
        if (el.dataset.dragBound === "1") return;
        el.addEventListener("dragstart", function (ev) {
            ev.dataTransfer.setData("text/plain", el.id);
            ev.dataTransfer.effectAllowed = "move";
            el.classList.add("dragging");
        });
        el.addEventListener("dragend", function () {
            el.classList.remove("dragging");
        });
        el.dataset.dragBound = "1";
    }

    // Transform the given report card into a "Bijzonderheden" card (adds dropdown + textarea)
    function makeBijzonderheden(el) {
        // idempotent: do nothing if already transformed
        if (el.classList.contains("is-bijzonderheden")) return el;

        el.classList.add(
            "is-bijzonderheden",
            "bg-white",
            "shadow-md",
            "rounded-lg",
            "p-4",
            "mb-4"
        );

        // create toggle button
        const btn = document.createElement("button");
        btn.type = "button";
        btn.className =
            "bijz-toggle inline-flex items-center px-3 py-1 rounded text-sm bg-sky-500 text-white hover:bg-sky-600 focus:outline-none mt-2";
        btn.innerHTML = `<span class="btn-text">Toon bijzonderheden</span> <i class="fas fa-chevron-down ml-2 transition-transform"></i>`;

        // create textarea
        const textarea = document.createElement("textarea");
        textarea.className =
            "bijz-textarea w-full border border-gray-300 rounded mt-2 p-2 hidden resize-none";
        textarea.placeholder = "Voer bijzonderheden in...";

        btn.addEventListener("click", function () {
            const hidden = textarea.classList.toggle("hidden");
            btn.querySelector(".btn-text").textContent = hidden
                ? "Toon bijzonderheden"
                : "Verberg bijzonderheden";
            const icon = btn.querySelector("i");
            if (icon)
                icon.style.transform = hidden
                    ? "rotate(0deg)"
                    : "rotate(180deg)";
        });

        // append the controls at the end of the report card
        el.appendChild(btn);
        el.appendChild(textarea);

        // ensure continued draggable behavior
        bindDraggable(el);

        return el;
    }

    // initial binding for existing report cards
    document.querySelectorAll(".draggable-report").forEach(bindDraggable);

    // Tab contents: allow drop and transform if dropped into tab3-content (Bijzonderheden)
    document.querySelectorAll(".tab-content").forEach(function (tab) {
        tab.addEventListener("dragover", function (ev) {
            ev.preventDefault();
            ev.dataTransfer.dropEffect = "move";
            tab.classList.add("drag-over");
        });

        tab.addEventListener("dragleave", function () {
            tab.classList.remove("drag-over");
        });

        tab.addEventListener("drop", function (ev) {
            ev.preventDefault();
            tab.classList.remove("drag-over");
            const reportId = ev.dataTransfer.getData("text/plain");
            if (!reportId) return;
            const draggedEl = document.getElementById(reportId);
            if (!draggedEl) return;

            // If dropping into Bijzonderheden (tab3-content) transform the card
            if (tab.id === "tab3-content") {
                makeBijzonderheden(draggedEl);
            }
            tab.appendChild(draggedEl);
        });
    });

    // Tab buttons: switching + drop (if dropped on a tab button, we switch to that tab and append)
    document.querySelectorAll(".tab-btn").forEach(function (btn) {
        btn.addEventListener("dragover", function (ev) {
            ev.preventDefault();
            this.classList.add("drag-over-tab-btn");
            const idx = this.id.match(/\d+/)?.[0];
            if (idx) switchTab(Number(idx));
        });

        btn.addEventListener("dragleave", function () {
            this.classList.remove("drag-over-tab-btn");
        });

        btn.addEventListener("drop", function (ev) {
            ev.preventDefault();
            this.classList.remove("drag-over-tab-btn");
            const reportId = ev.dataTransfer.getData("text/plain");
            if (!reportId) return;
            const draggedEl = document.getElementById(reportId);
            const idx = this.id.match(/\d+/)?.[0];
            if (idx && draggedEl) {
                switchTab(Number(idx));
                const target = getTabContent(idx);
                if (target && target.id === "tab3-content") {
                    makeBijzonderheden(draggedEl);
                }
                if (target) target.appendChild(draggedEl);
            }
        });
    });
});
