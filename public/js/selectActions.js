document.addEventListener("DOMContentLoaded", function () {
  const selectedType = document.getElementById("ads_type");
  //Pour ajout annonce: ads_type
  const selectedTransaction = document.getElementById("ads_transaction");
  //Pour ajout annonce : ads_transaction
  const initialTypeValue = selectedType.value;
  const initialTransactionValue = selectedTransaction.value;
  //VISIBLE INVISIBLE SELON TYPE SELECTIONNE OU NON
  if (initialTypeValue > 1) {
    selectedTransaction.classList.add("visible");
    selectedTransaction.classList.remove("invisible");
  } else {
    selectedTransaction.classList.remove("visible");
    selectedTransaction.classList.add("invisible");
  }

  //A CHAQUE Changement de type,
  //Si UN type  est selectionné, affiche les transaction associées
  //en fetchant dans (controlleur typetransaction)
  selectedType.addEventListener("change", function () {
    const selectedTypeId = this.value;
    //Visible Invisible + fetch pour afficher les choix de transact associé au type
    if (selectedTypeId > 0) {
      selectedTransaction.classList.add("visible");
      selectedTransaction.classList.remove("invisible");
      //Recupere transactions a afficher selon selection du type
      fetch("/get-transactions/" + selectedTypeId)
        .then((response) => response.json())
        .then((data) => {
          // type transaction remis à zéro
          selectedTransaction.innerHTML = "";
          const emptyOption = document.createElement("option");
          emptyOption.value = "";
          emptyOption.text = "Toutes les transactions";
          selectedTransaction.appendChild(emptyOption);

          // Ajout des nouvelles transactions
          data.forEach((transaction) => {
            const option = document.createElement("option");
            option.value = transaction.id;
            option.text = transaction.name;
            selectedTransaction.appendChild(option);
          });

          // Restaure la valeur initiale si elle existe
          if (initialTransactionValue) {
            selectedTransaction.value = initialTransactionValue;
          }
        })
        .catch((error) => console.error("Error fetching transactions:", error));
    } else {
      selectedTransaction.classList.remove("visible");
      selectedTransaction.classList.add("invisible");
      console.log("Nothing selected");
    }
  });

  // Restaure la valeur initiale du type après la soumission du formulaire
  if (initialTypeValue) {
    selectedType.value = initialTypeValue;
    // Déclencher l'événement de changement manuellement pour mettre à jour les transactions
    selectedType.dispatchEvent(new Event("change"));
  }
});
