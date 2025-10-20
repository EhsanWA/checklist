document.addEventListener("DOMContentLoaded", function () {
    // --- Modal window for report send confirmation ---
    function createModal() {
        // Create overlay
        const overlay = document.createElement("div");
        overlay.className =
            "verzend-modal-overlay fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50";

        // Create modal box
        const modal = document.createElement("div");
        modal.className =
            "verzend-modal bg-white rounded-lg shadow-lg w-full max-w-md relative";

        // Navigation bar with close button
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

        // Modal content
        const content = document.createElement("div");
        content.className = "p-6 text-center";
        content.innerHTML =
            "<h2 class='text-lg font-bold mb-2'>Rapport verzenden</h2><p>Weet je zeker dat je het rapport wilt verzenden? Deze actie kan niet ongedaan worden gemaakt.</p>";

        // Assemble modal
        modal.appendChild(nav);
        modal.appendChild(content);
        overlay.appendChild(modal);
        return overlay;
    }

    // --- Create the "Verzend rapport" button ---
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

    // --- Add or remove the send button based on tab2-content state ---
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

    // --- Initial check on DOMContentLoaded ---
    updateVerzendRapportBtn();

    // --- Update button on drag and drop events ---
    document.querySelectorAll(".tab-content").forEach(function (tab) {
        tab.addEventListener("drop", updateVerzendRapportBtn);
    });
    document.querySelectorAll(".tab-btn").forEach(function (btn) {
        btn.addEventListener("drop", updateVerzendRapportBtn);
    });
});
