-- Supabase (PostgreSQL) Schema for Perfect Enterprises Courier Tracking

-- Drop existing tables/types if they exist (useful for iterating)
DROP TABLE IF EXISTS tracking_timeline CASCADE;
DROP TABLE IF EXISTS shipments CASCADE;
DROP TABLE IF EXISTS pincodes CASCADE;

CREATE TABLE shipments (
    id SERIAL PRIMARY KEY,
    tracking_id VARCHAR(50) UNIQUE NOT NULL,
    partner_id VARCHAR(50) DEFAULT NULL,
    sender_name VARCHAR(100) NOT NULL,
    receiver_name VARCHAR(100) NOT NULL,
    destination_pincode VARCHAR(10) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending' CHECK (status IN ('Pending', 'In Transit', 'Out for Delivery', 'Delivered', 'Failed Attempt', 'Returned')),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tracking_timeline (
    id SERIAL PRIMARY KEY,
    shipment_id INTEGER NOT NULL REFERENCES shipments(id) ON DELETE CASCADE,
    status VARCHAR(50) NOT NULL,
    location VARCHAR(100) NOT NULL,
    description TEXT,
    timestamp TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pincodes (
    id SERIAL PRIMARY KEY,
    pincode VARCHAR(10) UNIQUE NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    is_serviceable BOOLEAN DEFAULT TRUE,
    partner_courier VARCHAR(50) DEFAULT 'Direct'
);

-- Function and Trigger to automatically update the updated_at column
CREATE OR REPLACE FUNCTION update_modified_column()   
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = now();
    RETURN NEW;   
END;
$$ language 'plpgsql';

CREATE TRIGGER update_shipments_modtime 
BEFORE UPDATE ON shipments 
FOR EACH ROW EXECUTE PROCEDURE update_modified_column();

-- Indexes for performance
CREATE INDEX idx_tracking_id ON shipments(tracking_id);
CREATE INDEX idx_partner_id ON shipments(partner_id);
CREATE INDEX idx_pincode ON pincodes(pincode);

-- Enable Row Level Security (RLS) - Required for Supabase best practices
ALTER TABLE shipments ENABLE ROW LEVEL SECURITY;
ALTER TABLE tracking_timeline ENABLE ROW LEVEL SECURITY;
ALTER TABLE pincodes ENABLE ROW LEVEL SECURITY;

-- Create policies for public read access (example)
CREATE POLICY "Allow public read access to shipments" ON shipments FOR SELECT USING (true);
CREATE POLICY "Allow public read access to tracking timeline" ON tracking_timeline FOR SELECT USING (true);
CREATE POLICY "Allow public read access to pincodes" ON pincodes FOR SELECT USING (true);
