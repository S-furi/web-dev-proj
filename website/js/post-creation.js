const resultField = document.querySelector(".main form .location-search>span");

function locationValidation() {
    const value = document.querySelector(".main form input#location").value;
    if (value.length > 3 ){
        const url = new URL("https://nominatim.openstreetmap.org/search?");

        const osm_params = {
            "q": value,
            "format": "json",
            "limit": 1,
        }

        for (const [key, value] of Object.entries(osm_params)) {
            url.searchParams.append(key, value);
        }

        fetch(url)
            .then(res => res.json())
            .then(res => {
                if(res.length > 0) {
                    registerLocation(res);
                    resultField.innerText = "done";
                } else {
                    resultField.innerText = "cancel";
                }
            }).catch((err) => {
                console.log(err)
                resultField.innerText = "cancel" } );

        document.querySelector(".main form .location-search button").disabled = true;
        setTimeout(restoreResearch, 6000);
    }
}

function registerLocation(locationInfo) {
    locationInfo = locationInfo[0];
    const params = {
        // in some cases, the displayed name is 
        'name': locationInfo["display_name"].split(",")[0],
        'lon': locationInfo['lon'],
        'lat': locationInfo['lat'],
    }

    const formData = new FormData();
    for (const key in params) {
        formData.append(key, params[key]);
    }

    axios.post("api/api-locations.php?action=0", formData)
        .then(res => {
            // injecting the locationId for inserting it's value on posts table
            document.querySelector(`.main input[name="location-id"]`)
                .value = res.data["location"]["locationId"];
        }).catch(err => console.log(err));
}

function restoreResearch() {
    document.querySelector(".main form .location-search button").disabled = false;
}

function inputCharLimitCheck(element, event, limit) {
  if (element.value.length >= limit && event.code !== "Backspace") {
    event.preventDefault();
  }
}

const textArea = document.getElementById("description");
const title = document.getElementById("title");

textArea.addEventListener("keydown", (event) => inputCharLimitCheck(textArea, event, 255));
title.addEventListener("keydown", (event) => inputCharLimitCheck(title, event, 50));


