document.querySelector(".date-filter").addEventListener("click", function() {
    let startDateInput = document.getElementById("start-date");
    let endDateInput = document.getElementById("end-date");

    startDateInput.showPicker(); // Munculin date picker

    startDateInput.addEventListener("change", function() {
        endDateInput.showPicker(); // Setelah pilih start, munculin end
    });

    endDateInput.addEventListener("change", function() {
        if (startDateInput.value && endDateInput.value) {
            document.getElementById("date-range").textContent = 
                `${startDateInput.value} - ${endDateInput.value}`;
        }
    });
});
