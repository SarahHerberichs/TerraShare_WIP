document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("load-more-btn")
    .addEventListener("click", function () {
      //Recup du nb d'annonce affichée à l'initial (limité à 12 en html)
      var offset = document.querySelectorAll(".each-ad").length;
      var xhr = new XMLHttpRequest();
      //Requete pour executer le chargement de plus d'annonces, passage du offset
      xhr.open("GET", "/load-more-ads?offset=" + offset, true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
          var newAds = JSON.parse(xhr.responseText);
          newAds.forEach(function (ad) {
            //Pour chaque nouvelle annonce trouvée, ajout des éléments (équivalent aux initiaux) pour injecter les prochaines ad
            var adListItem = document.createElement("li");
            adListItem.classList.add("each-ad");

            var adLink = document.createElement("a");
            adLink.href = "{{ path('app_consult_ad_by_id', {'id': ad.id}) }}";
            adLink.classList.add("each-ad");

            var textContent = document.createElement("div");
            textContent.classList.add("text-content");

            var h3 = document.createElement("h3");
            h3.textContent = ad.title;

            var cityName = document.createElement("p");
            cityName.classList.add("city-name");
            cityName.textContent =
              "à " +
              ad.city.name.charAt(0).toUpperCase() +
              ad.city.name.slice(1);

            var type = document.createElement("p");
            type.classList.add("type");
            type.textContent = "Type : " + (ad.type ? ad.type : "");

            var status = document.createElement("p");
            status.classList.add("status");
            status.textContent = "Status : " + (ad.status ? ad.status : "");

            var transaction = document.createElement("p");
            transaction.classList.add("transaction");
            transaction.textContent =
              "Transaction : " + (ad.transaction ? ad.transaction : "");

            var price = document.createElement("p");
            price.classList.add("price");
            price.textContent = "Prix : " + (ad.price ? ad.price : "") + " €";

            var imgContainer = document.createElement("div");
            imgContainer.classList.add("img-container", "img-list");

            ad.photos.forEach(function (photo, index) {
              if (index === 0) {
                var img = document.createElement("img");
                img.src = "/img/" + photo;
                img.alt = ad.title;
                img.classList.add(
                  "main-img-ad",
                  "img-list",
                  "card-img-top",
                  "mt-3"
                );
                imgContainer.appendChild(img);
              }
            });
            //Injection des elements HTML au DOM
            textContent.appendChild(h3);
            textContent.appendChild(cityName);
            textContent.appendChild(type);
            textContent.appendChild(status);
            textContent.appendChild(transaction);
            textContent.appendChild(price);

            adLink.appendChild(textContent);
            adLink.appendChild(imgContainer);
            adListItem.appendChild(adLink);
            //Ajout la nouvelle annonce à la liste existante
            document.querySelector(".list-ads").appendChild(adListItem);
          });
        }
      };
      xhr.send();
    });
});
