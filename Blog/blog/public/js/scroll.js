//function permet de d'afficher les  10 articles suivants
let offset=5
let button=document.getElementById("moreArticle");
button.addEventListener("click",onClickBouton);


let container = document.getElementById("posts");
function onClickBouton(){
  fetch('api/'+offset)
  .then(response => response.text())
  .then(posts =>{container.innerHTML += posts
    offset+=5;
    look=false;
  });
}


window.addEventListener("scroll", function () {
  console.log("scroll");
  if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight - window.innerHeight / 3) && !lock) {
      lock = true;
      loadPosts();
  }
});