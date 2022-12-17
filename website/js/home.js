function showForm() {
  window.location.href = "post-creation.php"
}

document.getElementById("create-post-xl")
  .addEventListener('click', () => showForm());
document.getElementById("create-post-sm")
  .addEventListener('click', () => showForm());
