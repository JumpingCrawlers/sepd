var url_string = window.location.href
var url = new URL(url_string);
var competenciaName = url.searchParams.get("competencia");
console.log(competenciaName)

main()

function main(){
    var titulo = document.querySelector(".title__wrapper")
    titulo.innerHTML = titulo.innerHTML + 
    '<h2 class="main__title">' + competenciaName + '</h2>'
}