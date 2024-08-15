function searchDoc() {
    const searchValue = document.getElementById("doc").value.trim().toLowerCase();
    const filmCards = document.querySelectorAll('.card');

    filmCards.forEach(card => {
        const title = card.querySelector('.content').textContent.toLowerCase();

        if (title.includes(searchValue)) {
            card.style.display = ""; 
        } else {
            card.style.display = "none"; 
        }
    });
}





