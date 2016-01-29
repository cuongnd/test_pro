

/**
 Cette methode cree un evenement souris sur un element de l'arbre DOM.
 Util pour simuler un comportement des objets javascript interactifs.
 @param e Element de l'arbre DOM où l'on doit déclancher l'evenement souris
 @param eventType le type de l'evenement souris : onclick, onmousemove
 @param x position de la souris en abscisse
 @param y position de la souris en ordonnées
 @param button le bouton de la souris emetteur de l'evenement pour un click, mouse pressed, mouse released
 @param click le nombre de click
*/

 function fireMouseEvent (e,eventType, x,y, button, click) {
    if ( document.createEvent) {
  	  var evObj = document.createEvent('MouseEvents');
	  evObj.initMouseEvent(
	  eventType,    // le type d'evenement souris
	  true,       // est-ce que l'evenement doit se propager (bubbling)?
	  true,       // est-ce que le défaut pour cet evenement peut être annulé?
	  document.defaultView,     // l' 'AbstractView' pour cet evenement
	  click,          // details -- Pour les evenements click, le nombre de clicks
	  x,          // screenX
	  y,          // screenY
	  x,          // clientX
	  y,          // clientY
   	  false,      // est-ce que la touche Ctrl est pressee?
	  false,      // est-ce que la touche Alt est pressee?
	  false,      // est-ce que la touche Shift est pressee?
	  false,      // est-ce que la touche Meta est pressee?
	  button,          // quel est le bouton presse
	  e      // l'element source de cet evenement
	 );


	 e.dispatchEvent(evObj);
 	 } else {
	 var evObj = document.createEventObject();

	 evObj.screenX = x;
	 evObj.screenY = y;
	 evObj.clientX = x;
	 evObj.clientY = y;
	 evObj.button = button;
     evObj.detail = click;
	 e.fireEvent("on" +eventType, evObj);
	 }
}

/*
verifie que ce fichier est bien charge dans un browser,
methode obsolete pour une version superieur a 1.3 de GWT
*/
function autoEventsIsOk() {
 //ne fait rien
}
