var buttonShow = document.getElementById("btn-show");
var buttonHidde = document.getElementById("btn-hidde");
var modal = document.getElementById("modal");

buttonShow.addEventListener("click", show);
buttonHidde.addEventListener("click", hidde);

function show() {
    modal.style.display = "block";
}

function hidde() {
    modal.style.display = "none";
}