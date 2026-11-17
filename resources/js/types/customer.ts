export type Customer = {
    external_customer_id: number | null;
    first_name: string | null;
    last_name: string | null;
    date_of_birth: string | null;
    email: string | null;
    phone: string | null;
    ip: string | null;
    iban: string | null;
    fraud_reasons: Array<string> | null;
};
