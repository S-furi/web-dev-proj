const resultField = document.querySelector(".main form .location-search>span");

function osmSearch() {
    const value = document.querySelector(".main form input#location").value;
    if (value.length> 3 ){
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
                    resultField.innerText = "done";
                } else {
                    resultField.innerText = "cancel";
                }
            }).catch(() => resultField.innerText = "cancel");

        document.querySelector(".main form .location-search button").disabled = true;
        setTimeout(restoreResearch, 6000);
    }
}

function restoreResearch() {
    document.querySelector(".main form .location-search button").disabled = false;
    console.log("ENABLED");
}

