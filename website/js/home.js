function showForm() {
  window.location.href = "post-creation.php"
}

function followUser(user, followed) {
  console.log(user + " " + followed);
  // follow went ok
  document.querySelector(".right li.user-suggestion.usr-"+followed + " input").value = "✔️";
  document.querySelector(".right li.user-suggestion.usr-"+followed).classList.add("disappearing-card");
  // asynchronous :)
  setTimeout(() => document.querySelector(".right li.user-suggestion.usr-"+followed).remove(), 1000);
}

function menuItemSelectedEffect() {
    const menuItems = document.querySelectorAll(".menu-item");

    const changeAcriveItem = () => {
        menuItems.forEach(item => {
            item.classList.remove('active');
        })
    }

    menuItems.forEach(item => {
        item.addEventListener('click', () => {
            changeAcriveItem();
            item.classList.add('active');
        })
    })
}

// selected effect on left menu item
menuItemSelectedEffect();


