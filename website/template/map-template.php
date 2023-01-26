<div class="wrapper">
    <div class="city-form">
        <div class="search-wrap">
          <input type="text" name="location-search" id="location-search" placeholder="Cerca un luogo..." title="Inserisci il luogo" />
          <div class="range-selector">
            <label for="radius-range">Seleziona Raggio</label>
            <div class="range">
              <input type="range" min="5" max="50" step="5" id="radius-range" value="10" oninput="this.nextElementSibling.value = this.value"/>
              <output>10</output>
            </div>
          </div>
        </div>
        <label for="search-button"><button type="button" id="search-button" name="search-button" class="btn btn-primary" onclick="osmSearch()">Cerca</button></label>
    </div>
    <div id="mapdiv" style="width: 100%; height: 100%;"></div>
    <div id="popup" class="ol-popup">
        <a href="#" id="popup-closer" class="ol-popup-closer" title="popup-closer"></a>
        <div id="popup-content"></div>
    </div>
</div>

