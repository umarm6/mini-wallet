export interface User {
    id: number;
    name: string;
    email: string;
    balance: string | number;
    created_at: string;
    updated_at: string;
}

export interface Transaction {
    id: number;
    sender_id: number;
    receiver_id: number;
    from_id?: number;
    to_id?: number;
    amount: string | number;
    commission_fee: string | number;
    status: 'completed' | 'pending' | 'failed';
    reference?: string;
    type?: 'sent' | 'received';
    created_at: string;
    updated_at?: string;
    sender?: User;
    receiver?: User;
}

export interface AuthResponse {
    user: User;
    token: string;
}

export interface TransferResponse {
    status: 'success' | 'error';
    transaction: Transaction;
    new_balance: string | number;
    errors?: Record<string, string[]>;
}

export interface TransactionHistoryResponse {
    data: Transaction[];
    balance: string | number;
    links?: {
        first: string;
        last: string;
        prev: string | null;
        next: string | null;
    };
    meta?: {
        current_page: number;
        from: number;
        last_page: number;
        per_page: number;
        to: number;
        total: number;
    };
}

export interface RegisterCredentials {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
}

export interface LoginCredentials {
    email: string;
    password: string;
}

export interface TransferRequest {
    receiver_id: number;
    amount: number | string;
}

export interface ApiError {
    message: string;
    errors?: Record<string, string[]>;
}

export interface PusherTransactionEvent {
    transaction: Transaction;
    sender_update: {
        balance: string | number;
        user_id: number;
    };
    receiver_update: {
        balance: string | number;
        user_id: number;
    };
}
