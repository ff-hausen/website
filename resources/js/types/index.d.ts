import { Config } from "ziggy-js";
import { Method } from "@inertiajs/core";

export interface User {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    email_verified_at?: string;
    image_url?: string;
}

export interface OAuthClient {
    id: string;
    user_id?: number;
    name: string;
    provider?: string;
    redirect: string;
    personal_access_client: boolean;
    password_client: boolean;
    revoked: boolean;
    created_at?: boolean;
    updated_at?: boolean;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
        can: {
            access_admin: boolean;
        };
    };
    ziggy: Config & { location: string };
};

export interface MainNavigationItem {
    name: string;
    href: string;
    current: boolean;
}

export interface UserNavigationItem {
    name: string;
    href: string;
    method?: Method;
}
