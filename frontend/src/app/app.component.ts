import { AsyncPipe } from '@angular/common';
import { Component, computed, inject, resource, Signal,signal } from '@angular/core';

import { PanelMenuModule } from 'primeng/panelmenu';
import { TableModule } from 'primeng/table';
import { SelectModule } from 'primeng/select';
import { SplitterModule } from 'primeng/splitter';
import { DatePickerModule } from 'primeng/datepicker';
import { FloatLabelModule } from 'primeng/floatlabel';

import { Report } from '../domain/report';
import { ReportService } from '../service/report.service';
import { ApiService } from '../service/api.service';

@Component({
  selector: 'app-root',
  imports: [PanelMenuModule, TableModule, SelectModule, SplitterModule, DatePickerModule,FloatLabelModule, AsyncPipe],
  templateUrl: './app.component.html',
  styleUrl: './app.component.scss',
  providers: [ReportService,]

})
export class AppComponent {
  room = signal('pokoj 1');
  dateFrom = signal('2020-01-01');
  dateTo = signal('2020-01-01');

  fromDate = computed(() => new Date(this.dateFrom()));
  toDate = computed(() => new Date(this.dateTo()));

  reports!: Report[];
  constructor(private reportService: ReportService) {}

  ngOnInit() {
      this.reportService.getReport().then((data: Report[]) => {
          this.reports = data; 
      });
   }
}
