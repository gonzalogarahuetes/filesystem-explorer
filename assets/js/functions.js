// Display and close file modal
const uploadFile = document.getElementById("btn__upload");
const modalFile = document.getElementById("modal__file");
const modalFileClose = document.querySelector("#button-close-file");
const closeModalFileBtn = document.querySelector("#cancel-modal-file");

uploadFile.addEventListener("click", openFileModal);
modalFileClose.addEventListener("click", closeFileModal);
closeModalFileBtn.addEventListener("click", closeFileModal);

function openFileModal() {
  modalFile.style.display = "block";
}

function closeFileModal() {
  modalFile.style.display = "none";
}
