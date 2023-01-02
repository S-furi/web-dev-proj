<div class="wrapper">
    <div class="city-form">
        <label for="location-search"><input type="text" name="location-search" id="location-search" placeholder="Cerca un luogo..." /></label>
        <label for="search-button"><button type="button" id="search-button" name="search-button" class="btn btn-primary" onclick="osmSearch()">Cerca</button></label>
    </div>
    <div id="mapdiv" style="width: 100%; height: 100%;"></div>
    <div id="popup" class="ol-popup">
        <a href="#" id="popup-closer" class="ol-popup-closer"></a>
        <div id="popup-content"></div>
    </div>
</div>

