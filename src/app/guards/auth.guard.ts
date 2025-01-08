import { Injectable } from '@angular/core';
import { CanActivate, Router } from '@angular/router';
import { HttpClientModule } from '@angular/common/http';
import { CommonModule } from '@angular/common';




@Injectable({ providedIn: 'root' })
export class AuthGuard implements CanActivate {
  constructor(private router: Router) {}



  canActivate(): boolean {
    const user = JSON.parse(localStorage.getItem('user') || '{}');

    if (user && user.role) {
      return true;
    }

    this.router.navigate(['/login']);
    return false;
  }
}


