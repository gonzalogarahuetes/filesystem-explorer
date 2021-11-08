const openEl = document.querySelector("[data-open]");
const closeEl = document.querySelector("[data-close]");
const isVisible = "is-visible";

openEl.addEventListener("click", function () {
  const modalId = this.dataset.open;
  document.getElementById(modalId).classList.add(isVisible);
});

closeEl.addEventListener("click", function () {
  this.parentElement.parentElement.parentElement.classList.remove(isVisible);
});

document.addEventListener("click", (e) => {
  if (e.target == document.querySelector(".modal.is-visible")) {
    document.querySelector(".modal.is-visible").classList.remove(isVisible);
  }
});

document.addEventListener("keyup", (e) => {
  // if we press the ESC
  if (e.key == "Escape" && document.querySelector(".modal.is-visible")) {
    document.querySelector(".modal.is-visible").classList.remove(isVisible);
  }
});
