// document.addEventListener("DOMContentLoaded", function () {
//   var departmentsSelect = document.getElementById("departments");
//   var citiesList = document.getElementById("citiesList");

//   departmentsSelect.addEventListener("change", function () {
//     var selectedDepartmentNumber = departmentsSelect.value;

//     var xhr = new XMLHttpRequest();
//     xhr.onreadystatechange = function () {
//       if (xhr.readyState === 4) {
//         if (xhr.status === 200) {
//           // Parsez la réponse JSON
//           var citiesData = JSON.parse(xhr.responseText);

//           // Mettez à jour le contenu de la liste des villes
//           citiesList.innerHTML = "";

//           citiesData.forEach(function (city) {
//             // Ajoutez chaque ville à la liste
//             var listItem = document.createElement("li");
//             listItem.textContent = city.name;

//             var listItem = document.createElement("li");
//             listItem.textContent = city.name;

//             // Ajoutez un gestionnaire d'événements pour le clic
//             listItem.addEventListener("click", function () {
//               // Affichez l'ID de la ville dans la console
//               window.location.href = "/create-ad/" + city.id; // Assurez-vous que votre objet City a une propriété 'id'
//             });

//             citiesList.appendChild(listItem);
//           });
//         } else {
//           alert("Erreur de requête AJAX : " + xhr.status);
//         }
//       }
//     };

//     xhr.open("GET", "/get-cities/" + selectedDepartmentNumber, true);
//     xhr.send();
//   });
// });
