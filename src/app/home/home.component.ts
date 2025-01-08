import { Component, OnInit } from '@angular/core';
import { NewsService } from '../services/news.service';
import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { News } from '../models/news.model';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-home',
  standalone: true,
  imports:[ CommonModule, HttpClientModule, FormsModule],
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css'],
})
export class HomeComponent implements OnInit {
  news: News[] = [];

  constructor(private newsService: NewsService) {}

  ngOnInit(): void {
    this.loadRandomNews();
  }
openNews(item: any): void {
  const newsUrl = `/news/${item.id}`;
  window.open(newsUrl, '_blank');
}

  loadRandomNews(): void {
    this.newsService.getRandomNews().subscribe({
      next: (response: News[]) => {
        this.news = response;
      },
      error: (err) => {
        console.error('Greška pri učitavanju vijesti:', err);
      },
    });
  }
}
