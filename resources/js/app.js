import flatpickr from "flatpickr";

function addEvents() {
    const e = new Date();
    e.setHours(0);
    flatpickr(".datepicker", {
        minDate: e,
        enableTime: true,
        time_24hr: true,
    });

    const el = document.getElementById('start');
    if(el !== null) {
        el.addEventListener("change",
            (function () {
                const e1 = new Date(document.getElementById('start').value);
                const e2 = new Date(e1);
                e2.setHours(e2.getHours() + 1);
                document.getElementById('end').flatpickr({
                    defaultDate: e2,
                    minDate: e1,
                    enableTime: true,
                    time_24hr: true,
                });
            })
        );
    }
}

document.addEventListener('DOMContentLoaded', addEvents);
