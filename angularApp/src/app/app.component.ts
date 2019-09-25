import { Component, OnInit } from '@angular/core';
import {ArticoloComponent} from './articolo/articolo.component'
import { Articolo } from './articolo/articolo';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  
  title = 'angularApp';

  articolo1;

  constructor() {

      this.articolo1 = new Articolo()

      console.log(this.articolo1);

      this.articolo1.titolo = "Articolo 1 - Angular";
      this.articolo1.autore = "Alessandro Pericolo";
      this.articolo1.testo = "Creato articolo 1";
      this.articolo1.pagine = 100,

      console.log(this.articolo1);
    };

}
