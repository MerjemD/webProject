import { RouterModule, Routes } from '@angular/router';
import { HomeComponent } from './home/home.component';
import { AboutComponent } from './about/about.component';
import { LoginComponent } from './login/login.component';
import { FormsModule } from '@angular/forms';
import { NgModule } from '@angular/core';
import { HttpClientModule } from '@angular/common/http';
import { provideHttpClient } from '@angular/common/http';
import { UserCalendarComponent } from './user-calendar/user-calendar.component';
import { AdminCalendarComponent } from './admin-calendar/admin-calendar.component';
import { RegisterComponent } from './register/register.component';
import { AuthGuard } from './guards/auth.guard';
import { CommonModule } from '@angular/common';
import { AppComponent } from './app.component';
import { HttpClient } from '@angular/common/http'; // Dodajte ovaj import
import { NewsDetailComponent } from './news-detail/news-detail.component';



export const routes: Routes = [
  { path: 'home', component: HomeComponent },
  { path: 'about', component: AboutComponent },
  { path: 'login', component: LoginComponent },
  { path: 'register', component: RegisterComponent },
  { path: 'admin-calendar', component: AdminCalendarComponent, canActivate: [AuthGuard], data: { role: 'admin' } },
    { path: 'user-calendar', component: UserCalendarComponent, canActivate: [AuthGuard], data: { role: 'user' } },

   { path: 'news/:date', component: NewsDetailComponent },
  { path: '', redirectTo: '/home', pathMatch: 'full' },
  { path: '**', redirectTo: '/home' },
];

@NgModule({
  imports: [
    RouterModule.forRoot(routes),
    FormsModule,
    HttpClientModule,
       CommonModule,
  ],
  exports: [RouterModule],
  providers: [
    provideHttpClient()
  ],
  declarations: []
})
export class AppRoutingModule { }
