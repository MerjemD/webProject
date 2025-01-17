/*sifra za admina- Merj@123, za usera- Prviuser@123*/
import { Component } from '@angular/core';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../services/auth.service';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, FormsModule, HttpClientModule],
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
})
export class LoginComponent {
  emailOrUsername: string = '';
  password: string = '';
  message: string = '';
  errorMessage: string = '';
  successMessage: string = '';

  constructor(
    private authService: AuthService,
    private router: Router
  ) {}

  onLogin() {
    this.authService.login(this.emailOrUsername, this.password).subscribe(
      (response: any) => {
        if (response.status === 'success') {
          this.message = `Uspješna prijava. Vaša uloga je: ${response.role}`;


          localStorage.setItem('user', JSON.stringify({ role: response.role }));


          if (response.role === 'admin') {
            this.router.navigate(['/admin-calendar']);
          } else {
            this.router.navigate(['/user-calendar']);
          }
        } else {
          this.message = 'Pogrešno korisničko ime ili lozinka';
        }
      },
      (error) => {
        this.message = 'Greška prilikom prijave';
      }
    );
  }
}
