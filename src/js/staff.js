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
let inpt_p_email = document.getElementById('pid');
inpt_p_email.addEventListener("change", (event) => {
    let inpt_cards = document.querySelectorAll('input.patient_email') ;
    inpt_cards.forEach((inpt) =>{
        inpt.value = inpt_p_email.value;

    })
  });