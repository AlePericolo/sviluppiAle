import { Component, Input, Output, EventEmitter } from '@angular/core';
import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { Articolo} from '../articolo/articolo';

@Component({
  selector: 'gestione-articolo',
  templateUrl: './gestione-articolo.component.html',
  styleUrls: ['./gestione-articolo.component.css']
})

export class ArticoloFormComponent {

  @Input() articolo: Articolo;

  articoloForm: FormGroup
  //@Output() submit = new EventEmitter<Articolo>();
  
  constructor(fb: FormBuilder) { 
    
    this.articoloForm = fb.group({
      titolo: ["", [Validators.required]],
      autore: ["",  [Validators.required]],
      testo: ["", [Validators.required]],
      pagine: [undefined, [Validators.required]]
    });
    
    this.articoloForm.valueChanges.subscribe(value => {
      this.articolo.titolo = value.titolo;
      this.articolo.autore = value.autore;
      this.articolo.testo = value.testo;
      this.articolo.pagine = value.pagine;
    });

  }

  ngOnInit() {
    console.log(this.articolo);
  }

  /*
  salvaArticolo(){
    console.log(this.newArticolo);
    this.submit.emit(this.newArticolo)
    this.newArticolo = new Articolo();
  }
  */

  visualizzaArticolo() {
    console.log(this.articoloForm.value);
 }

}
