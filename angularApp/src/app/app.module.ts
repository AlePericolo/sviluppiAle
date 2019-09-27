import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { ArticoloComponent } from './articolo/articolo.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ArticoloFormComponent } from './gestione-articolo/gestione-articolo.component';

@NgModule({
  declarations: [AppComponent, ArticoloComponent, ArticoloFormComponent],
  imports: [BrowserModule, AppRoutingModule, FormsModule, ReactiveFormsModule],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
