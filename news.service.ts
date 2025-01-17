import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, map } from 'rxjs';
import { News } from '../models/news.model';
import { HttpClientModule } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Injectable({
  providedIn: 'root',
})
export class NewsService {
  private baseUrl = 'http://localhost/news/';

  constructor(private http: HttpClient) {}

  getRandomNews(): Observable<News[]> {
    return this.http.get<{ news: News[] }>(`${this.baseUrl}get_random_news.php`).pipe(
      map(response => response.news)
    );
  }

  addNews(title: string, content: string, imageUrl: string, date: string): Observable<any> {
    const newsData = { title, content, image_url: imageUrl, date };
    return this.http.post<any>(`${this.baseUrl}add_news.php`, newsData);
  }

  getNewsByDate(date: string): Observable<News[]> {
    return this.http.get<{ news: News[] }>(`${this.baseUrl}get_news_by_date.php?date=${date}`).pipe(
      map(response => response.news)
    );
  }
}
