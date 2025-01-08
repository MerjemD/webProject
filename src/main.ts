import { bootstrapApplication } from '@angular/platform-browser';
import { AppComponent } from './app/app.component';
import { provideRouter } from '@angular/router';
import { routes } from './app/app.routes';
import { provideHttpClient } from '@angular/common/http';  // Ispravno importovanje

bootstrapApplication(AppComponent, {
  providers: [
    provideRouter(routes), // Povezivanje ruta
    provideHttpClient (),  // Ispravno korišćenje withFetch() bez zagrada unutar funkcije
  ],
}).catch((err) => console.error(err));
