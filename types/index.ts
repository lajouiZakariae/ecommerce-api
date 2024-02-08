export interface Category {
    id?: number;
    name: string;
    description?: string;
    createdAt?: Date;
    updatedAt?: Date;
}

export interface CouponCode {
    id?: number;
    code: string;
    amount: number;
    createdAt?: Date;
    updatedAt?: Date;
}

export interface History {
    id?: number;
    operation: Operation;
    productId: number;
    createdAt?: Date;
    updatedAt?: Date;
}

export enum Operation {
    DAMAGE = "damage",
    ORDER = "order",
    SALE = "sale",
}

export interface Medium {
    id?: number;
    altText: string;
    path: string;
    type: Type;
    productId: number;
    createdAt?: Date;
    updatedAt?: Date;
}

export enum Type {
    IMAGE = "image",
    VIDEO = "video",
}

export interface OrderItem {
    id?: number;
    orderId: number;
    productId: number;
    quantity: number;
    createdAt?: Date;
    updatedAt?: Date;
}

export interface Order {
    id?: number;
    fullName: string;
    email: string;
    phoneNumber: string;
    status: Status;
    city: string;
    paymentMethodId: number;
    zipCode: string;
    couponCodeId: number;
    address: string;
    delivery: boolean;
    createdAt?: Date;
    updatedAt?: Date;
}

export enum Status {
    PENDING = "pending",
    IN_TRANSIT = "in transit",
    DELIVERED = "delivered",
    DELIVERY_ATTEMPT = "delivery attempt",
    CANCELLED = "cancelled",
    RETURN_TO_SENDER = "return to sender",
}

export interface PaymentMethod {
    id?: number;
    name: string;
    description?: string;
    createdAt?: Date;
    updatedAt?: Date;
    url?: string;
}

export interface Product {
    id?: number;
    title: string;
    description?: string;
    cost: number;
    price: number;
    stockQuantity: number;
    published: boolean;
    categoryId: number;
    storeId?: number;
    createdAt?: Date;
    updatedAt?: Date;
}

export interface PurchaseItem {
    id?: number;
    purchaseId: number;
    productId: number;
    quantity: number;
    createdAt?: Date;
    updatedAt?: Date;
}

export interface Purchase {
    id?: number;
    supplierId: number;
    deliveryDate: Date;
    paid: boolean;
    paymentMethodId?: number;
    storeId: number;
    createdAt?: Date;
    updatedAt?: Date;
}

export interface Review {
    id?: number;
    email: string;
    body: string;
    productId: number;
    approved: boolean;
    createdAt?: Date;
    updatedAt?: Date;
}

export interface Role {
    id?: number;
    name: string;
    createdAt?: Date;
    updatedAt?: Date;
}

export interface Setting {
    id?: number;
    platform: Platform;
    settingsValue: string;
    settingsDefault: string;
}

export enum Platform {
    DESKTOP = "desktop",
    WEB_CLIENT = "web_client",
    WEB_ADMIN = "web_admin",
}

export interface Store {
    id?: number;
    name: string;
    address?: string;
    latitude?: number;
    longitude?: number;
    createdAt?: Date;
    updatedAt?: Date;
}

export interface Supplier {
    id?: number;
    name: string;
    email: string;
    phoneNumber: string;
    address: string;
    createdAt?: Date;
    updatedAt?: Date;
}
