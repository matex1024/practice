import { Injectable, inject, signal } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { distinctUntilChanged, map } from 'rxjs';

import { API_URL, Report } from '../domain/report';

@Injectable({ providedIn: 'root' })
export class ApiService {
  #http = inject(HttpClient);
  #response = signal<Report[] | null>(null);

  get reports() {
    return this.#response.asReadonly();
  }

  doApiCall(params: Object) {
    this.#http
      .get<{ reports: Report[] }>(`${API_URL}/reports`, { 
        params:  params as any
      })
      .pipe(
        distinctUntilChanged(),
        map(({ reports }) => reports)
      )
      .subscribe((response) => this.#response.set(response));
  }
}