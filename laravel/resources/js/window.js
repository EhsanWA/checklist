document.addEventListener("DOMContentLoaded", function () {
    // venster aanmaken
    function createModal() {
        // Creeer overlay
        const overlay = document.createElement("div");
        overlay.className =
            "verzend-modal-overlay fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50";

        // Creeer venster
        const modal = document.createElement("div");
        modal.className =
            "verzend-modal bg-white rounded-lg shadow-lg w-full max-w-md relative";

        // Navigatiebalk met sluitknop
        const nav = document.createElement("div");
        nav.className =
            "flex items-center justify-end border-b border-gray-200 px-4 py-2";
        nav.style.height = "40px";

        const closeBtn = document.createElement("button");
        closeBtn.type = "button";
        closeBtn.innerHTML = "<span aria-label='Sluiten'>&#10005;</span>";
        closeBtn.className =
            "text-gray-500 hover:text-gray-700 text-xl focus:outline-none";
        closeBtn.style.background = "none";
        closeBtn.style.border = "none";
        closeBtn.onclick = function () {
            if (overlay.parentNode === document.body) {
                document.body.removeChild(overlay);
            }
        };
        nav.appendChild(closeBtn);

        // venster inhoud
        const content = document.createElement("div");
        content.className = "p-6";

        // Titel en beschrijving
        const title = document.createElement("h2");
        title.className = "text-lg font-bold mb-2 text-center";
        title.textContent = "Rapport verzenden";

        const description = document.createElement("p");
        description.className = "text-center mb-4";
        description.textContent =
            "Weet je zeker dat je het rapport wilt verzenden? Deze actie kan niet ongedaan worden gemaakt.";

        // Handtekening label
        const signatureLabel = document.createElement("label");
        signatureLabel.className =
            "block text-sm font-medium text-gray-700 mb-2";
        signatureLabel.textContent = "Handtekening:";

        // Canvas container
        const canvasContainer = document.createElement("div");
        canvasContainer.className =
            "border-2 border-gray-300 rounded mb-4 bg-white";
        canvasContainer.id = "signature-container";

        // Canvas voor handtekening
        const canvas = document.createElement("canvas");
        canvas.width = 400;
        canvas.height = 150;
        canvas.className = "w-full cursor-crosshair";
        canvas.id = "signature-canvas";

        const ctx = canvas.getContext("2d");
        let isDrawing = false;
        let hasSigned = false;

        // Canvas tekenen logica
        canvas.addEventListener("mousedown", function (e) {
            isDrawing = true;
            const rect = canvas.getBoundingClientRect();
            ctx.beginPath();
            ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
        });

        canvas.addEventListener("mousemove", function (e) {
            if (!isDrawing) return;
            hasSigned = true;
            const rect = canvas.getBoundingClientRect();
            ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
            ctx.strokeStyle = "#000";
            ctx.lineWidth = 2;
            ctx.lineCap = "round";
            ctx.stroke();
        });

        canvas.addEventListener("mouseup", function () {
            isDrawing = false;
        });

        canvas.addEventListener("mouseleave", function () {
            isDrawing = false;
        });

        // Touch support voor mobiel
        canvas.addEventListener("touchstart", function (e) {
            const rect = canvas.getBoundingClientRect();
            const touch = e.touches[0];
            // Only prevent default if touch is inside the canvas
            if (
                touch.clientX >= rect.left &&
                touch.clientX <= rect.right &&
                touch.clientY >= rect.top &&
                touch.clientY <= rect.bottom
            ) {
                e.preventDefault();
                isDrawing = true;
                ctx.beginPath();
                ctx.moveTo(touch.clientX - rect.left, touch.clientY - rect.top);
            }
        });

        canvas.addEventListener("touchmove", function (e) {
            e.preventDefault();
            if (!isDrawing) return;
            hasSigned = true;
            const rect = canvas.getBoundingClientRect();
            const touch = e.touches[0];
            ctx.lineTo(touch.clientX - rect.left, touch.clientY - rect.top);
            ctx.strokeStyle = "#000";
            ctx.lineWidth = 2;
            ctx.lineCap = "round";
            ctx.stroke();
        });

        canvas.addEventListener("touchend", function (e) {
            e.preventDefault();
            isDrawing = false;
        });

        canvasContainer.appendChild(canvas);

        // Wis knop
        const clearBtn = document.createElement("button");
        clearBtn.type = "button";
        clearBtn.className =
            "text-sm text-gray-600 hover:text-gray-800 underline mb-4";
        clearBtn.textContent = "Wis handtekening";
        clearBtn.addEventListener("click", function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hasSigned = false;
            canvasContainer.classList.remove("border-red-500");
            canvasContainer.classList.add("border-gray-300");
        });

        // Verzend knop
        const submitBtn = document.createElement("button");
        submitBtn.type = "button";
        submitBtn.className =
            "w-full px-4 py-2 rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none";
        submitBtn.textContent = "Bevestig en verzend";
        submitBtn.addEventListener("click", function () {
            if (!hasSigned) {
                // Maak canvas rood als er niet getekend is
                canvasContainer.classList.remove("border-gray-300");
                canvasContainer.classList.add("border-red-500");

                // Schud animatie
                canvasContainer.style.animation = "shake 0.3s";
                setTimeout(() => {
                    canvasContainer.style.animation = "";
                }, 300);
            } else {
                // Hier kun je de verzendlogica toevoegen
                alert("Rapport verzonden!");
                if (overlay.parentNode === document.body) {
                    document.body.removeChild(overlay);
                }
            }
        });

        // Voeg alles toe aan content
        content.appendChild(title);
        content.appendChild(description);
        content.appendChild(signatureLabel);
        content.appendChild(canvasContainer);
        content.appendChild(clearBtn);
        content.appendChild(submitBtn);

        // venster samenstellen
        modal.appendChild(nav);
        modal.appendChild(content);
        overlay.appendChild(modal);

        // Voeg shake animatie toe aan document
        if (!document.getElementById("shake-animation-style")) {
            const style = document.createElement("style");
            style.id = "shake-animation-style";
            style.textContent = `
                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    25% { transform: translateX(-10px); }
                    75% { transform: translateX(10px); }
                }
            `;
            document.head.appendChild(style);
        }

        return overlay;
    }

    // Creerer de "Verzend rapport" knop
    function createVerzendRapportBtn() {
        const sendBtn = document.createElement("button");
        sendBtn.type = "button";
        sendBtn.className =
            "verzend-rapport-btn inline-flex items-center px-4 py-2 rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none mt-4";
        sendBtn.textContent = "Verzend rapport";
        sendBtn.addEventListener("click", function () {
            const modal = createModal();
            document.body.appendChild(modal);
        });
        return sendBtn;
    }

    // voeg of verwijder de "Verzend rapport" knop op basis van tab2 inhoud
    function updateVerzendRapportBtn() {
        const tab2 = document.getElementById("tab2-content");
        const tab1 = document.getElementById("tab1-content");
        if (!(tab2 && tab1)) return;

        const btn = tab1.querySelector(".verzend-rapport-btn");
        const isTab2Empty =
            tab2.querySelectorAll(".draggable-report").length === 0;

        if (isTab2Empty) {
            if (!btn) tab1.appendChild(createVerzendRapportBtn());
        } else {
            if (btn) btn.remove();
        }
    }

    // Initial check on DOMContentLoaded
    updateVerzendRapportBtn();

    // Update knop met elke drop actie
    document.querySelectorAll(".tab-content").forEach(function (tab) {
        tab.addEventListener("drop", updateVerzendRapportBtn);
    });
    document.querySelectorAll(".tab-btn").forEach(function (btn) {
        btn.addEventListener("drop", updateVerzendRapportBtn);
    });
});
