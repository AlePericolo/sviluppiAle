import { Component } from '@angular/core';
import { ArticoliService } from './articoli.service';

@Component({
  selector: 'main',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  providers: [ArticoliService]
})

export class AppComponent {
  
  title = 'ELENCO ARTICOLI ';

  elencoArticoli;

  constructor(private articoliService: ArticoliService) {
      this.elencoArticoli = articoliService.getArticoli();
    };

  addArticolo(articolo){
    this.articoliService.addArticoli(articolo);
  }

}
