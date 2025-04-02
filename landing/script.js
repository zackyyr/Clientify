document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".users-category__btn button");
    const descriptions = document.querySelectorAll(".users-info");

    buttons.forEach((button, index) => {
        button.addEventListener("click", function () {
            // Remove active class from all buttons
            buttons.forEach(btn => btn.classList.remove("user-active"));
            // Add active class to clicked button
            this.classList.add("user-active");

            // Hide all descriptions
            descriptions.forEach(desc => {
                desc.classList.remove("info-active");
                setTimeout(() => { desc.style.display = "none"; }, 300); // Tunggu animasi sebelum sembunyikan
            });

            // Show the corresponding description with fade effect
            setTimeout(() => {
                descriptions[index].style.display = "flex";
                descriptions[index].classList.add("info-active");
            }, 300); // Delay supaya animasi smooth
        });
    });
});


// Slider 
document.addEventListener("DOMContentLoaded", function () {
    const slider = document.querySelector(".testimonial-cards");
    const cards = document.querySelectorAll(".testimonial-card");

    // Duplikat semua testimonial agar loop terlihat seamless
    cards.forEach((card) => {
        let clone = card.cloneNode(true);
        slider.appendChild(clone);
    });
});
