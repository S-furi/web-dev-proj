function isToday(day) {
    const todayDate = new Date();
    return (day == todayDate.getDate() && currentMonth == todayDate.getMonth() && currentYear == todayDate.getFullYear())
        ? ` class="active"` : "";
}

function renderCalendar() {
    // first day index of current month
    const firstDayIndex = new Date(currentYear, currentMonth, 1).getDay();

    // last day index of current month
    const lastDayCurMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    const lastDayIndex = new Date(currentYear, currentMonth, lastDayCurMonth).getDay();

    // get last month's last day number
    const lastMonthLastDay = new Date(currentYear, currentMonth, 0).getDate();

    let htmlDayTag = "";
    // fill last days of last month
    for (let i = firstDayIndex; i > 0; i--) {
        htmlDayTag += `<li class="inactive">${lastMonthLastDay - i + 1}</li>`
    }

    // fill the rest of current month's days
    for (let i = 1; i <= lastDayCurMonth; i++) {
        htmlDayTag += `<li${isToday(i)}>${i}</li>`;
    }

    // filling next months first days
    for (let i = lastDayIndex; i < 6; i++) {
        htmlDayTag += `<li class="inactive">${i - lastDayIndex + 1}</li>`
    }

    document.querySelector(".right .current-date").innerHTML = `${months[currentMonth]} ${currentYear}`
    document.querySelector(".right .days").innerHTML = htmlDayTag;

    document.querySelector(".left .current-date").innerHTML = `${months[currentMonth]} ${currentYear}`
    document.querySelector(".left .days").innerHTML = htmlDayTag;
}

function handleMonthChange(event) {
    currentMonth = event.target.id == "prev" ? currentMonth - 1 : currentMonth + 1;
    if (currentMonth < 0 || currentMonth > 11) {
        date = new Date(currentYear, currentMonth);
        currentYear = date.getFullYear();
        currentMonth = date.getMonth();
    } else {
        date = new Date();
    }
    renderCalendar();
}


function insertCalendar() {
    const wrapperContent = `
            <div class="calendar-head">
                <p class="current-date">Settembre 2022</p>
                <div class="icons">
                    <span id="prev" class="material-symbols-outlined">chevron_left</span>
                    <span id="next" class="material-symbols-outlined">chevron_right</span>
                </div>
            </div>
            <div class="calendar-body">
                <ul class="weeks">
                    <li>Dom</li>
                    <li>Lun</li>
                    <li>Mar</li>
                    <li>Mer</li>
                    <li>Gio</li>
                    <li>Ven</li>
                    <li>Sab</li>
                </ul>
                <ul class="days">
                </ul>
            </div>`;

    document.querySelector(".right .calendar-wrapper").innerHTML = wrapperContent;
    document.querySelector(".left #popup-cal").innerHTML = wrapperContent;
}


function showPopupCalendar() {
    document.querySelector(".left #popup-cal").classList.toggle("show");
    switchPopupIcon();
}


function switchPopupIcon() {
    const calBtn = document.querySelector(".left .popup-calendar-wrapper .calendar-btn");

    if (calBtn.classList.contains("toggle-on")) {
        calBtn.classList.remove("toggle-on");
        calBtn.innerHTML = closeIcon;
    } else {
        calBtn.classList.add("toggle-on");
        calBtn.innerHTML = calIcon;
    }
}

// Google icons for calendar toggle btn
const closeIcon = `<span class="material-symbols-outlined">close</span>`;
const calIcon = `<span class="material-symbols-outlined">calendar_month</span>`;

// they'll be modified if prev or next icons are clicked
let date = new Date();
let currentMonth = date.getMonth();
let currentYear = date.getFullYear();


const months = [
    "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio",
    "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"];

insertCalendar();

// make the chevrons change months
document.querySelectorAll(".right .calendar-wrapper .icons span").forEach(icon => {
    icon.addEventListener('click', event => handleMonthChange(event));
});

document.querySelectorAll(".left .popup-calendar-wrapper .icons span").forEach(icon => {
    icon.addEventListener('click', event => handleMonthChange(event));
});

// finally, render the calendar
renderCalendar();
