document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".wrapper-senha").forEach(wrapper => {
    const input = wrapper.querySelector("input");
    const icon = wrapper.querySelector(".toggle");

    icon.addEventListener("click", () => {
      if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
      } else {
        input.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
      }
    });
  });
});
