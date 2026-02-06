CREATE DATABASE IF NOT EXISTS spk_bansos;
USE spk_bansos;

-- Users Table (Admins)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'admin',
    full_name VARCHAR(100),
    avatar VARCHAR(255)
);

-- Criteria Table (AHP Criteria)
CREATE TABLE criteria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10) NOT NULL, -- e.g., K1
    name VARCHAR(100) NOT NULL,
    weight DECIMAL(5, 4) NOT NULL, -- Normalized weight
    type ENUM('benefit', 'cost') NOT NULL,
    description TEXT
);

-- Alternatives Table (Beneficiaries)
CREATE TABLE alternatives (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    nik VARCHAR(20) UNIQUE,
    address TEXT,
    kecamatan VARCHAR(50),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Evaluations Table (Alternative Scores per Criteria)
CREATE TABLE evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alternative_id INT NOT NULL,
    criteria_id INT NOT NULL,
    value DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (alternative_id) REFERENCES alternatives(id) ON DELETE CASCADE,
    FOREIGN KEY (criteria_id) REFERENCES criteria(id) ON DELETE CASCADE
);

-- Rankings Table (Final Results)
CREATE TABLE rankings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alternative_id INT NOT NULL,
    score DECIMAL(10, 4) NOT NULL,
    rank_position INT,
    calculated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (alternative_id) REFERENCES alternatives(id) ON DELETE CASCADE
);

-- Reports Table (History)
CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('PDF', 'Excel', 'Cetak') NOT NULL,
    author VARCHAR(100) NOT NULL,
    file_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed Data (Extended)
INSERT INTO users (username, password, full_name) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin Utama');

INSERT INTO criteria (code, name, weight, type) VALUES 
('K1', 'Penghasilan Bulanan', 0.35, 'cost'),
('K2', 'Jumlah Tanggungan', 0.25, 'benefit'),
('K3', 'Kondisi Rumah', 0.20, 'benefit'),
('K4', 'Status Pekerjaan', 0.15, 'cost'),
('K5', 'Aset Lainnya', 0.05, 'cost');

INSERT INTO alternatives (name, kecamatan) VALUES 
('Siti Aminah', 'Kec. Hegarmanah'),
('Budi Santoso', 'Kec. Sukasari'),
('Wawan Hendrawan', 'Kec. Cidadap'),
('Ratna Sari', 'Kec. Sukasari'),
('Asep Sunandar', 'Kec. Coblong');

INSERT INTO reports (name, type, author, created_at) VALUES
('Laporan_Bansos_Desember_2025.pdf', 'PDF', 'Admin Utama', '2025-12-25 14:30:00'),
('Rekapitulasi_Nilai_Akhir.xlsx', 'Excel', 'Budi Santoso', '2025-12-24 09:15:00'),
('Laporan_Penerima_Tahap_1.pdf', 'PDF', 'Admin Utama', '2025-11-10 11:20:00');
