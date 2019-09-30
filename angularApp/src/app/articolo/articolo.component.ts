import { Component, Input} from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Articolo }  from './articolo'
import { ArticoliService } from '../articoli.service';


@Component({
  selector: 'articolo',
  templateUrl: './articolo.component.html',
  styleUrls: ['./articolo.component.css']
})

export class ArticoloComponent {

  @Input() elencoArticoli: Articolo[];
  articoloForm: FormGroup
  articolo: Articolo

  constructor(formBuilder: FormBuilder, private articoliService: ArticoliService) {
  
    //definisco la form builder
    this.articoloForm = formBuilder.group({
      titolo: [null, [Validators.required]],
      autore: [null,  [Validators.required]],
      testo: [null, [Validators.required]],
      pagine: [null, [Validators.required]],
      likes: [null, [Validators.required]]
    });

    //definisco il binding sulla form
    this.articoloForm.valueChanges.subscribe(value => {
      value.titolo ? this.articolo.titolo = value.titolo : null;
      value.autore ? this.articolo.autore = value.autore : null;
      value.testo ? this.articolo.testo = value.testo : null;
      value.pagine ? this.articolo.pagine = value.pagine : null;
      value.likes ? this.articolo.likes = value.likes : 0;
    });
  }

  gestisciArticolo(a){

    this.articolo = new Articolo();
  
    if(a){
      this.articolo = a;
    }
    
    //setto sulla form l'articolo (nuovo/passato)
    this.articoloForm.setValue(this.articolo);
  
  }

  salvaArticolo(){
    this.articoliService.addArticoli(this.articolo);
  }

}