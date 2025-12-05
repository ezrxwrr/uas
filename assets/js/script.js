// ===============================
// Smooth Scroll ke Section Tertentu
// ===============================
function scrollToSection(id) {
    const target = document.getElementById(id);
    if (target) {
        target.scrollIntoView({ behavior: "smooth" });
    }
}



// ===============================
// Placeholder Total Donasi (Frontend Mode)
// Tidak menampilkan angka 0
// ===============================
document.addEventListener("DOMContentLoaded", () => {
    const el = document.getElementById("total-donasi");

    if (el) {
        // Data dummy — backend nanti akan mengganti variabel ini
        let totalDonasi = null;
        // Jika backend sudah ada:
        // let totalDonasi = <?= $total_donasi ?>;

        if (!totalDonasi || totalDonasi === 0) {
            el.textContent = "Data akan ditampilkan di sini";
        } else {
            el.textContent = "Rp " + totalDonasi.toLocaleString("id-ID");
        }
    }
});



// ===============================
// Alert Donasi (Frontend Only)
// ===============================
document.addEventListener("DOMContentLoaded", () => {
    const donateForm = document.querySelector(".form-container form");

    if (donateForm) {
        donateForm.addEventListener("submit", (e) => {
            e.preventDefault();
            alert("Terima kasih! Donasi Anda telah dikirim (Tampilan frontend saja)");
        });
    }
});
