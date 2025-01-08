import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { NewsService } from '../services/news.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-news-detail',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './news-detail.component.html',
  styleUrls: ['./news-detail.component.css']
})
export class NewsDetailComponent implements OnInit {
  news: any ;

  constructor(private route: ActivatedRoute, private newsService: NewsService) {}

  ngOnInit(): void {
    const date = this.route.snapshot.paramMap.get('date');
    if (date) {
      this.newsService.getNewsByDate(date).subscribe((data: any[]) => {

        if (data && data.length > 0) {
                  this.news = data[0];
                } else {
                  this.news = null;
                }
      });
    }
  }
}
