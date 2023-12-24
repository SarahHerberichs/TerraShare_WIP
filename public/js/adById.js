document.addEventListener("DOMContentLoaded", function () {
  var imageContainer = document.getElementById("imageContainer");
  var images = imageContainer.getElementsByTagName("img");
  var currentIndex = 0;

  // Cacher toutes les images sauf la première
  for (var i = 1; i < images.length; i++) {
    images[i].style.display = "none";
  }

  // Ajouter un gestionnaire d'événements pour afficher les images suivantes au clic
  imageContainer.addEventListener("click", function () {
    currentIndex = (currentIndex + 1) % images.length;

    // Cacher toutes les images
    for (var i = 0; i < images.length; i++) {
      images[i].style.display = "none";
    }

    // Afficher l'image actuelle
    images[currentIndex].style.display = "block";
  });
});
