/*
  # Create Orders and Messages Tables

  1. New Tables
    - `orders`
      - `id` (uuid, primary key)
      - `order_id` (text, unique) - Human-readable order ID
      - `customer_name` (text) - Customer full name
      - `customer_email` (text) - Customer email address
      - `customer_phone` (text) - Customer phone number
      - `delivery_address` (text) - Delivery address
      - `items` (jsonb) - Array of order items with product details
      - `subtotal` (decimal) - Order subtotal
      - `tax` (decimal) - Tax amount (15%)
      - `total` (decimal) - Total amount
      - `status` (text) - Order status (default: 'pending')
      - `created_at` (timestamptz) - Order creation timestamp

    - `messages`
      - `id` (uuid, primary key)
      - `name` (text) - Sender name
      - `email` (text) - Sender email
      - `subject` (text) - Message subject
      - `message` (text) - Message content
      - `status` (text) - Message status (default: 'unread')
      - `created_at` (timestamptz) - Message creation timestamp

  2. Security
    - Enable RLS on both tables
    - Add policies for public insert (orders and messages submission)
    - Add policies for authenticated admin access to read data
*/

CREATE TABLE IF NOT EXISTS orders (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  order_id text UNIQUE NOT NULL,
  customer_name text NOT NULL,
  customer_email text NOT NULL,
  customer_phone text NOT NULL,
  delivery_address text NOT NULL,
  items jsonb NOT NULL,
  subtotal decimal(10, 2) NOT NULL DEFAULT 0,
  tax decimal(10, 2) NOT NULL DEFAULT 0,
  total decimal(10, 2) NOT NULL DEFAULT 0,
  status text NOT NULL DEFAULT 'pending',
  created_at timestamptz DEFAULT now()
);

CREATE TABLE IF NOT EXISTS messages (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  name text NOT NULL,
  email text NOT NULL,
  subject text NOT NULL,
  message text NOT NULL,
  status text NOT NULL DEFAULT 'unread',
  created_at timestamptz DEFAULT now()
);

ALTER TABLE orders ENABLE ROW LEVEL SECURITY;
ALTER TABLE messages ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Anyone can create orders"
  ON orders FOR INSERT
  TO anon
  WITH CHECK (true);

CREATE POLICY "Authenticated users can read orders"
  ON orders FOR SELECT
  TO authenticated
  USING (true);

CREATE POLICY "Anyone can send messages"
  ON messages FOR INSERT
  TO anon
  WITH CHECK (true);

CREATE POLICY "Authenticated users can read messages"
  ON messages FOR SELECT
  TO authenticated
  USING (true);

CREATE INDEX IF NOT EXISTS idx_orders_created_at ON orders(created_at DESC);
CREATE INDEX IF NOT EXISTS idx_orders_status ON orders(status);
CREATE INDEX IF NOT EXISTS idx_messages_created_at ON messages(created_at DESC);
CREATE INDEX IF NOT EXISTS idx_messages_status ON messages(status);
