// Calculator functionality
function appendToDisplay(value) {
    document.getElementById('calcDisplay').value += value;
}

function calculateResult() {
    const display = document.getElementById('calcDisplay');
    try {
        display.value = eval(display.value);
    } catch (e) {
        display.value = 'Error';
    }
}

function clearDisplay() {
    document.getElementById('calcDisplay').value = '';
}

// Simple calendar display
function displaySimpleCalendar() {
    const calendarDiv = document.getElementById('calendar');
    const date = new Date();
    const month = date.getMonth();
    const year = date.getFullYear();

    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDay = new Date(year, month, 1).getDay();

    let calendarHtml = `<div class="month-header">${date.toLocaleString('default', { month: 'long' })} ${year}</div>`;
    calendarHtml += `<div class="day-names">
                        <span>Sun</span>
                        <span>Mon</span>
                        <span>Tue</span>
                        <span>Wed</span>
                        <span>Thu</span>
                        <span>Fri</span>
                        <span>Sat</span>
                      </div>`;
    calendarHtml += '<table><tr>';

    for (let i = 0; i < firstDay; i++) {
        calendarHtml += '<td></td>';
    }

    for (let day = 1; day <= daysInMonth; day++) {
        calendarHtml += `<td>${day}</td>`;
        if ((day + firstDay) % 7 === 0) {
            calendarHtml += '</tr><tr>';
        }
    }

    calendarHtml += '</tr></table>';
    calendarDiv.innerHTML = calendarHtml;
}

// Modal management
const calculatorBtn = document.getElementById('calculatorBtn');
const calendarBtn = document.getElementById('calendarBtn');
const calculatorModal = document.getElementById('calculatorModal');
const calendarModal = document.getElementById('calendarModal');
const closeCalculator = document.getElementById('closeCalculator');
const closeCalendar = document.getElementById('closeCalendar');

calculatorBtn.onclick = function() {
    clearDisplay();
    calculatorModal.style.display = "block";
};

calendarBtn.onclick = function() {
    displaySimpleCalendar();
    calendarModal.style.display = "block";
};

closeCalculator.onclick = function() {
    calculatorModal.style.display = "none";
};

closeCalendar.onclick = function() {
    calendarModal.style.display = "none";
};

window.onclick = function(event) {
    if (event.target == calculatorModal) {
        calculatorModal.style.display = "none";
    } else if (event.target == calendarModal) {
        calendarModal.style.display = "none";
    }
};
