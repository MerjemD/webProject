import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';

export interface News {
  id: number;
  title: string;
  content: string;
  date: string;
  image_url?: string;
  created_at: string;
}
