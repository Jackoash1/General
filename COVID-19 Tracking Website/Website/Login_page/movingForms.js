let boxes = document.getElementsByClassName("loginbox");
let toLogin = document.getElementById("toLoginFormBtn");
let toRegister = document.getElementById("toRegisterFormBtn");

window.addEventListener("DOMContentLoaded", () => {
  boxes[0].style.opacity = 1;
  boxes[1].style.transform = "translateX(45vw)";

  toRegister.addEventListener("click", displayRegister);
})

function displayRegister() {
  boxes[0].style.opacity = 0;
  boxes[0].style.transform = "translateX(-45vw)";

  boxes[1].style.opacity = 1;
  boxes[1].style.transform = "translateX(0px)";

  toRegister.removeEventListener("click", displayRegister);
  toLogin.addEventListener("click", displayLogin);
}

function displayLogin() {
  boxes[1].style.opacity = 0;
  boxes[1].style.transform = "translateX(45vw)";

  boxes[0].style.opacity = 1;
  boxes[0].style.transform = "translateX(0px)";

  toRegister.addEventListener("click", displayRegister);
  toLogin.removeEventListener("click", displayLogin);
}