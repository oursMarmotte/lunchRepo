import Route from "./Route.js";

export const allRoutes= [
    new Route("/","Accueil","/frontend/home.php"),
    new Route("/galerie","La galerie","/frontend/galerie.php"),
    new Route("/reserver","Réserver","/frontend/reserver.php"),
];

export const websitename ="Mon restaurant du coin";