(function($) { "use strict";

document.addEventListener('DOMContentLoaded', function () {

  

        var map = L.map('mapa').setView([-34.679276, -58.489494], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
    
        L.marker([-34.679276, -58.489494]).addTo(map)
            .bindPopup('BitFlow<br> Argentina')
            .openPopup();

    });
})(jQuery);