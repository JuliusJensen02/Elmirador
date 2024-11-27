let popups = document.querySelectorAll(".seasonPopup");
console.log(popups);
for (const popup of popups) {
    let popupBtn = popup.previousElementSibling;
    popupBtn.addEventListener('click', function () {
        popup.classList.add("active");
    });

    let closeBtn = popup.querySelector(".popup-top button");
    closeBtn.addEventListener('click', function () {
        popup.classList.remove("active");
    });

    popup.addEventListener('click', function (e) {
        if(e.target === popup){
            popup.classList.remove("active");
        }
    });
}
