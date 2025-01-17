import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { HttpClient } from '@angular/common/http';
import { News } from '../models/news.model';

@Component({
  selector: 'app-admin-calendar',
  standalone: true,
  imports: [CommonModule, FormsModule, HttpClientModule],
  templateUrl: './admin-calendar.component.html',
  styleUrls: ['./admin-calendar.component.css'],
})
export class AdminCalendarComponent {
  selectedDate: string = '';
  news: { date: string; title: string; content: string; image_url?: string }[] = [];
  newTitle: string = '';
  newContent: string = '';
  selectedFile: File | null = null;
  allNews = [
    { date: '2024-12-21', title: 'Naslov 1', content: 'Sadržaj vijesti 1', image_url: '' },
    { date: '2024-12-22', title: 'Naslov 2', content: 'Sadržaj vijesti 2', image_url: '' },
  ];

  constructor(private http: HttpClient) {}

  fetchNewsForDate() {
    this.news = this.allNews.filter((item) => item.date === this.selectedDate);
  }

  onFileSelected(event: Event) {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files.length > 0) {
      this.selectedFile = input.files[0];
    }
  }

  addNews() {
    if (this.newTitle && this.newContent && this.selectedDate) {
      const formData = new FormData();
      formData.append('date', this.selectedDate);
      formData.append('title', this.newTitle);
      formData.append('content', this.newContent);
      if (this.selectedFile) {
        formData.append('image', this.selectedFile);
      }

      this.http.post('http://localhost/news/add_news.php', formData).subscribe(
        (response: any) => {
          alert(response.message);
          if (response.status === 'success') {
            this.allNews.push({
              date: this.selectedDate,
              title: this.newTitle,
              content: this.newContent,
              image_url: response.image_url || '',
            });
            this.fetchNewsForDate();
            this.newTitle = '';
            this.newContent = '';
            this.selectedFile = null;
          }
        },
        (error) => {
          console.error('Greška prilikom dodavanja vijesti:', error);
          alert('Došlo je do greške.');
        }
      );
    } else {
      alert('Molimo popunite sve podatke.');
    }
  }
}
