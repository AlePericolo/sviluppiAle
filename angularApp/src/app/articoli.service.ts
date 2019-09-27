import { Injectable } from '@angular/core';
import { Articolo} from "./articolo/articolo"

@Injectable({
  providedIn: 'root'
})

export class ArticoliService {

  private elencoArticoli: Articolo[];

  constructor() {

    //preset articoli nell'array
    this.elencoArticoli = [
          {
            titolo: "Titolo A",
            autore: "Alessandro Pericolo",
            testo: "Creazione primo articolo",
            pagine: 10,
            likes: 1
          },
          {
            titolo: "Titolo B",
            autore: "Mauro Giudici",
            testo: "Creazione secondo articolo",
            pagine: 50,
            likes: 5
          },
          {
            titolo: "Titolo C",
            autore: "Marco Sciarra",
            testo: "Creazione terzo articolo",
            pagine: 20,
            likes: 3
          }
    ];
    //this.elencoArticoli = [];
   }

   //funzione che ritorna l'array degli articoli
   getArticoli(): Articolo[]{
     return this.elencoArticoli;
   }

   //funzione per aggiungere un elemento all'array degli articoli
   addArticoli(articolo){
     this.elencoArticoli.push(articolo);
   }
}
