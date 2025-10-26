import "./bootstrap";

document.addEventListener("DOMContentLoaded", function () {
    function getTabContent(index) {
        return document.getElementById(`tab${index}-content`);
    }

    // check of een element een id heeft en zo niet, geef het een id
    function ensureId(el) {
        if (!el.id) {
            el.id = `report-${Date.now()}-${Math.random()
                .toString(36)
                .slice(2)}`;
        }
    }

    // maak een element sleepbaar
    function bindDraggable(el) {
        ensureId(el);
        el.setAttribute("draggable", "true");
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

    // verander opdracht als het onder bijzonderheden valt
    function makeBijzonderheden(el) {
        if (el.classList.contains("is-bijzonderheden")) return el;

        el.classList.add(
            "is-bijzonderheden",
            "bg-white",
            "shadow-md",
            "rounded-lg",
            "p-4",
            "mb-4"
        );

        const btn = document.createElement("button");
        btn.type = "button";
        btn.className =
            "bijz-toggle inline-flex items-center px-3 py-1 rounded text-sm bg-sky-500 text-white hover:bg-sky-600 focus:outline-none mt-2";
        btn.innerHTML = `<span class="btn-text">Toon bijzonderheden</span> <i class="fas fa-chevron-down ml-2 transition-transform"></i>`;

        const textarea = document.createElement("textarea");
        textarea.className =
            "bijz-textarea w-full border border-gray-300 rounded mt-2 p-2 hidden resize-none";
        textarea.placeholder = "Voer bijzonderheden in...";

        textarea.addEventListener("focusout", function () {
            if (textarea.value === "") {
                textarea.classList.add("border-red-500", "bg-red-50");
            } else {
                textarea.classList.remove("border-red-500", "bg-red-50");
            } 
        });

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

        el.appendChild(btn);
        el.appendChild(textarea);

        bindDraggable(el);
        return el;
    }


    // verwijdert bijzonderheden styling en elementen als het onder gecontroleerd valt
    function removeBijzonderheden(el) {
        if (!el.classList.contains("is-bijzonderheden")) return el;

        el.classList.remove(
            "is-bijzonderheden",
        );

        const btn = el.querySelector(".bijz-toggle");
        const textarea = el.querySelector(".bijz-textarea");
        if (btn) el.removeChild(btn);
        if (textarea) el.removeChild(textarea);

        bindDraggable(el);
        return el;
    }


    // Initial binding
    document.querySelectorAll(".draggable-report").forEach(bindDraggable);


    // Drop onto tab contents
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
            if (!draggedEl) return;

            if (tab.id === "tab3-content") {
                makeBijzonderheden(draggedEl);
            } else if (tab.id === "tab1-content") {
                removeBijzonderheden(draggedEl);
            }

            tab.appendChild(draggedEl);
        });
    });


    // Drop onto tab buttons (also switch tabs)
    document.querySelectorAll(".tab-btn").forEach(function (btn) {
        btn.addEventListener("dragover", function (ev) {
            ev.preventDefault();
            this.classList.add("drag-over-tab-btn");
            const idx = this.id.match(/\d+/)?.[0];
            if (idx && typeof switchTab === "function") switchTab(Number(idx));
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
            if (!draggedEl || !idx) return;

            if (typeof switchTab === "function") switchTab(Number(idx));
            const target = getTabContent(idx);
            if (!target) return;

            if (target.id === "tab3-content") {
                makeBijzonderheden(draggedEl);
            } else if (target.id === "tab1-content") {
                removeBijzonderheden(draggedEl);
            }

            target.appendChild(draggedEl);
        });
    });
});
