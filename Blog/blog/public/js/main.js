
console.log("click")
const datalist = document.getElementById("searchList")
const search=document.getElementById('search')
//creation d'options
const option = document.createElement("option");

search.addEventListener("keyup", (e)=>{

fetch('apiSearch?q='+e.target.value)
.then(response => response.json())
.then(title =>{
    for(let i=0; i<title.length ; i++){
         option.value=title[i].title;
         datalist.append(option);
    }
    
  });
});