// document.addEventListener("DOMContentLoaded", function () {
//   const selectedType = document.getElementById("type");

//   // Votre code JavaScript ici
//   selectedType.addEventListener("change", function () {
//     const selectedTypeId = this.value;
//     console.log(selectedTypeId);
//     if (selectedTypeId > 0) {
//       fetch("/get-transactions/" + selectedTypeId)
//         .then((response) => response.json())
//         .then((data) => {
//           const transactionSelect = document.getElementById("transaction");

//           transaction.innerHTML = "";
//           const Emptyoption = document.createElement("option");
//           Emptyoption.value = "";
//           Emptyoption.text = "Toutes les transactions";
//           transactionSelect.appendChild(Emptyoption);
//           data.forEach((transaction) => {
//             const option = document.createElement("option");
//             option.value = transaction.id;
//             option.text = transaction.name;
//             transactionSelect.appendChild(option);
//           });
//         })
//         .catch((error) => console.error("error fetching transaction", error));
//     } else {
//       console.log("rien de selectionné");
//     }
//   });
// });
document.addEventListener("DOMContentLoaded", function () {
  const reloadBtn = document.getElementById("reload-btn");
  reloadBtn.addEventListener("click", function () {
    location.reload();
  });
  const selectedType = document.getElementById("type");
  const selectedTransaction = document.getElementById("transaction");

  // Enregistrez les valeurs initiales sélectionnées
  const initialTypeValue = selectedType.value;
  const initialTransactionValue = selectedTransaction.value;

  if (initialTypeValue > 1) {
    selectedTransaction.classList.add("visible");
    selectedTransaction.classList.remove("invisible");
  } else {
    selectedTransaction.classList.remove("visible");
    selectedTransaction.classList.add("invisible");
  }
  // Gestionnaire d'événements pour le changement de sélection du type
  selectedType.addEventListener("change", function () {
    const selectedTypeId = this.value;

    if (selectedTypeId > 0) {
      selectedTransaction.classList.add("visible");
      selectedTransaction.classList.remove("invisible");
      fetch("/get-transactions/" + selectedTypeId)
        .then((response) => response.json())
        .then((data) => {
          // Réinitialiser la liste des transactions
          selectedTransaction.innerHTML = "";
          const emptyOption = document.createElement("option");
          emptyOption.value = "";
          emptyOption.text = "Toutes les transactions";
          selectedTransaction.appendChild(emptyOption);

          // Ajouter les nouvelles transactions
          data.forEach((transaction) => {
            const option = document.createElement("option");
            option.value = transaction.id;
            option.text = transaction.name;
            selectedTransaction.appendChild(option);
          });

          // Restaurer la valeur initiale si elle existe
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

  // Restaurer la valeur initiale du type après la soumission du formulaire
  if (initialTypeValue) {
    selectedType.value = initialTypeValue;
    // Déclencher l'événement de changement manuellement pour mettre à jour les transactions
    selectedType.dispatchEvent(new Event("change"));
  }
});
