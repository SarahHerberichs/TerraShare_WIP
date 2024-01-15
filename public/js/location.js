document.addEventListener("DOMContentLoaded", function () {
  var departmentsSelect = document.getElementById("departments");
  var citySearchInput = document.getElementById("citySearch");
  var citiesList = document.getElementById("citiesList");

  function updateCitiesList(departmentNumber, searchQuery = "") {
    //Pour execution de requete asynchrone
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      //Le statut est à 4 lorsquela requête est terminée
      if (xhr.readyState === 4) {
        //le status est 200 si la requête a abouti
        if (xhr.status === 200) {
          //Parse la réponse JSON de la requete
          var citiesData = JSON.parse(xhr.responseText);
          citiesList.innerHTML = "";
          //Pour chaque city crée une div qui contiendra une span avec le city.name
          citiesData.forEach(function (city) {
            var cityContainer = document.createElement("div");
            cityContainer.className = "city-item";

            var cityName = document.createElement("span");
            cityName.textContent = city.name + "-" + city.zipcode;

            cityContainer.appendChild(cityName);
            //Fonction de redirection au click sur une ville
            cityContainer.addEventListener("click", function () {
              window.location.href = "/create-ad/" + city.id;
            });
            //Injection dans la div vide initiale des villes
            citiesList.appendChild(cityContainer);
          });
        } else {
          alert("Erreur de requête AJAX : " + xhr.status);
        }
      }
    };

    var url = "/get-cities/" + departmentNumber;
    //vérifie que le parametre passé à la fonction n'est pas vide, si pas vide l'ajoute à l'url en format encodé pour transmettre la chaine de recherche au server
    if (searchQuery !== "") {
      url += "?search=" + encodeURIComponent(searchQuery);
    }
    ///ouverture et envoi de la requete
    xhr.open("GET", url, true);
    xhr.send();
  }

  departmentsSelect.addEventListener("change", function () {
    var selectedDepartmentNumber = departmentsSelect.value;
    updateCitiesList(selectedDepartmentNumber, citySearchInput.value);
  });

  citySearchInput.addEventListener("input", function () {
    var selectedDepartmentNumber = departmentsSelect.value;
    updateCitiesList(selectedDepartmentNumber, citySearchInput.value);
  });
});
