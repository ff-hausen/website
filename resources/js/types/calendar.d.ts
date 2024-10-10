export interface CalendarEventType {
    id: number;
    name: string;
    created_at: string;
    updated_at: string;
}

export interface CalendarResponsibleUser {
    id: number;
    full_name: string;
    image_url: string;
}

export interface CalenderEvent {
    id: number;
    title: string;
    start_time: string;
    end_time: string;
    all_day: boolean;
    department: string;
    created_at: string;
    updated_at: string;
    type: CalendarEventType;
    responsible: Array<CalendarResponsibleUser>;
}
