document.addEventListener("DOMContentLoaded", () => {
  const dltLinks = document.getElementsByClassName("delete");
  for (let i = 0; i < dltLinks.length; i++) {
    dltLinks[i].addEventListener("click", (event) => {
      const confirmation = confirm("Are you sure you want to delete this item?");
      if (!confirmation) {
        event.preventDefault();
      }
    });
  }
})