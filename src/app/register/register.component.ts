import { Component } from '@angular/core';
import { HttpClient, HttpClientModule, HttpHeaders } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterLink, RouterLinkActive } from '@angular/router';

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [CommonModule, FormsModule, HttpClientModule, RouterLink, RouterLinkActive],
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css'],
})
export class RegisterComponent {
  username: string = '';
  email: string = '';
  password: string = '';
  errorMessage: string = '';
  successMessage: string = '';

  constructor(private http: HttpClient) {}

  onRegister() {

    const jsonData = {
      username: this.username,
      email: this.email,
      password: this.password,
    };

    const headers = new HttpHeaders({ 'Content-Type': 'application/json' });

    this.http
      .post('http://localhost/news/register.php', jsonData, {
        headers: headers,
        responseType: 'json',
      })
      .subscribe({
        next: (response: any) => {

          if (response.status === 'success') {
            this.successMessage = response.message || 'Registracija uspješna!';
          } else {
            this.errorMessage = response.message || 'Greška pri registraciji.';
          }
        },
        error: (error) => {
          this.errorMessage = 'Došlo je do greške prilikom povezivanja sa serverom.';
          console.error(error);
        },
      });
  }
}
