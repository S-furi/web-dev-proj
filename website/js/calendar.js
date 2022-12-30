function fetchEvents() {
    return axios.get("api/api-post.php?action=1")
        .then(res => {
            if ( res.data.length > 0 ) {
                res.data
                    .forEach(event => {
                        event.eventDate = new Date(event.eventDate);
                        events.push(event);
                    });
            }
        }).catch(err => console.log(err));
}

function getCurrentMonthEvents() {
    return events
        .filter(t => t.eventDate.getMonth() == currentMonth && t.eventDate.getFullYear() == currentYear);
}

function renderCalendar() {
    const currentMonthEvents = getCurrentMonthEvents();
    console.log(currentMonthEvents);

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
        const classList = isSpecialDay(i, currentMonthEvents.map(t => t.eventDate));
        let itemClasses = classList.length === 0 ? "" : (classList.length == 1 ? ` class="${classList[0]}"` : ` class="${classList[0]} ${classList[1]}"`);
        htmlDayTag += `<li${itemClasses}>${i}</li>`;
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

function isSpecialDay(day, busyDays) {
    const classList = [];
    if (compareDays(day, new Date())) { 
        classList.push("active");
    } else if (busyDays.filter(t => compareDays(day, t)).length !== 0) {
        classList.push("busy");
    }
    return classList;
}

function compareDays(day, comparisonDate) {
    return (day == comparisonDate.getDate() 
            && currentMonth == comparisonDate.getMonth() 
            && currentYear == comparisonDate.getFullYear());
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

// finally, render the calendar and fill all the events
const events = [];

fetchEvents().then(() => renderCalendar());

