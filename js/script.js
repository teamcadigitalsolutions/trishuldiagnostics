/* ================= NAVBAR TOGGLE ================= */
const navToggle = document.querySelector(".nav-toggle");
const navLinks = document.querySelector(".nav-links");

navToggle?.addEventListener("click", () => {
    navLinks.classList.toggle("show");
});

/* ================= SMOOTH SCROLL ================= */
document.querySelectorAll("a[href^='#']").forEach(link => {
    link.addEventListener("click", e => {
        const targetId = link.getAttribute("href");
        const targetEl = document.querySelector(targetId);

        if (targetEl) {
            e.preventDefault();
            targetEl.scrollIntoView({ behavior: "smooth" });
        }
    });
});

/* ================= FORM VALIDATION & AJAX SUBMISSION ================= */
document.querySelectorAll("form").forEach(form => {
    form.addEventListener("submit", async function(e) {
        e.preventDefault();

        // Client-side validation
        let valid = true;
        form.querySelectorAll("input[required], textarea[required], select[required]").forEach(field => {
            if (!field.value.trim()) {
                field.style.borderColor = "red";
                valid = false;
            } else {
                field.style.borderColor = "#ccc";
            }
        });

        if (!valid) {
            alert("Please fill in all required fields.");
            return;
        }

        // Add hidden form_type input if missing
        if (!form.querySelector('input[name="form_type"]')) {
            const hiddenInput = document.createElement("input");
            hiddenInput.type = "hidden";
            hiddenInput.name = "form_type";
            hiddenInput.value = form.id.replace("Form","").toLowerCase(); // e.g. medicalForm -> medical
            form.appendChild(hiddenInput);
        }

        const formData = new FormData(form);

        try {
            const response = await fetch('form_save.php', {
                method: 'POST',
                body: formData
            });

            // Check if the response is OK
            if (!response.ok) {
                throw new Error(`Server returned ${response.status}`);
            }

            // Try parsing JSON
            const data = await response.json();

            if (data.status === 'success') {
                alert(data.message);
                form.reset();
            } else {
                alert(data.message || "Submission failed.");
            }
        } catch (err) {
            console.error(err);
            alert("Something went wrong! Please try again later.");
        }
    });
});

/* ================= IMAGE GALLERY MODAL ================= */
const modal = document.getElementById("imageModal");
const modalImg = document.getElementById("modalImage");
const closeBtn = document.querySelector(".close");

if (modal && modalImg && closeBtn) {
    document.querySelectorAll(".gallery-scroll img").forEach(img => {
        img.addEventListener("click", () => {
            modal.style.display = "flex";
            modalImg.src = img.src;
        });
    });

    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    modal.addEventListener("click", e => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
}
