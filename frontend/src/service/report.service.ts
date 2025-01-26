import { Injectable } from '@angular/core';
import { Report } from '@/domain/report';
@Injectable()
export class ReportService {
    getReportData() : Report[] {
        return [
            { name: 'Test 1', date: '2025-01-01', time: '10:00', user: 'User A', room: 'Pokoj 1' },
            { name: 'Test 2', date: '2025-01-02', time: '11:00', user: 'User B', room: 'Pokoj 3' },
            { name: 'Test 3', date: '2025-01-03', time: '13:00', user: 'User C', room: 'Pokoj 2' },
        ];
    }

    getReport(): Promise<Report[]> {
        return Promise.resolve(this.getReportData());
    }
}
