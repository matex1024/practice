import { AsyncPipe } from '@angular/common';
import { Component, computed, inject, resource, Signal,signal } from '@angular/core';
import { FormsModule } from '@angular/forms';

import { PanelMenuModule } from 'primeng/panelmenu';
import { TableModule } from 'primeng/table';
import { SelectModule } from 'primeng/select';
import { SplitterModule } from 'primeng/splitter';
import { DatePickerModule } from 'primeng/datepicker';
import { FloatLabelModule } from 'primeng/floatlabel';

import { ReportService } from '../service/report.service';

@Component({
  selector: 'app-root',
  imports: [PanelMenuModule, TableModule, SelectModule, SplitterModule, DatePickerModule,FloatLabelModule, AsyncPipe, FormsModule],
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss'],
  providers: [ReportService]
})
export class AppComponent {
  dateFrom: Date | null = null;
  dateTo: Date | null = null;
  room: string | null = null;
  reportService = inject(ReportService);
  reports = this.reportService.reports || [];
  roomOptions: { label: string; value: string }[] = [];

  fetchReports(): void {
    const dateFromStr = this.dateFrom ? this.dateFrom.toISOString().split('T')[0] : '';
    const dateToStr = this.dateTo ? this.dateTo.toISOString().split('T')[0] : '';
    this.reportService.fetchReports(dateFromStr, dateToStr, this.room || '');
  }

  getRoomOptions() {
    for(let i =1; i<=10; i++){
      this.roomOptions.push({label: 'pokoj-'+i, value: 'pokoj '+i});
    }
  }

  ngOnInit(): void {
    this.reportService.fetchReports();
    this.getRoomOptions();
  }
}
