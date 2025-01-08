import { Component } from '@angular/core';
import { RouterOutlet, RouterModule } from '@angular/router';
import { NavBarComponent } from './nav-bar/nav-bar.component';
import { FooterComponent} from './footer/footer.component';
import { LoginComponent } from './login/login.component';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { RegisterComponent } from './register/register.component';
import { UserCalendarComponent } from './user-calendar/user-calendar.component';
import { AdminCalendarComponent } from './admin-calendar/admin-calendar.component';
import { CommonModule } from '@angular/common';
 import {routes} from './app.routes';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, RouterModule, NavBarComponent, RegisterComponent, FooterComponent, CommonModule,  FormsModule, HttpClientModule],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent {
  title = 'WP';
}
