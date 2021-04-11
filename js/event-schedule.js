function setDay(self, day) {
    const ul = self.parentElement.parentElement;
    const body = new FormData();

    body.append("day", day);
    body.append("eventID", eventID);

    fetch("/getDaySchedule.php", {
        method: "POST",
        body,
    }).then(async res => {
        const daySchedule = document.getElementById("daySchedule");
        const header = daySchedule.firstElementChild;
        const text = await res.text();

        daySchedule.removeChild(header);
        daySchedule.innerHTML = text;
        daySchedule.prepend(header);
    });

    for (const child of ul.children) {
        child.classList.remove("active");
    }

    self.parentElement.classList.add("active");

    setHash("day", day);
}
