import { Component, OnInit } from '@angular/core';
import { NewsService } from '../services/news.service';
import { News } from '../models/news.model';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-user-calendar',
  standalone: true,
  imports: [FormsModule, CommonModule, HttpClientModule],
  templateUrl: './user-calendar.component.html',
  styleUrls: ['./user-calendar.component.css'],
})
export class UserCalendarComponent {
  selectedDate: string = '';
  news: News[] = [];

 constructor(private newsService: NewsService) {}

  loadNewsByDate(): void {
    if (this.selectedDate) {
      this.newsService.getNewsByDate(this.selectedDate).subscribe({
        next: (response: News[]) => {
          this.news = response;
        },
        error: (err) => {
          console.error('Greška pri učitavanju vijesti za datum:', err);
        },
      });
    }
  }
}
