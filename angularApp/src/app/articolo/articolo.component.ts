import { Component, OnInit, Input, NgModule} from '@angular/core';
import { Articolo }  from './articolo'


@Component({
  selector: 'articolo',
  templateUrl: './articolo.component.html',
  styleUrls: ['./articolo.component.css']
})



export class ArticoloComponent implements OnInit {

  @Input() articolo1: Articolo;
  
  articolo2: Articolo;  
  
  articolo3: {
    titolo:string;
    autore:string;
    pagine: number
  };

  constructor() {

      this.articolo2 = {
        titolo: "Articolo 2 - Angular",
        autore: "Mauro Giudici",
        testo:  "Creato articolo 2",
        pagine: 100,
        likes: 0
      };
   }

  ngOnInit() {

    console.log(this.articolo1);

      this.articolo3 = {
        titolo: "Articolo 3 - Angular",
        autore: "Marco Sciarra",
        pagine: 10
      };
  }

  incrementaApprezzamenti(a) {
    a.likes = a.likes + 1;
    alert("Grazie per aver espresso il tuo apprezzamento per l'articolo: " + a.titolo + " \n Il numero di apprezzamenti raggiunti è " + a.likes);
  }

}