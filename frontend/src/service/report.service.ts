import { Injectable, inject, signal } from '@angular/core';
import { HttpClient , HttpParams} from '@angular/common/http';
import { Observable } from 'rxjs';

import { API_URL, Report } from '../domain/report';

@Injectable({ providedIn: 'root' })
export class ReportService {
  #http = inject(HttpClient);
  #response = signal<Report[] | null>(null);

  get reports() {
    return this.#response.asReadonly();
  }

  fetchReports(dateFrom?: string, dateTo?: string, room?: string):void {
    let params = new HttpParams();
    if (dateFrom) params = params.set('date_from', dateFrom);
    if (dateTo) params = params.set('date_to', dateTo);
    if (room) params = params.set('room', room);

    this.#http.get<Report[]>(API_URL, { params }).subscribe({
      next: (data) => this.#response.set(data),
      error: (err) => console.error('Error fetching reports:', err),
    });
  }
}