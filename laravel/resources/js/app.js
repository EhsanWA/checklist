import "./bootstrap";

// Handleiding foto's, drag/drop, en drag-disable toggle (via form data-enable-drag attribute).
document.addEventListener("DOMContentLoaded", function () {
    function getTabContent(index) {
        return document.getElementById(`tab${index}-content`);
    }

    // Zorg voor consistente ID's
    function ensureId(el) {
        if (!el.id) {
            el.id = `report-${Date.now()}-${Math.random()
                .toString(36)
                .slice(2)}`;
        }
    }

    // Bijzonderheden logica: Activeer notes + foto inputs voor status=bijzonderheden via moveItem().

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

        // FOTO BUTTON
        const fotobtn = document.createElement("button");
        fotobtn.type = "button";
        fotobtn.className =
            "inline-flex items-center px-3 py-1 rounded text-sm bg-green-500 text-white hover:bg-green-600 focus:outline-none mb-2";

        const btnText = document.createElement("span");
        btnText.className = "btn-text";
        btnText.textContent = "Foto toevoegen";

        const icon = document.createElement("i");
        icon.className = "fas fa-camera ml-2";

        fotobtn.appendChild(btnText);
        fotobtn.appendChild(icon);

        fotobtn.addEventListener("click", function () {
            let fileInput = el.querySelector(".report-photo-input");
            if (!fileInput) {
                fileInput = document.createElement("input");
                fileInput.type = "file";
                fileInput.accept = "image/*";
                fileInput.className = "report-photo-input";
                fileInput.style.display = "none";

                // Foto upload met 2MB client-side limiet + Canvas resize/compress (1200×900, JPEG 85%).
                fileInput.addEventListener("change", function () {
                    const file = this.files?.[0];
                    if (!file) return;

                    const MAX = 2 * 1024 * 1024;
                    if (file.size > MAX) {
                        alert(
                            "De geselecteerde foto is te groot. Maximaal 2MB."
                        );
                        this.value = "";
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        // Canvas resize: max 1200×900, export JPEG 85% kwaliteit.
                        const canvas = document.createElement("canvas");
                        const img = new Image();
                        img.onload = function () {
                            const maxW = 1200,
                                maxH = 900;
                            let w = img.width,
                                h = img.height;
                            if (w > h) {
                                if (w > maxW) {
                                    h = h * (maxW / w);
                                    w = maxW;
                                }
                            } else {
                                if (h > maxH) {
                                    w = w * (maxH / h);
                                    h = maxH;
                                }
                            }
                            canvas.width = w;
                            canvas.height = h;
                            canvas.getContext("2d").drawImage(img, 0, 0, w, h);
                            canvas.toBlob(
                                (blob) => {
                                    fileInput._resizedBlob = blob;
                                },
                                "image/jpeg",
                                0.85
                            );
                        };
                        img.src = e.target.result;

                        const wrap = document.createElement("div");
                        wrap.className = "photo-preview mt-2 relative";

                        const preview = document.createElement("img");
                        preview.src = e.target.result;
                        preview.className = "rounded shadow-md";
                        preview.style.maxWidth = "200px";

                        const removeBtn = document.createElement("button");
                        removeBtn.type = "button";
                        removeBtn.className =
                            "remove-photo absolute top-1 right-1 bg-red-500 text-white px-2 py-1 rounded text-xs";
                        removeBtn.textContent = "Verwijder";

                        removeBtn.addEventListener("click", function () {
                            wrap.remove();
                            fileInput.value = "";
                        });

                        wrap.appendChild(preview);
                        wrap.appendChild(removeBtn);
                        el.appendChild(wrap);
                    };

                    reader.readAsDataURL(file);
                });

                el.appendChild(fileInput);
            }

            fileInput.click();
        });

        el.appendChild(fotobtn);

        // BIJZONDERHEDEN TEXT
        const btn = document.createElement("button");
        btn.type = "button";
        btn.className =
            "bijz-toggle inline-flex items-center px-3 py-1 rounded text-sm bg-sky-500 text-white hover:bg-sky-600 focus:outline-none mt-2";
        btn.innerHTML = `
            <span class="btn-text">Toon bijzonderheden</span>
            <i class="fas fa-chevron-down ml-2 transition-transform"></i>
        `;

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
            icon.style.transform = hidden ? "rotate(0deg)" : "rotate(180deg)";
        });

        el.appendChild(btn);
        el.appendChild(textarea);
        return el;
    }

    // Drag & drop verwijderen voor bijzonderheden
    function removeBijzonderheden(el) {
        if (!el.classList.contains("is-bijzonderheden")) return el;

        el.classList.remove("is-bijzonderheden");

        const btn = el.querySelector(".bijz-toggle");
        const textarea = el.querySelector(".bijz-textarea");

        if (btn) btn.remove();
        if (textarea) textarea.remove();

        return el;
    }
});
