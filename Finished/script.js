let activeFieldId = 'user-id'; // Default active field

window.onload = function() {
    startClock();
};

function startClock() {
    setInterval(function() {
        const now = new Date();
        const clockElement = document.getElementById('real-time-clock');
        clockElement.innerHTML = now.toLocaleTimeString();
    }, 1000);
}

function setActiveField(fieldId) {
    activeFieldId = fieldId;
}

function enterNumber(number) {
    const activeField = document.getElementById(activeFieldId);
    activeField.value += number; // Append number to the current value
}

function deleteLastChar() {
    const activeField = document.getElementById(activeFieldId);
    activeField.value = activeField.value.slice(0, -1);
}

function clearFields() {
    document.getElementById('user-id').value = '';
    document.getElementById('password').value = '';
}
