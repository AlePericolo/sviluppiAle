import { Component, Output, EventEmitter } from '@angular/core';
import { Articolo} from '../articolo/articolo';

@Component({
  selector: 'articolo-form',
  templateUrl: './articolo-form.component.html',
  styleUrls: ['./articolo-form.component.css']
})
export class ArticoloFormComponent {

  @Output() submit = new EventEmitter<Articolo>();
  newArticolo: Articolo

  constructor() { 
    this.newArticolo = new Articolo();
  }

  ngOnInit() {
    console.log(this.newArticolo);
  }

  salvaArticolo(){
    console.log(this.newArticolo);
    this.submit.emit(this.newArticolo)
    this.newArticolo = new Articolo();
  }

}
