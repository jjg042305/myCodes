function displayTime() {
    const d = new Date();
    const dateTimeString = d.toLocaleString();

    document.getElementById("date").innerHTML = dateTimeString;
}
displayTime();
setInterval(displayTime, 1000);