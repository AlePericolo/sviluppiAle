import { Component, Input} from '@angular/core';
import { Articolo }  from './articolo'


@Component({
  selector: 'articolo',
  templateUrl: './articolo.component.html',
  styleUrls: ['./articolo.component.css']
})

export class ArticoloComponent {

  @Input() elencoArticoli: Articolo[];
  mostraGestioneArticolo = false;
  articolo: Articolo

  constructor() {
  }

  gestisciArticolo(param){
    
    if(param){
      this.articolo = param
    }else{
      this.articolo = new Articolo()
    }
    
    this.mostraGestioneArticolo = true;
  }

}