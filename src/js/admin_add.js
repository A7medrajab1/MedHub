let type = document.getElementById("usertype");
let speciality = document.getElementById("speciality");

type.addEventListener('change', function () {
    if (type.value === 'doctor') {
        speciality.removeAttribute('disabled');
    } else {
        speciality.setAttribute('disabled', '');
    }
});