import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { ArticoloComponent } from './articolo/articolo.component';
import { FormsModule } from '@angular/forms';
import { ArticoloFormComponent } from './articolo-form/articolo-form.component';

@NgModule({
  declarations: [AppComponent, ArticoloComponent, ArticoloFormComponent],
  imports: [BrowserModule, AppRoutingModule, FormsModule],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
