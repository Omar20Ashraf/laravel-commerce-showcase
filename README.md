# Laravel Commerce Showcase

A Laravel-based service marketplace platform where users subscribe, browse service packages, and pay through multiple payment gateways depending on their location.

---

## Table of Contents

- [How to Run the Project](#how-to-run-the-project)
- [To-Do List](#to-do-list)
- [Business Logic](#business-logic)
- [Database Structure](#database-structure)

---

## How to Run the Project

### Requirements

- PHP >= 8.3
- Composer
- MySQL (or any supported database)

### Installation Steps

**1. Clone the repository**

```bash
git clone https://github.com/Omar20Ashraf/laravel-commerce-showcase
cd laravel-commerce-showcase
```

**2. Install PHP dependencies**

```bash
composer install
```

**3. Set up environment file**

```bash
cp .env.example .env
```

Open `.env` and configure your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel-commerce-showcase
DB_USERNAME=root
DB_PASSWORD=
```

**4. Create the database**

Create a new MySQL database named `laravel-commerce-showcase` (or whatever you set in `DB_DATABASE`).

**5. Generate application key**

```bash
php artisan key:generate
```

**6. Run migrations and seed the database**

```bash
php artisan migrate --seed
```

**7. Serve the application**

```bash
php artisan serve
```

The application will be available at `http://127.0.0.1:8000`.

---

## To-Do List

The following features and improvements are pending implementation:

- [ ] **Console command** — Create a scheduled Artisan command to automatically close overdue invoices, bookings, and order items based on configurable due dates
- [ ] **Application design** — Implement the full UI/UX design across all pages and views of the application
- [ ] **Payment callback handling** — Handle success and failure payment callbacks properly; currently the callbacks are only received but no action is taken on either outcome
- [ ] **Callback signature validation** — Validate the webhook/callback signature for every supported payment gateway to ensure request authenticity
- [ ] **Admin dashboard** — Build an admin panel to list, create, and manage services, packages, service items, and related entities
- [ ] **Provider order approval flow** — Implement the provider approval step so that a provider must explicitly approve each order item before an invoice with a payment token is generated

---

## Business Logic

### Subscription & Free Trial

- When a user registers, they automatically receive a free trial subscription for a configurable number of days (defined in the application config).
- While the user is within the free trial period, any item they checkout will have a fee of **0**.

### Cart

- Users can add active service items to the cart.
- The cart supports both **guest users** (identified by IP address) and **authenticated users**.
- When a guest registers or logs in, the system checks for any cart items associated with their IP that have no `user_id`. If found, those items are transferred to the authenticated user's account.

### Checkout & Order Flow

1. **Checkout** — A successful checkout creates an **Order** containing one or more **Order Items**.
2. **Provider Approval** — Each order item must be individually approved by the respective service provider.
3. **Invoice Creation** — Once the **last** order item is approved, an **Invoice** is created. The invoice contains **Invoice Lines** that correspond 1:1 with all approved order items.
4. **Payment Token** — A payment token is generated alongside the invoice and is used to initiate the payment flow.

### Web to API Handoff

After the invoice and payment token are created on the web side, the user is transferred to the **API layer** to complete payment. The API is versioned.

### API Endpoints

**`GET /api/v1/invoices/{paymentToken}`** — Invoice Details & Available Gateways

Returns the full invoice data along with the list of payment gateways available to the user. Gateway availability is determined by:
- The user's **city**
- The **module** (type/category) of the invoice

---

**`POST /api/v1/transactions`** — Initiate Payment Transaction

Starts a payment transaction with the selected payment gateway using the **hosted page** method. Returns a redirect URL to which the customer must be forwarded to complete payment on the gateway's page.

---

**`POST /api/v1/callback`** — Payment Gateway Callback

Receives the asynchronous callback from the payment gateway after the transaction is completed. Handles both success and failure outcomes and validates the callback signature.

---

## Database Structure

### Service Catalog

```
providers
services
service_items
packages
package_service             (pivot)
```

A **provider** owns multiple **services**. Each service contains multiple **service items**, which are the actual purchasable units added to the cart. **Packages** group services together via the `package_service` pivot table.

---

### Cart & Orders

```
carts
cart_items
orders
order_items
```

---

### Invoices (Polymorphic)

The invoice is polymorphic and can belong to either an **Order** or a **Subscription**, allowing both service purchases and subscription renewals to share the same invoicing and payment flow.

```
invoices
invoice_lines
```

---

### Subscriptions

```
subscriptions
```

---

### Payment Gateways

Gateway availability per transaction is determined by the user's city and the invoice module (type).

```
gateways

gateway_module              (pivot: gateway ↔ invoice module/type)

city_gateway                (pivot: gateway ↔ city)
```

---

### Status (Polymorphic)

Statuses are tracked polymorphically, meaning any model (order, order item, invoice, subscription, etc.) can have a status history without dedicated status columns on each table.

```
statuses

status_related_object       (polymorphic pivot)
```

This design allows a full **status history** to be maintained per record, and new models can be tracked simply by morphing to this table — no migration changes required.
