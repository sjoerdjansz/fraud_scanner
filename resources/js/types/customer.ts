export type Customer = {
    id: number;
    external_customer_id: number | null;
    first_name: string | null;
    last_name: string | null;
    date_of_birth: string | null;
    email: string | null;
    phone: string | null;
    ip: string | null;
    iban: string | null;
    products: Array<string> | null;
    fraud_reasons: Array<string>;
    fraud_reason_labels: Array<string>;
};

export type Scan = {
    id: number;
    created_at: string;
    customers: Customer[];
};
