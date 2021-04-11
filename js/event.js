function setTab(self, name) {
    const ul = self.parentElement.parentElement;
    const about = document.getElementById("about");
    const schedule = document.getElementById("schedule");
    const restaurants = document.getElementById("restaurants");

    switch (name) {
        case "about":
            about.style.display = "block";
            if (schedule) schedule.style.display = "none";
            if (restaurants) restaurants.style.display = "none";
            unsetHash("day");
            break;
        case "schedule":
            about.style.display = "none";
            schedule.style.display = "block";
            initDay();
            break;
        case "restaurants":
            about.style.display = "none";
            restaurants.style.display = "block";
            unsetHash("day");
            break;
    }

    for (const child of ul.children) {
        child.classList.remove("active");
    }

    self.parentElement.classList.add("active");
    setHash("tab", name);
}

switch (getHash("tab")) {
    case undefined:
        setTab(document.getElementById("about-tab"), "about");
        break;
    default:
        setTab(document.getElementById(`${getHash("tab")}-tab`), getHash("tab"));
        break;
}
