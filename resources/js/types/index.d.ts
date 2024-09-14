import { Config } from "ziggy-js";

export interface User {
    id: number;
    first_name: string;
    last_name: string;
    username: string;
    email: string;
    email_verified_at?: string;
    image_url?: string;
    role_names?: Array<string>;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
    ziggy: Config & { location: string };
};
