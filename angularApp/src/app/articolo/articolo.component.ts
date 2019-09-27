import { Component, OnInit, Input, NgModule} from '@angular/core';
import { Articolo }  from './articolo'
import { ArrayType } from '@angular/compiler';


@Component({
  selector: 'articolo',
  templateUrl: './articolo.component.html',
  styleUrls: ['./articolo.component.css']
})

export class ArticoloComponent implements OnInit {

  @Input() articolo1: Articolo;
  
  articolo2: Articolo;  

  elencoArticoli: Articolo[] = [];
    
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
  }

  incrementaApprezzamenti(a) {
    a.likes = a.likes + 1;
    alert("Grazie per aver espresso il tuo apprezzamento per l'articolo: " + a.titolo + " \n Il numero di apprezzamenti raggiunti è " + a.likes);
  }

  addArticolo(articolo) {
    console.log(articolo)
    this.elencoArticoli.push(articolo)
  }

}