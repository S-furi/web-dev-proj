function fetchEvents() {
    return axios.get("api/api-post.php?action=1")
        .then(res => {
            if (res.data.length > 0) {
                res.data
                    .forEach(event => {
                        event.eventDate = new Date(event.eventDate);
                        events.push(event);
                    });
            }
        }).catch(_ => { return } );
}

function getCurrentMonthEvents() {
    return events
        .filter(t => t.eventDate.getMonth() == currentMonth && t.eventDate.getFullYear() == currentYear);
}

function showEventsBrief(value) {
    const selectedDate = new Date(currentYear, currentMonth, value);
    const selectedEvents = getCurrentMonthEvents()
        .filter(t => t.eventDate.getDate() == selectedDate.getDate()
            && t.eventDate.getMonth() == selectedDate.getMonth()
            && t.eventDate.getFullYear() == selectedDate.getFullYear());

    modal.style.display = "block";
    selectedEvents.forEach(t => insertEventInView(t));
}

function insertEventInView(event) {
    const container = document.querySelector(".modal .events-of-day");
    const ref = `post.php?usrId=${event.usrId}&postId=${event.postId}`;
    container.innerHTML += `<li><a href="${ref}" >"<strong>${event.title}</strong>", il ${event.eventDate.toLocaleDateString('it-IT')}</a></li>`
}

function renderCalendar() {
    const currentMonthEvents = getCurrentMonthEvents();

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
        if (classList.includes("busy")) {
            htmlDayTag += `<li${itemClasses}><button id="events-btn-${i}" onclick="showEventsBrief(this.innerHTML)" >${i}</button></li>`;
            continue;
        }
        htmlDayTag += `<li${itemClasses}>${i}</li>`;
    }

    // filling next months first days
    for (let i = lastDayIndex; i < 6; i++) {
        htmlDayTag += `<li class="inactive">${i - lastDayIndex + 1}</li>`
    }

    document.querySelector(".right .current-date").innerHTML = `${months[currentMonth]} ${currentYear}`
    document.querySelector(".right .days").innerHTML = htmlDayTag;
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
    }
    if (busyDays.filter(t => compareDays(day, t)).length !== 0) {
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
}

function setModalListeners() {
    modal.addEventListener("keydown", function(event) {
        if (event.keyCode === 27) {
            document.querySelector(".modal .events-of-day").innerHTML = "";
            modal.style.display = "none";
        }
    });

    span.onclick = () => {
        modal.style.display = "none";
        document.querySelector(".modal .events-of-day").innerHTML = "";
    };

    window.onclick = function(event) {
        if (event.target.classList.contains("modal")) {
            event.target.style.display = "none";
            if (event.target == modal) {
                document.querySelector(".modal .events-of-day").innerHTML = "";
            }
        }
    };
}

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

// find the modal and set the listeners for exit it
const modal = document.querySelector("#calendar-events-modal");
const span = document.querySelector("#calendar-events-modal span");
setModalListeners();

// finally, render the calendar and fill all the events
const events = [];

fetchEvents().then(() => renderCalendar());




