-- AMVAC 2027 Database Seed Data

-- Default Super Administrator (admin@amvac2027.org / ChangeMe2027!Secure)
INSERT INTO admins (name, email, password_hash, role, is_active)
VALUES (
    'AMVAC Lead Administrator',
    'admin@amvac2027.org',
    '$2y$10$pxiIvScNJY6igvWkIugnNuBPucxrkL00XlEQCMRS96yb3gEjvwUZq',
    'super_admin',
    1
) ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Default Application Settings
INSERT INTO settings (setting_key, setting_value, description, is_sensitive) VALUES
('site_title', 'Africa Motor Vehicle Administration Conference 2027', 'Official Public Conference Name', 0),
('event_dates', '15–19 March 2027', 'Provisional Event Dates (D-02)', 0),
('event_venue', 'BICC Conference Centre, Banjul, The Gambia', 'Provisional Conference Venue (D-02)', 0),
('event_format', 'Hybrid – Abuja & Banjul', 'Participation Modality (D-02)', 0),
('payment_gateway_status', 'sandbox_stub', 'Payment Gateway State (D-07: live disabled until authorized)', 0),
('bank_display_public', '0', 'Flag to reveal bank account details on public pages (0=hidden, 1=visible)', 1),
('bank_account_beneficiary', 'PENDING CLIENT APPROVAL', 'Beneficiary name on Bloom Bank Africa account (D-06)', 1)
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- Initial Controlled Content Entries
INSERT INTO content_entries (content_key, title, content_value, is_published) VALUES
('hero_intro', 'Hero Introduction Banner', 'Enhancing Continental Security, Road Safety and Economic Integration Through Effective Motor Vehicle Administration in Africa.', 1),
('about_summary', 'Conference Purpose Statement', 'The Africa Motor Vehicle Administration Conference 2027 is a continental platform for dialogue, collaboration and action on effective motor vehicle administration. The conference brings together policymakers, regulators, road safety experts and stakeholders to promote harmonized systems, stronger security, safer roads and economic integration across Africa.', 1),
('programme_status_note', 'Official Schedule Notice', 'The detailed day-by-day conference timetable, plenary speaker roster and technical working sessions are currently undergoing final committee sign-off and will be published upon ratification.', 1)
ON DUPLICATE KEY UPDATE title = VALUES(title);
