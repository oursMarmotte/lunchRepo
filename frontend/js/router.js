import Route from "./Route.js";
import { allRoutes,websitename } from "./allRoutes.js";


const route404 = new Route("/pageErreur","Page introuvable","/frontend/page404.php");


const getRouteByUrl = (url)=>{

    let currentRoute = null;
    allRoutes.forEach((element)=>{
        if(element.url == url){
            currentRoute = element;
        }
    });

    if(currentRoute !=  null){
        return currentRoute;
    }else{
        return route404;
    }

}



const LoadContentPage = async ()=>{
    const path = window.location.pathname;
    const actualRoute = getRouteByUrl(path);
    const html = await fetch(actualRoute.pathHtml).then((data)=>data.text());
    document.getElementById("main-page").innerHTML = html;

    if(actualRoute.pathJS != ""){
        var scriptag = document.createElement("script");
        scriptag.setAttribute("type","text/javascript");
        scriptag.setAttribute("src",actualRoute.pathJS);
        document.querySelector("body").appendChild(scriptag);

    }
    document.title = actualRoute.title+"-"+websitename;
}

// Fonction pour gérer les événements de routage (clic sur les liens)

const routeEvent = (event)=>{

    event = event || window.event;
    event.preventDefault();
    window.history.pushState({},"",event.target.href);
    LoadContentPage();
};

window.onpopstate = LoadContentPage;
window.route = routeEvent;
LoadContentPage();
