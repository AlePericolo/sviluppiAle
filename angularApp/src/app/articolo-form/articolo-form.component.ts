import { Component, Output, EventEmitter } from '@angular/core';
import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { Articolo} from '../articolo/articolo';

@Component({
  selector: 'articolo-form',
  templateUrl: './articolo-form.component.html',
  styleUrls: ['./articolo-form.component.css']
})

export class ArticoloFormComponent {

  newArticolo: Articolo
  articoloForm: FormGroup
  @Output() submit = new EventEmitter<Articolo>();
  
  constructor(fb: FormBuilder) { 
    
    this.newArticolo = new Articolo();

    this.articoloForm = fb.group({
      titolo: ["", [Validators.required]],
      autore: ["",  [Validators.required]],
      testo: ["", [Validators.required]],
      pagine: [undefined, [Validators.required]]
    });
    
    this.articoloForm.valueChanges.subscribe(value => {
      this.newArticolo.titolo = value.titolo;
      this.newArticolo.autore = value.autore;
      this.newArticolo.testo = value.testo;
      this.newArticolo.pagine = value.pagine;
    });

  }

  ngOnInit() {
    console.log(this.newArticolo);
  }

  salvaArticolo(){
    console.log(this.newArticolo);
    this.submit.emit(this.newArticolo)
    this.newArticolo = new Articolo();
  }

  visualizzaArticolo() {
    console.log(this.articoloForm.value);
 }

}
