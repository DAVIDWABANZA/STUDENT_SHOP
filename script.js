let panier = [];
let total = 0;
function ajouterAuPanier(nom, prix) {
    panier.push({ nom: nom, prix: prix });
    afficherPanier();
}
function afficherPanier() {
    const liste = document.getElementById("liste-panier");
    const totalElement = document.getElementById("total");
    liste.innerHTML = "";
    total = 0;
    panier.forEach((produit, index) => {
        total += produit.prix;
        const element = document.createElement("li");
        element.textContent =
            `${produit.nom} — ${produit.prix.toLocaleString("fr-FR")} FC `;
        const bouton = document.createElement("button");
        bouton.textContent = "Retirer";
        bouton.style.marginLeft = "10px";
        bouton.onclick = () => {
            panier.splice(index, 1);
            afficherPanier();
        };
        element.appendChild(bouton);
        liste.appendChild(element);
    });
    totalElement.textContent =
        total.toLocaleString("fr-FR") + " FC";
}